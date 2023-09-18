<template>

    <Head title="Upgrade Account" />

    <div class="w-full lace-self-center flex flex-col gap-y-3 bg-orange-400">
        <div id="topDiv" class="w-full bg-gray-800 text-gray-50 dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">
<!--            <Message v-if="showMyMessage" @close="showMessage = false" :message="props.message"/>-->

            <header class="flex justify-between pt-4">
                <h1 class="text-3xl font-semibold pb-3">Subscription</h1>
            </header>

                <div v-if="shopStore.upgradeSelection===''" class="w-full flex flex-col">
                        <div class="text-xl">Choose a subscription:</div>
                        <div class="mt-2 w-fit">
                            <button @click.prevent="shopStore.upgradeMonthly()">Premium Monthly - $25 / month</button>
                        </div>
                        <div class="mt-2 w-fit">
                            <button @click.prevent="shopStore.upgradeYearly()">Premium Yearly - $250 / year</button>
                        </div>
                        <div class="mt-2 w-fit hidden">
                            <button @click.prevent="shopStore.upgradeForever()">Premium Forever - $999 / one time</button>
                        </div>
                </div>

            <div class="flex flex-row mt-4 px-8">
                <div v-if="shopStore.upgradeSelection!==''" class="space-y-2 flex flex-col">
                    <div v-if="shopStore.upgradeSelection==='monthly'">
                        <label for="standard" class="font-semibold">Premium Monthly - $25 / month</label>
                    </div>
                    <div v-if="shopStore.upgradeSelection==='yearly'">
                        <label for="standard" class="font-semibold">Premium Yearly - $250 / year</label>
                    </div>
                    <div v-if="shopStore.upgradeSelection==='forever'">
                        <label for="standard" class="font-semibold">Premium Forever - $999 / one time</label>
                    </div>
                </div>
                <div v-if="shopStore.upgradeSelection!==''"
                     class="px-2 text-blue-500 hover:text-blue-400 font-semibold hover:underline hover:cursor-pointer"
                     @click="shopStore.changeUpgradeSelection()">(change)</div>
            </div>

            <div v-show="!shopStore.showPaymentForm" class="mx-auto mt-8 px-12">
                <h2 class="mt-6 mx-auto text-xl font-semibold text-white dark:text-gray-100">Payment form is loading...</h2>
            </div>

            <div class="w-full mx-auto mt-8">
                <h2 class="text-2xl font-semibold text-gray-100 dark:text-gray-100">Payment</h2>

                <form id="payment-form" class="w-full">
                    <div id="link-authentication-element" class="mb-2">
                        <!--Stripe.js injects the Link Authentication Element-->
                    </div>
                    <div id="payment-element" class="w-full">
                        <!--Stripe.js injects the Payment Element-->
                    </div>

                    <button id="submit"
                            class="h-fit bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4"
                            @click.prevent="submit()">
                        <div class="spinner hidden" id="spinner"></div>
                        <span id="button-text">Pay now</span>
                    </button>
                    <div id="payment-message" class="hidden"></div>
                </form>

                <!-- {/* Show any error or success messages */}-->
                <div v-if="error" id="payment-error" class="text-red-600 font-semibold w-full my-2"> {{error}}</div>
                <div v-if="message" id="payment-message" class="mt-1 text-white">{{message}}</div>
            </div>


        </div>
    </div>

</template>

<script setup>
import { onMounted, onUpdated } from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore";
import { useShopStore } from "@/Stores/ShopStore";
import { useForm } from "@inertiajs/inertia-vue3";
import { loadStripe } from '@stripe/stripe-js';
import Messages from "@/Components/VideoPlayer/Chat/VideoOTTChatMessages.vue";
import {Inertia} from "@inertiajs/inertia";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()
let shopStore = useShopStore()

videoPlayerStore.currentPage = 'subscribe'

let props = defineProps({
    user: Object,
    message: String,
    error: String,
    intent: Object,
    elements: {}
})

const options = {
    mode: 'setup',
    currency: 'cad',
};

let stripe
let elements
let emailAddress

let StripeAPIKey = ''
StripeAPIKey = process.env.MIX_STRIPE_KEY

onMounted(async() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()
    shopStore.customer = props.user

    stripe = await loadStripe(StripeAPIKey);

    initialize()

    showPaymentForm()

    document
        .querySelector("#payment-form")
        // .addEventListener("submit", handleSubmit);


});

function showPaymentForm() {
    shopStore.showPaymentForm = true;
}

function initialize() {

    const clientSecret = props.intent.client_secret
    const appearance = {
        theme: 'stripe',
        variables: {
            colorPrimary: '#0570de',
            colorBackground: '#ffffff',
            colorBackgroundText: '#ffffff',
            colorText: '#222222',
            colorDanger: '#df1b41',
            fontFamily: 'Ideal Sans, system-ui, sans-serif',
            spacingUnit: '4px',
        },
        rules: {
            '.Label': {
                color: 'var(--colorBackgroundText)',
            },
        }
    };

    elements = stripe.elements({appearance, clientSecret});

    // this is for linkPayments
    // const linkAuthenticationElement = elements.create("linkAuthentication");
    // linkAuthenticationElement.mount("#link-authentication-element");
    //
    // linkAuthenticationElement.on('change', (event) => {
    //     emailAddress = event.value.email;
    // });

    const paymentElementOptions = {
        layout: "tabs",
    };

    const paymentElement = elements.create("payment", paymentElementOptions);
    paymentElement.mount("#payment-element");

}

async function submit() {
    if (shopStore.upgradeStripeId === '' || null) {
        alert('Please select a subscription.')
        return
    }
    setLoading(true);

    const {setupIntent, error} = await stripe.confirmSetup({
        elements,
        confirmParams: {
            // Make sure to change this to your payment completion page
            return_url: "https://not.tv/subscription_success",
            // receipt_email: emailAddress,
        },
        redirect: 'if_required'


    });

    // This point will only be reached if there is an immediate error when
    // confirming the payment. Otherwise, your customer will be redirected to
    // your `return_url`. For some payment methods like iDEAL, your customer will
    // be redirected to an intermediate site first to authorize the payment, then
    // redirected to the `return_url`.

    if (error) {
        if (error.type === "card_error" || error.type === "validation_error") {
            showMessage(error.message);
        } else {
            showMessage("An unexpected error occurred.");
            setLoading(false);
        }
    } else {
        // console.log(setupIntent)
        let form = useForm({
            paymentMethod: setupIntent.payment_method,
            plan: shopStore.upgradeStripeId,
        });

        //Submit the form
        form.post('/shop/subscribe')

    }
}


// ------- UI helpers -------

function showMessage(messageText) {
    const messageContainer = document.querySelector("#payment-message");

    messageContainer.classList.remove("hidden");
    messageContainer.textContent = messageText;

    setTimeout(function () {
        messageContainer.classList.add("hidden");
        messageContainer.textContent = "";
    }, 4000);
}

// Show a spinner on payment submission
function setLoading(isLoading) {
    if (isLoading) {
        // Disable the button and show a spinner
        document.querySelector("#submit").disabled = true;
        document.querySelector("#spinner").classList.remove("hidden");
        document.querySelector("#button-text").classList.add("hidden");
    } else {
        document.querySelector("#submit").disabled = false;
        document.querySelector("#spinner").classList.add("hidden");
        document.querySelector("#button-text").classList.remove("hidden");
    }
}

</script>

<style scoped>

/* Variables */
* {
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, sans-serif;
    font-size: 16px;
    -webkit-font-smoothing: antialiased;
    display: flex;
    justify-content: center;
    align-content: center;
    height: 100vh;
    width: 100vw;
}

form {
    width: 30vw;
    min-width: 500px;
    align-self: center;
    box-shadow: 0px 0px 0px 0.5px rgba(50, 50, 93, 0.1),
    0px 2px 5px 0px rgba(50, 50, 93, 0.1), 0px 1px 1.5px 0px rgba(0, 0, 0, 0.07);
    border-radius: 7px;
    padding: 40px;
}

.hidden {
    display: none;
}

#payment-message {
    color: rgb(105, 115, 134);
    font-size: 16px;
    line-height: 20px;
    padding-top: 12px;
    text-align: center;
}

#payment-element {
    margin-bottom: 24px;
}

/* Buttons and links */
button {
    background: #5469d4;
    font-family: Arial, sans-serif;
    color: #ffffff;
    border-radius: 4px;
    border: 0;
    padding: 12px 16px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    display: block;
    transition: all 0.2s ease;
    box-shadow: 0px 4px 5.5px 0px rgba(0, 0, 0, 0.07);
    width: 100%;
}
button:hover {
    filter: contrast(115%);
}
button:disabled {
    opacity: 0.5;
    cursor: default;
}

/* spinner/processing state, errors */
.spinner,
.spinner:before,
.spinner:after {
    border-radius: 50%;
}
.spinner {
    color: #ffffff;
    font-size: 22px;
    text-indent: -99999px;
    margin: 0px auto;
    position: relative;
    width: 20px;
    height: 20px;
    box-shadow: inset 0 0 0 2px;
    -webkit-transform: translateZ(0);
    -ms-transform: translateZ(0);
    transform: translateZ(0);
}
.spinner:before,
.spinner:after {
    position: absolute;
    content: "";
}
.spinner:before {
    width: 10.4px;
    height: 20.4px;
    background: #5469d4;
    border-radius: 20.4px 0 0 20.4px;
    top: -0.2px;
    left: -0.2px;
    -webkit-transform-origin: 10.4px 10.2px;
    transform-origin: 10.4px 10.2px;
    -webkit-animation: loading 2s infinite ease 1.5s;
    animation: loading 2s infinite ease 1.5s;
}
.spinner:after {
    width: 10.4px;
    height: 10.2px;
    background: #5469d4;
    border-radius: 0 10.2px 10.2px 0;
    top: -0.1px;
    left: 10.2px;
    -webkit-transform-origin: 0px 10.2px;
    transform-origin: 0px 10.2px;
    -webkit-animation: loading 2s infinite ease;
    animation: loading 2s infinite ease;
}

@-webkit-keyframes loading {
    0% {
        -webkit-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}
@keyframes loading {
    0% {
        -webkit-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}

@media only screen and (max-width: 600px) {
    form {
        width: 80vw;
        min-width: initial;
    }
}

</style>





