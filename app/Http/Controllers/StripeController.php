<?php

namespace App\Http\Controllers;

use Stripe\StripeClient;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stripe\Stripe as StripeGateway;

class StripeController extends Controller
{
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
}
