<template>

    <Head title="Upgrade Account" >
<!--        <script src="https://js.stripe.com/v3/" defer></script>-->
    </Head>

    <div id="topDiv"></div>
    <div class="h-[calc(100vh-16rem)] text-center bg-gray-800 text-white p-10">


            <Message v-if="showMyMessage" @close="showMessage = false" :message="props.message"/>

<!--            <h1 class="text-3xl font-semibold pb-3">Become a notTV Premium Subscriber</h1>-->

            <div class="flex justify-center w-full">
                <div class="flex flex-col justify-center w-fit bg-gray-900 rounded-md py-10 mb-24">

                    <h2 class="text-3xl py-8 px-2 font-bold">Payment</h2>

                    <div class="grid grid-cols-1 justify-center xl:space-x-4 space-y-8 xl:space-y-0 px-8 mx-auto">

                            <div class="px-12 py-6">
                                <div class="mb-2">

                                </div>
                                <div class="py-10">

                                    <form id="payment-form" @submit.prevent="submit">

                                        <div class="mt-4">
                                            <input type="radio" name="plan" id="standard"
                                                   value="plan_LyCOYZAqzVdFpz" checked>
                                            <label for="standard">Standard - $10 / month</label>
                                        </div>

                                        <div
                                            id="link-authentication-element"/>
<!--                                            onChange={(e) => setEmail(e.target.value)}-->


                                        <div id="payment-element" data-options=paymentElementOptions>

<Elements stripe="{{stripePromise}}" options="{{options}}">
<!--    <CheckoutForm />-->
</Elements>
                                            form injected here


                                        </div>

<!--                                        <button :disabled="isLoading || !stripe || !elements" id="submit">-->
                                        <button id="submit" type="submit">
                                            <span v-if="isLoading" class="spinner" id="spinner" />
                                        <span v-if="!isLoading" id="button-text">
                                          Pay now
                                        </span>
                                        </button>
<!--                                        {/* Show any error or success messages */}-->
                                        <div v-if="message" id="payment-message">{{message}}</div>
                                    </form>

                                </div>

                            </div>
                    </div>



            </div>
            </div>


    </div>

</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore";
import Message from "@/Components/Modals/Messages";
import {Inertia} from "@inertiajs/inertia";
import {useForm} from "@inertiajs/inertia-vue3";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'subscribe'


onBeforeMount(() => {
    userStore.scrollToTopCounter = 0;

})

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.scrollToTopCounter === 0 ) {
        document.getElementById("topDiv").scrollIntoView()
        userStore.scrollToTopCounter ++;
    }

});


let props = defineProps({
    message: String,
    intent: Object,
})

let form = useForm({
    name: '',
});


let showMyMessage = ref(true);

// Stripe.js
////////////

// // Initialize Stripe
// let elements = stripe.elements();


const stripe = Stripe('pk_test_51KJwK5Kahp38LUVYOjg7h425exCr6UZmMm1M24d31ZaS0HTsgPWIZE9Hd2F0KnREVHuPm2VHesX3J5SQfFFg7fTC00DMNpq1Lj');
const options = {
    // passing the client secret obtained from the server
    clientSecret: props.intent.client_secret,
}

let elements;

initialize();

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
    form.post(route('subscribe.post'));
};


// // Fetches a payment intent and captures the client secret
async function initialize() {

    // const stripeScript = await document.createElement("script");
    // stripeScript.setAttribute(
    //     "src",
    //     "https://js.stripe.com/v3/s"
    // );
    // document.head.appendChild(stripeScript);

    elements = stripe.elements({
        clientSecret: props.intent.client_secret,
    });


    const paymentElement = elements.create("payment");
    paymentElement.mount("#payment-element");

    const appearance = {
        theme: 'stripe',
    };


    const linkAuthenticationElement = elements.create("linkAuthentication");
    linkAuthenticationElement.mount("#link-authentication-element");

    linkAuthenticationElement.on('change', (event) => {

    });

    const paymentElementOptions = {
        layout: "tabs",
    };

}


</script>

<script>


export default {

    setup() {

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


