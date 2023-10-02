<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Stripe\StripeClient;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stripe\Stripe as StripeGateway;
use Throwable;

class StripeController extends Controller
{

    public function purchase(Request $request)
    {
//        dd($request);
        // make a purchase

        // get the user
        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        // validate the request
        $attributes = \Illuminate\Support\Facades\Request::validate([
            'name' => ['required', 'string', 'max:255'],
//            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'address1' => ['nullable', 'string', 'max:255'],
            'address2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'postalCode' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
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
                    'total' => $payment->charges->data[0]->amount
                ]);

            foreach (json_decode($request->input('cart'), true) as $item) {
                $order->products()
                    ->attach($item['id'], ['quantity' => $item['quantity']]);
            }

            $order->load('products');

            return $order;
        } catch(\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function subscribe() {
        if (auth()->user()->subscribed('default')) {
            return redirect('/stream');
        }
            return Inertia::render('Shop/Subscribe', [
                // TODO: list customer's payment methods:
                'payment_method' => auth()->user()->defaultPaymentMethod(),
                'intent' => auth()->user()->createSetupIntent(),
            ]);
    }

    public function createPaymentIntent() {

    }

    public function setupNewSubscription(Request $request) {
        try {
            auth()->user()->newSubscription('default', $request->plan)->create($request->paymentMethod);
        } catch(\Exception $e) {
            return Inertia::render('Shop/Subscribe', [
                'error' => $e->getMessage(),
            ]);
        }
        return to_route('subscriptionSuccess');
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

    public function getBillingPortalAccessUrl(Request $request) {
        return $request->user()->billingPortalUrl(route('stream'));
//        return $request->user()->redirectToBillingPortal(route('stream'));
    }

    public function createCheckoutSession(Request $request)
    {

    }

    public function initiateSetup(Request $request) {
        $intent = auth()->user()->createSetupIntent();
    }

    public function initiatePayment(Request $request)
    {
        StripeGateway::setApiKey('STRIPE_SECRET_KEY');

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount * 100, // Multiply as & when required
                'currency' => $request->currency,
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            // Save the $paymentIntent->id to identify this payment later
        } catch (Exception $e) {
            // throw error
        }

        return [
            'token' => (string) Str::uuid(),
            'client_secret' => $paymentIntent->client_secret
        ];
    }

    public function completePayment(Request $request)
    {
        $stripe = new StripeClient('sk_test_51KJwK5Kahp38LUVYhyBLIgpjehztUl8kdRcBK4FWXCU7sXsZ2NUOM11ktvIacKUaCbrixtcy1f3HKiZ27umNlLVb00VviyQEzc');

        // Use the payment intent ID stored when initiating payment
        $paymentDetail = $stripe->paymentIntents->retrieve('PAYMENT_INTENT_ID');

        if ($paymentDetail->status != 'succeeded') {
            // throw error
        }

        // Complete the payment
    }

    public function failPayment(Request $request)
    {
        // Log the failed payment if you wish
    }

    public function getUserSubscriptionsFromStripe(Request $request) {
        dd($request->data);
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

        // 2. Get all subscriptions from Stripe
        $stripeSecret = env('STRIPE_SECRET');
        $stripe = new \Stripe\StripeClient($stripeSecret);
        $subscriptions = $stripe->subscriptions->all();

        // 3. Loop through results where subscription belongs to customer

        // 4. Create the subscriptions on the subscriptions table if they don't exist (look for existing subscription by $subStripeId)

        // 5. Create the subscription_item on the subscription_items table

        // 6. Update the user (if we have the pm_type, pm_last_four, trial_ends_at information)




    }
}
