<template>

    <Head title="Upgrade Account" />

    <div class="place-self-center flex flex-col gap-y-3">
        <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">
            <Message v-if="showMyMessage" @close="showMessage = false" :message="props.message"/>

            <header class="flex justify-between mb-3">
                <div class="pt-4">
                    <h1 class="text-3xl font-semibold pb-3">Payment</h1>
                </div>
            </header>

            <div class="grid grid-cols-1 justify-center xl:space-x-4 space-y-8 xl:space-y-0 px-8 mx-auto">
                <div class="px-12 py-10">
                    <form id="payment-form" @submit.prevent="submit" data-secret="{{}}">

                        <div class="mt-4">
                            <input type="radio" name="plan" id="standard"
                                   value="plan_LyCOYZAqzVdFpz" checked />
                            <label for="standard">Standard - $10 / month</label>
                        </div>

                        <button id="submit" type="submit">
                            <span v-if="isLoading" class="spinner" id="spinner" />
                            <span v-if="!isLoading" id="button-text">
                              Pay now
                            </span>
                        </button>

                        <button type="submit"
                                id="checkout-button"
                                class="h-fit bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4"
                                :disabled="form.processing">
                            Checkout</button>
                        <!-- {/* Show any error or success messages */}-->
                        <div v-if="message" id="payment-message">{{message}}</div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore";
import Message from "@/Components/Modals/Messages";
import {useForm} from "@inertiajs/inertia-vue3";
import {loadStripe} from '@stripe/stripe-js';

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'subscribe'

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()
});


let props = defineProps({
    message: String,
    intent: Object,
})

let form = useForm({
    name: '',
});

let isLoading = ref(false)
let showMessage = ref(false)


let showMyMessage = ref(true);

// Stripe.js
////////////

// // Initialize Stripe
// let elements = stripe.elements();

const stripe = loadStripe('pk_test_51KJwK5Kahp38LUVYOjg7h425exCr6UZmMm1M24d31ZaS0HTsgPWIZE9Hd2F0KnREVHuPm2VHesX3J5SQfFFg7fTC00DMNpq1Lj');
// const elements = Stripe.elements()

// const appearance = { /* appearance */};
// const options = {
//     business: "notTV"
// };
// const clientSecret = {{CLIENT_SECRET}};
// const elements = stripe.elements(appearance, clientSecret);
// const paymentElement = elements.create('payment', options);
// paymentElement.mount('#payment-element');

// const paymentIntent = await stripe.paymentIntents.create({
//     amount: 1099,
//     currency: 'cad',
//     automatic_payment_methods: {
//         enabled: true,
//     },
// })


// const stripe = Stripe('pk_test_51KJwK5Kahp38LUVYOjg7h425exCr6UZmMm1M24d31ZaS0HTsgPWIZE9Hd2F0KnREVHuPm2VHesX3J5SQfFFg7fTC00DMNpq1Lj');
// const options = {
//     // passing the client secret obtained from the server
//     clientSecret: props.intent.client_secret,
// }

// let elements;

// initialize();

// let elements = stripe.elements({
//     clientSecret: 'CLIENT_SECRET',
//     // mode is "payment" for the 'forever' product
//     mode: 'subscription',
//     currency: 'usd',
//     amount: 1099,
// });

// // Initialize Stripe
// let elements = stripe.elements();
//
// // Handle form submission
//
// let form = document.getElementById('payment-form');
// form.addEventListener('submit', function(event) {
//     event.preventDefault();
//
//     stripe.createToken(card).then(function(result) {
//         if(result.error) {
//             // Inform the user if there was an error.
//             let errorElement = document.getElementById('card-errors');
//             errorElement.textContent = result.error.message;
//         } else {
//             // Send the token to your server.
//             stripeTokenHandler(result.token);
//         }
//     });
// });
//
// function stripeTokenHandler(token) {
//     let hiddenInput = document.createElement('input');
//     hiddenInput.setAttribute('type', 'hidden');
//     hiddenInput.setAttribute('name', 'stripeToken');
//     hiddenInput.setAttribute('value', 'token.id');
//     form.appendChild(hiddenInput);
//
//     form.submit();
//
// }
//
let submit = () => {
    // form.post(route('subscribe.post'));
    // axios.post('/upgrade', async (req, res) => {
    //     const session = await stripe.checkout.sessions.create({
    //         line_items: [
    //             {
    //                 // Provide the exact Price ID (for example, pr_1234) of the product you want to sell
    //                 price: 'plan_LyCOYZAqzVdFpz',
    //                 quantity: 1,
    //             },
    //         ],
    //         mode: 'subscription',
    //         success_url: `${YOUR_DOMAIN}/payment_success`,
    //         cancel_url: `${YOUR_DOMAIN}/payment_cancelled`,
    //     });
    //     res.redirect(303, session.url);
    // });
};

// let submit = null

// // Fetches a payment intent and captures the client secret
// async function initialize() {
//
//     // const stripeScript = await document.createElement("script");
//     // stripeScript.setAttribute(
//     //     "src",
//     //     "https://js.stripe.com/v3/s"
//     // );
//     // document.head.appendChild(stripeScript);
//
//     elements = stripe.elements({
//         clientSecret: props.intent.client_secret,
//     });
//
//
//     const paymentElement = elements.create("payment");
//     paymentElement.mount("#payment-element");
//
//     const appearance = {
//         theme: 'stripe',
//     };
//
//
//     const linkAuthenticationElement = elements.create("linkAuthentication");
//     linkAuthenticationElement.mount("#link-authentication-element");
//
//     linkAuthenticationElement.on('change', (event) => {
//
//     });
//
//     const paymentElementOptions = {
//         layout: "tabs",
//     };
//
// }


</script>

<!--<style scoped>-->
<!--/* Variables */-->
<!--* {-->
<!--    box-sizing: border-box;-->
<!--}-->

<!--body {-->
<!--    font-family: -apple-system, BlinkMacSystemFont, sans-serif;-->
<!--    font-size: 16px;-->
<!--    -webkit-font-smoothing: antialiased;-->
<!--    display: flex;-->
<!--    justify-content: center;-->
<!--    align-content: center;-->
<!--    height: 100vh;-->
<!--    width: 100vw;-->
<!--}-->

<!--form {-->
<!--    width: 30vw;-->
<!--    min-width: 500px;-->
<!--    align-self: center;-->
<!--    box-shadow: 0px 0px 0px 0.5px rgba(50, 50, 93, 0.1),-->
<!--    0px 2px 5px 0px rgba(50, 50, 93, 0.1), 0px 1px 1.5px 0px rgba(0, 0, 0, 0.07);-->
<!--    border-radius: 7px;-->
<!--    padding: 40px;-->
<!--}-->

<!--.hidden {-->
<!--    display: none;-->
<!--}-->

<!--#payment-message {-->
<!--    color: rgb(105, 115, 134);-->
<!--    font-size: 16px;-->
<!--    line-height: 20px;-->
<!--    padding-top: 12px;-->
<!--    text-align: center;-->
<!--}-->

<!--#payment-element {-->
<!--    margin-bottom: 24px;-->
<!--}-->

<!--/* Buttons and links */-->
<!--button {-->
<!--    background: #5469d4;-->
<!--    font-family: Arial, sans-serif;-->
<!--    color: #ffffff;-->
<!--    border-radius: 4px;-->
<!--    border: 0;-->
<!--    padding: 12px 16px;-->
<!--    font-size: 16px;-->
<!--    font-weight: 600;-->
<!--    cursor: pointer;-->
<!--    display: block;-->
<!--    transition: all 0.2s ease;-->
<!--    box-shadow: 0px 4px 5.5px 0px rgba(0, 0, 0, 0.07);-->
<!--    width: 100%;-->
<!--}-->
<!--button:hover {-->
<!--    filter: contrast(115%);-->
<!--}-->
<!--button:disabled {-->
<!--    opacity: 0.5;-->
<!--    cursor: default;-->
<!--}-->

<!--/* spinner/processing state, errors */-->
<!--.spinner,-->
<!--.spinner:before,-->
<!--.spinner:after {-->
<!--    border-radius: 50%;-->
<!--}-->
<!--.spinner {-->
<!--    color: #ffffff;-->
<!--    font-size: 22px;-->
<!--    text-indent: -99999px;-->
<!--    margin: 0px auto;-->
<!--    position: relative;-->
<!--    width: 20px;-->
<!--    height: 20px;-->
<!--    box-shadow: inset 0 0 0 2px;-->
<!--    -webkit-transform: translateZ(0);-->
<!--    -ms-transform: translateZ(0);-->
<!--    transform: translateZ(0);-->
<!--}-->
<!--.spinner:before,-->
<!--.spinner:after {-->
<!--    position: absolute;-->
<!--    content: "";-->
<!--}-->
<!--.spinner:before {-->
<!--    width: 10.4px;-->
<!--    height: 20.4px;-->
<!--    background: #5469d4;-->
<!--    border-radius: 20.4px 0 0 20.4px;-->
<!--    top: -0.2px;-->
<!--    left: -0.2px;-->
<!--    -webkit-transform-origin: 10.4px 10.2px;-->
<!--    transform-origin: 10.4px 10.2px;-->
<!--    -webkit-animation: loading 2s infinite ease 1.5s;-->
<!--    animation: loading 2s infinite ease 1.5s;-->
<!--}-->
<!--.spinner:after {-->
<!--    width: 10.4px;-->
<!--    height: 10.2px;-->
<!--    background: #5469d4;-->
<!--    border-radius: 0 10.2px 10.2px 0;-->
<!--    top: -0.1px;-->
<!--    left: 10.2px;-->
<!--    -webkit-transform-origin: 0px 10.2px;-->
<!--    transform-origin: 0px 10.2px;-->
<!--    -webkit-animation: loading 2s infinite ease;-->
<!--    animation: loading 2s infinite ease;-->
<!--}-->

<!--@-webkit-keyframes loading {-->
<!--    0% {-->
<!--        -webkit-transform: rotate(0deg);-->
<!--        transform: rotate(0deg);-->
<!--    }-->
<!--    100% {-->
<!--        -webkit-transform: rotate(360deg);-->
<!--        transform: rotate(360deg);-->
<!--    }-->
<!--}-->
<!--@keyframes loading {-->
<!--    0% {-->
<!--        -webkit-transform: rotate(0deg);-->
<!--        transform: rotate(0deg);-->
<!--    }-->
<!--    100% {-->
<!--        -webkit-transform: rotate(360deg);-->
<!--        transform: rotate(360deg);-->
<!--    }-->
<!--}-->

<!--@media only screen and (max-width: 600px) {-->
<!--    form {-->
<!--        width: 80vw;-->
<!--        min-width: initial;-->
<!--    }-->
<!--}-->

<!--</style>-->


