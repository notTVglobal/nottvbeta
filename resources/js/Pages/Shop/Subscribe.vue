<template>

    <Head title="Upgrade Account" />

    <div class="w-full lace-self-center flex flex-col gap-y-3">
        <div id="topDiv" class="w-full bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">
            <Message v-if="showMyMessage" @close="showMessage = false" :message="props.message"/>

            <header class="flex justify-between pt-4">
                <h1 class="text-3xl font-semibold pb-3">Subscription</h1>
            </header>


                <div v-if="shopStore.upgradeSelection===''" class="flex flex-col">
                    <div class="mt-2">
                        <input type="radio" name="plan" id="standard"
                           value="plan_LyCOYZAqzVdFpz"
                           @click="shopStore.upgradeMonthly()"
                           class="px-2" />
                        <label for="standard">Premium Monthly - $25 / month</label>
                    </div>
                    <div class="mt-2">
                        <input type="radio" name="plan" id="standard"
                               value="price_1NhgZTKahp38LUVY8n9Skgwf"
                               @click="shopStore.upgradeYearly()"
                               class="px-2" />
                        <label for="standard">Premium Yearly - $250 / year</label>
                    </div>
                    <div class="mt-2">
                        <input type="radio" name="plan" id="standard"
                               value="price_1NhgZyKahp38LUVY1MOhE5L5"
                               @click="shopStore.upgradeForever()"
                               class="px-2" />
                        <label for="standard">Premium Forever - $999 / one time</label>
                    </div>
                </div>

            <div class="flex flex-row mt-4 px-8">
                <div v-if="shopStore.upgradeSelection!==''" class="space-y-2 flex flex-col">
                    <div v-if="shopStore.upgradeSelection==='monthly'">
                        <label for="standard">Premium Monthly - $25 / month</label>
                    </div>
                    <div v-if="shopStore.upgradeSelection==='yearly'">
                        <label for="standard">Premium Yearly - $250 / year</label>
                    </div>
                    <div v-if="shopStore.upgradeSelection==='forever'">
                        <label for="standard">Premium Forever - $999 / one time</label>
                    </div>
                </div>
                <div v-if="shopStore.upgradeSelection!==''"
                     class="px-2 text-blue-500 hover:text-blue-400 hover:underline hover:cursor-pointer"
                     @click="shopStore.changeUpgradeSelection()">(change)</div>
            </div>

            <div class="lg:w-2/3 mx-auto mt-8 px-12">
                <h2 class="text-2xl font-semibold text-black dark:text-gray-100">Payment</h2>
                <div class="relative">
                    <label for="name" class="leading-7 text-sm text-black dark:text-gray-200">Cardholder Name</label>
                    <input
                        type="text"
                        id="card-holder-name"
                        name="name"
                        v-model="shopStore.customer.name"
                        class="w-full bg-gray-100 rounded border border-gray-300 focus:border-indigo-500 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"
                        :disabled="paymentProcessing"
                    >
                </div>
                <!-- Stripe Elements Placeholder -->
                <div class="mt-4">
                    <label for="card" class="leading-7 text-sm text-black dark:text-gray-200">Credit Card</label>
                    <div id="card-element"></div>
                </div>
                <div class="mt-4">
                    <button id="checkout-button"
                            type="submit"
                            class="h-fit bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4"
                            :disabled="form.processing">
                        <span v-if="isLoading" class="spinner" id="spinner" />
                        <span v-if="!isLoading" id="button-text">
                      Pay now
                    </span>
                    </button>
                </div>
                <!-- {/* Show any error or success messages */}-->
                <div v-if="message" id="payment-message" class="mt-4">{{message}}</div>
            </div>


        </div>
    </div>

</template>

<script setup>
import { onMounted, ref } from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore";
import { useShopStore } from "@/Stores/ShopStore";
import Message from "@/Components/Modals/Messages";
import {useForm} from "@inertiajs/inertia-vue3";
import {loadStripe} from '@stripe/stripe-js';

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()
let shopStore = useShopStore()

videoPlayerStore.currentPage = 'subscribe'

let props = defineProps({
    user: Object,
    message: String,
    intent: Object,
})

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()
    shopStore.customer = props.user
});

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

<script>
import { loadStripe } from '@stripe/stripe-js';
import { useShopStore } from '@/Stores/ShopStore'
import { Inertia } from "@inertiajs/inertia";
export default {
    setup() {

    },
    props: {
        user: Object,
    },
    data() {
        const shopStore = useShopStore()
        shopStore.customer = this.user
        return {
            stripe: {},
            cardElement: {},
            customer: shopStore.customer,
            cart: shopStore.cart,
            order: [],
            paymentProcessing: false,
            country: shopStore.customer.country,
            region: shopStore.customer.province
        }
    },
    async mounted() {
        this.stripe = await loadStripe(process.env.MIX_STRIPE_KEY);

        const elements = this.stripe.elements();
        this.cardElement = elements.create('card', {
            classes: {
                base: 'bg-gray-100 py-3 px-2 rounded border border-gray-300 focus:border-indigo-500 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out'
            }
        });

        this.cardElement.mount('#card-element');
    },
    methods: {
        updateOrder(order) {
            this.order = order;
        },
        updateCart(cart) {
            this.cart = cart;
        },
        clearCart() {
            this.updateCart = [];
        },
        async processPayment() {
            // send the payment information to Laravel + Stripe
            this.paymentProcessing = true;

            const {paymentMethod, error} = await this.stripe.createPaymentMethod({
                    type: 'card',
                    card: this.cardElement,
                    billing_details: {
                        name: this.customer.name,
                        email: this.customer.email,
                        phone: this.customer.phone,
                        address: {
                            line1: this.customer.address1,
                            line2: this.customer.address2,
                            city: this.customer.city,
                            state: this.customer.province,
                            postal_code: this.customer.postalCode,
                            country: this.customer.country,
                        }
                    }
                }
            );

            if (error) {
                this.paymentProcessing = false;
                alert(error);
            } else {
                this.customer.payment_method_id = paymentMethod.id;
                this.customer.amount = this.cart.reduce((acc, item) => acc + (item.price * item.quantity), 0);
                this.customer.cart = JSON.stringify(this.cart);
                console.log('you arrived here.');

                axios.post('/api/purchase', this.customer)
                    .then((response) => {
                        this.paymentProcessing = false;

                        // this.updateOrder(response.data);
                        this.order = this.cart;
                        this.cart = [];
                        console.log('you arrived at the second here.');
                        Inertia.post('/shop/summary', {
                            order: response.data
                        })
                    })
                    .catch((error) => {
                        this.paymentProcessing = false;
                        alert(error);
                    });
            }
        }
    },
    computed: {
        // ...mapState(useShopStore, ['cart']),
        // ...mapState(useShopStore, ['paymentProcessing']),
        // ...mapState(useShopStore, ['order']),
    },
}

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


