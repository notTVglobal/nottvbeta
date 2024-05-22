<template>

    <form @submit.prevent="submit" >
        <button type="submit"
                id="checkout-button"
                class="h-fit bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4"
                :disabled="form.processing">
            Checkout</button>
    </form>

</template>

<script setup>
import { onMounted } from "vue";
import { loadStripe } from "@stripe/stripe-js";
import { useForm } from "@inertiajs/vue3"
import { usePageSetup } from '@/Utilities/PageSetup'

usePageSetup('payment2')

// This is your test secret API key.
// const stripePromise = loadStripe('pk_test_51KJwK5Kahp38LUVYOjg7h425exCr6UZmMm1M24d31ZaS0HTsgPWIZE9Hd2F0KnREVHuPm2VHesX3J5SQfFFg7fTC00DMNpq1Lj');

const stripePromise = loadStripe(
    process.env.STRIPE_KEY
);

// const stripe = Stripe(process.env.STRIPE_SECRET_KEY);


const YOUR_DOMAIN = 'http://nottv.local:8000';

let form = useForm({

});

let submit = () => {
    axios.post('/upgrade', async (req, res) => {
        const session = await stripe.checkout.sessions.create({
            line_items: [
                {
                    // Provide the exact Price ID (for example, pr_1234) of the product you want to sell
                    price: 'plan_LyCOYZAqzVdFpz',
                    quantity: 1,
                },
            ],
            mode: 'subscription',
            success_url: `${YOUR_DOMAIN}/payment_success`,
            cancel_url: `${YOUR_DOMAIN}/payment_cancelled`,
        });
        res.redirect(303, session.url);
    });
};

</script>
