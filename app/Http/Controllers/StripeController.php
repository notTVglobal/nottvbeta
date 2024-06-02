<?php

namespace App\Http\Controllers;

use App\Helpers\SubscriptionHelper;
use App\Models\AppSetting;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Stripe\Customer;
use Stripe\Exception\InvalidRequestException;
use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stripe\Stripe as StripeGateway;
use Throwable;

class StripeController extends Controller {

  public function __construct() {
    // Set the Stripe API key from the environment variable
    Stripe::setApiKey(config('services.stripe.secret'));
  }

  public function purchase(Request $request) {

    // make a purchase

    // get the user
    $user_id = auth()->user()->id;
    $user = User::find($user_id);

    // validate the request
    $attributes = \Illuminate\Support\Facades\Request::validate([
        'name'       => ['required', 'string', 'max:255'],
//            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        'address1'   => ['nullable', 'string', 'max:255'],
        'address2'   => ['nullable', 'string', 'max:255'],
        'city'       => ['nullable', 'string', 'max:255'],
        'province'   => ['nullable', 'string', 'max:255'],
        'country'    => ['nullable', 'string', 'max:255'],
        'postalCode' => ['nullable', 'string', 'max:255'],
        'phone'      => ['nullable', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
    ]);

    // update the user
    $user->update($attributes);

    try {
      $payment = $user->charge(
          $request->input('amount'),
          $request->input('payment_method_id')
      );

      $payment = $payment->asStripePaymentIntent();

      $order = $user->orders()
          ->create([
              'transaction_id' => $payment->charges->data[0]->id,
              'total'          => $payment->charges->data[0]->amount
          ]);

      foreach (json_decode($request->input('cart'), true) as $item) {
        $order->products()
            ->attach($item['id'], ['quantity' => $item['quantity']]);
      }

      $order->load('products');

      return $order;
    } catch (\Exception $e) {
      return response()->json(['message' => $e->getMessage()], 500);
    }
  }

  public function subscription() {

    $user = auth()->user();

    if ($user->subscribed('default')) {
      return back()->with('message', 'You are already subscribed. To check your subscription go to your \'account\' from the top right menu.');
    }

    // Check if the user has a Stripe customer ID
    if ($user->stripe_id) {
      try {
        // Attempt to retrieve the customer to see if they still exist
        $stripeCustomer = Customer::retrieve($user->stripe_id);
      } catch (InvalidRequestException $e) {
        // If the customer does not exist, clear the stripe_id and create a new customer
        if ($e->getStripeCode() === 'resource_missing') {
          $user->stripe_id = null;
          $user->save();
        } else {
          throw $e; // Rethrow any other exceptions
        }
      }
    }

    // If the user does not have a Stripe customer ID, attempt to fetch it
    if (!$user->stripe_id) {
      // Search for an existing customer by email
      $customers = Customer::all(['email' => $user->email]);

      if (count($customers->data) > 0) {
        // Use the existing customer ID
        $user->stripe_id = $customers->data[0]->id;
        $user->save();
      } else {
        // Create a new customer if none exist
        $user->createAsStripeCustomer();
      }
    }
    // Create a setup intent
    $intent = $user->createSetupIntent();

    $settings = AppSetting::first(); // Adjust the query as needed

    // Process subscription settings to convert prices from cents to dollars
    $subscriptionSettings = SubscriptionHelper::processSubscriptionSettings($settings->subscription_settings);

    return Inertia::render('Shop/Subscription', [
      // TODO: list customer's payment methods:
        'payment_method'       => $user->defaultPaymentMethod(),
        'intent'               => $intent,
        'subscriptionSettings' => $subscriptionSettings,
    ]);
  }

  public function oneTimeContribution() {

    $user = auth()->user();

    return Inertia::render('Shop/OneTimeContribution', [
      // TODO: list customer's payment methods:
//        'payment_method' => $user->defaultPaymentMethod(),
//        'intent'         => $user->createSetupIntent(),
    ]);
  }

  public function createPaymentIntent(Request $request) {
    // Retrieve settings from the database
    $settings = AppSetting::first();
    if (!$settings || !isset($settings->subscription_settings['stripe_secret_key'])) {
      Log::error('Stripe secret key is not set in the database');
      return response()->json(['error' => 'Stripe secret key is not set in the database'], 500);
    }

    $stripeSecret = $settings->subscription_settings['stripe_secret_key'];

    Stripe::setApiKey($stripeSecret);
    $user = auth()->user();

    // Check if the user already has a Stripe customer ID
    if (!$user->stripe_id) {
      // Create a new customer if not found
      $customer = Customer::create([
          'email' => $user->email,
          'name'  => $user->name,
      ]);

      // Save the customer ID in the user's record for future use
      $user->stripe_id = $customer->id;
      $user->save();
    } else {
      // Retrieve the existing customer ID
      $customer = Customer::retrieve($user->stripe_id);
    }

    $type = '';
    $description = '';

    if (!$request->favourite_item_type) {
      $type = 'donation to not.tv';
      $description = 'Donation to not.tv';
    } else {
      $type = 'donation';
      $description = 'Donation for ' . $request->favourite_item_type . ': ' . $request->favourite_item_name;
    }

    $paymentIntent = PaymentIntent::create([
        'amount'      => $request->amount,
        'currency'    => 'cad',
        'customer'    => $user->stripe_id ?? null,
        'description' => $description,
        'metadata'    => [
            'favourite_item_id'   => $request->favourite_item_id,
            'favourite_item_type' => $request->favourite_item_type,
            'favourite_item_name' => $request->favourite_item_name,
            'user_name'           => $user->name,
            'user_email'          => $user->email,
            'type'                => $type,
        ],
    ]);

    return response()->json(['clientSecret' => $paymentIntent->client_secret]);
  }

  public function setupNewSubscription(Request $request) {
    try {
      auth()->user()->newSubscription('default', $request->plan)->create($request->paymentMethod);
    } catch (\Exception $e) {
      return Inertia::render('Shop/Subscription', [
          'error' => $e->getMessage(),
      ]);
    }

    return to_route('subscriptionSuccess');
  }

  public function subscriptionSuccessReturnUrl() {
    {
      $user = auth()->user();

      // 1. Check if the user is indeed subscribed.
      if ($user->subscribed('default')) {

        // 2. Broadcast to the Echo channel the user is subscribed to and update the userStore isSubscriber to true
        broadcast(new UserSubscribed($user));

        // 3. Return the user to the subscription success page.
        return Inertia::render('Shop/SubscriptionSuccess', [
            'userIsSubscriber' => true,
          // TODO: list customer's payment methods:
            'paymentMethods'   => $user->paymentMethods(), // Adjust this line based on how you retrieve payment methods
        ]);
      }

      return redirect()->route('stream');
    }
  }

  public function subscriptionSuccess() {
    if (auth()->user()->subscribed('default')) {
      return Inertia::render('Shop/SubscriptionSuccess', [
        // TODO: list customer's payment methods:
          'userIsSubscriber' => auth()->user()->subscribed('default'),
      ]);
    }

    return to_route('stream');
  }

  public function donationSuccess() {
    return Inertia::render('Shop/OneTimeContributionSuccess', [
    ]);
  }

  public function getBillingPortalAccessUrl(Request $request) {
    return $request->user()->billingPortalUrl(route('stream'));
//        return $request->user()->redirectToBillingPortal(route('stream'));
  }

  public function createCheckoutSession(Request $request) {

  }

  public function initiateSetup(Request $request) {
    $intent = auth()->user()->createSetupIntent();
  }

  public function initiatePayment(Request $request) {
    StripeGateway::setApiKey('STRIPE_SECRET_KEY');

    try {
      $paymentIntent = PaymentIntent::create([
          'amount'                    => $request->amount * 100, // Multiply as & when required
          'currency'                  => $request->currency,
          'automatic_payment_methods' => [
              'enabled' => true,
          ],
      ]);

      // Save the $paymentIntent->id to identify this payment later
    } catch (Exception $e) {
      // throw error
    }

    return [
        'token'         => (string) Str::uuid(),
        'client_secret' => $paymentIntent->client_secret
    ];
  }

  public function completePayment(Request $request) {
    $stripe = new StripeClient('sk_test_51KJwK5Kahp38LUVYhyBLIgpjehztUl8kdRcBK4FWXCU7sXsZ2NUOM11ktvIacKUaCbrixtcy1f3HKiZ27umNlLVb00VviyQEzc');

    // Use the payment intent ID stored when initiating payment
    $paymentDetail = $stripe->paymentIntents->retrieve('PAYMENT_INTENT_ID');

    if ($paymentDetail->status != 'succeeded') {
      // throw error
    }

    // Complete the payment
  }

  public function failPayment(Request $request) {
    // Log the failed payment if you wish
  }

  public function getUserSubscriptionsFromStripe(Request $request) {
    // these first variables go in the subscriptions table
    $name = 'default';
    $stripeSubscriptionId = ''; // e.g, sub_xx
    $stripeStatus = ''; // e.g active
    $stripePrice = ''; // e.g. price_xx
    $quantity = 0;
    $currentPeriodEnd = ''; // ends_at

    // these second variables go in the subscription_items table
    $subscriptionId = ''; // this is the id created in the subscriptions table, used to create a new row in the subscription_item table
    $stripeSubscriptionItemId = ''; // e.g. si_xx
    $stripeProduct = ''; // e.g. prod_xx
    $createdAt = '';

    // 1. Find the user and set the user stripe customer id
    $userId = $request->id;
    $user = User::find($userId);
    $customerId = $user->stripe_id; // 'cus_'

    try {

      // 2. Get all subscriptions from Stripe
      $stripeSecret = env('STRIPE_SECRET');
      $stripe = new \Stripe\StripeClient($stripeSecret);
      $subscriptions = $stripe->subscriptions->all();
      $newlyInsertedSubscriptionIds = [];
      $currentDateTime = now(); // Get the current date and time

//        dd($subscriptions);

      // 3. Loop through results where subscription belongs to customer
      foreach ($subscriptions->data as $subscription) {
        // Check if the subscription's customer matches $customerId
        if ($subscription->customer === $customerId) {
          $subscriptionId = $subscription->id;

          // 4. Create the subscriptions on the subscriptions table if they don't exist (look for existing subscription by sub_)
          // Attempt to update or insert a new subscription record into the 'subscriptions' table
          $insertedId = DB::table('subscriptions')->updateOrInsert(
              ['stripe_id' => $subscriptionId],
              [
                  'user_id'       => $userId,
                  'name'          => $name,
                  'stripe_id'     => $subscriptionId,
                  'stripe_status' => $subscription->status,
                  'stripe_price'  => $subscription->plan->id,
                  'quantity'      => $subscription->quantity,
                  'trial_ends_at' => date('Y-m-d H:i:s', $subscription->trial_end),
                  'ends_at'       => date('Y-m-d H:i:s', $subscription->current_period_end),
                  'created_at'    => date('Y-m-d H:i:s', $subscription->created),
                  'updated_at'    => $currentDateTime,
              ]
          );

          // If a new subscription was inserted or an existing one was updated, store the ID
          if ($insertedId) {
            $newlyInsertedSubscriptionIds[] = $insertedId;

            // Check if the subscription has items
            if (isset($subscription->items) && isset($subscription->items->data[0])) {
              $item = $subscription->items->data[0];
              $itemId = $item->id;

              // 5. Create the subscription_item on the subscription_items table
              // Attempt to update or insert a new subscription_items record
              DB::table('subscription_items')->updateOrInsert(
                  ['stripe_id' => $itemId],
                  [
                      'subscription_id' => $insertedId,
                      'stripe_id'       => $itemId,
                      'stripe_product'  => $subscription->plan->product,
                      'stripe_price'    => $subscription->plan->id,
                      'quantity'        => $subscription->quantity,
                      'created_at'      => date('Y-m-d H:i:s', $subscription->created),
                      'updated_at'      => $currentDateTime,
                  ]
              );
            }
          }
        }
      }

      // Add any additional logic you need after processing subscriptions and creating subscription items
      // 6. Update the user (if we have the pm_type, pm_last_four, trial_ends_at information)

      // Check if no subscriptions were retrieved
      if (count($newlyInsertedSubscriptionIds) === 0) {
        return Redirect::route('users.index')->with('message', 'No subscriptions were retrieved for ' . $user->name . '.');
      }

      // Return message with the number of newly inserted subscription IDs
//        return $newlyInsertedSubscriptionIds;
      return Redirect::route('users.index')->with('message', 'Successfully retrieved ' . count($newlyInsertedSubscriptionIds) . ' ' . (count($newlyInsertedSubscriptionIds) === 1 ? 'subscription' : 'subscriptions') . ' for ' . $user->name . '.');
    } catch (\Exception $e) {
      // Log the error for debugging
      Log::error($e);

      // Return an error message
//            return "An error occurred: " . $e->getMessage();
      return Redirect::route('users.index')->with('message', 'An error occurred: ' . $e->getMessage());
    }

  }
}
