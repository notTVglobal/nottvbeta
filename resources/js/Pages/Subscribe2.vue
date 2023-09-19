<template>

    <Head title="TEST SUBSCRIBE" />
<!--        <script src="https://js.stripe.com/v3/" defer></script>-->

    <div id="topDiv" class="h-[calc(100vh-16rem)] text-center bg-gray-800 text-white p-10">


        <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

<!--            <h1 class="text-3xl font-semibold pb-3">Become a notTV Premium Subscriber</h1>-->

            <div class="flex justify-center w-full">
                <div class="flex flex-col justify-center w-fit bg-gray-900 rounded-md py-10 mb-24">

                    <main>
                        <h1>Payment</h1>

                        <p>
                            Enable more payment method types
                            <a
                                href="https://dashboard.stripe.com/settings/payment_methods"
                                target="_blank"
                            >in your dashboard</a>.
                        </p>

                        <form
                            id="payment-form"
                            @submit.prevent="handleSubmit"
                        >
                            <div id="link-authentication-element" />
                            <div id="payment-element" />
                            <button
                                id="submit"
                                :disabled="isLoading"
                            >
                                Pay now
                            </button>
                            <sr-messages :messages="messages" />
                        </form>
                    </main>

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
import { useForm } from "@inertiajs/inertia-vue3";

import { loadStripe } from "@stripe/stripe-js";
import SrMessages from "./SrMessages.vue";

const isLoading = ref(false);
const messages = ref([]);

let stripe;
let elements;

userStore.showFlashMessage = true;

onMounted(async () => {
    const { publishableKey } = await fetch("/api/config").then((res) => res.json());
    stripe = await loadStripe('pk_test_51KJwK5Kahp38LUVYOjg7h425exCr6UZmMm1M24d31ZaS0HTsgPWIZE9Hd2F0KnREVHuPm2VHesX3J5SQfFFg7fTC00DMNpq1Lj');

    const { clientSecret, error: backendError } = await fetch("/api/create-payment-intent").then((res) => res.json());

    if (backendError) {
        messages.value.push(backendError.message);
    }
    messages.value.push(`Client secret returned.`);

    elements = stripe.elements({clientSecret});
    const paymentElement = elements.create('payment');
    paymentElement.mount("#payment-element");
    const linkAuthenticationElement = elements.create("linkAuthentication");
    linkAuthenticationElement.mount("#link-authentication-element");
    isLoading.value = false;
});

const handleSubmit = async () => {
    if (isLoading.value) {
        return;
    }

    isLoading.value = true;

    const { error } = await stripe.confirmPayment({
        elements,
        confirmParams: {
            return_url: `${window.location.origin}/return`
        }
    });

    if (error.type === "card_error" || error.type === "validation_error") {
        messages.value.push(error.message);
    } else {
        messages.value.push("An unexpected error occured.");
    }

    isLoading.value = false;
}

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
    intent: Object,
    card: ref(null)
})

let form = useForm({
    name: '',
});


</script>

<script>


</script>

<style scoped>
@import url('https://fonts.googleapis.com/css?family=Raleway&display=swap');

:root {
    --light-grey: #F6F9FC;
    --dark-terminal-color: #0A2540;
    --accent-color: #635BFF;
    --radius: 3px;
}

body {
    padding: 20px;
    font-family: 'Raleway';
    display: flex;
    justify-content: center;
    font-size: 1.2em;
    color: var(--dark-terminal-color);
}

main {
    width: 480px;
}

form > * {
    margin: 10px 0;
}

button {
    background-color: var(--accent-color);
}

button {
    background: var(--accent-color);
    border-radius: var(--radius);
    color: white;
    border: 0;
    padding: 12px 16px;
    margin-top: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    display: block;
}
button:hover {
    filter: contrast(115%);
}
button:active {
    transform: translateY(0px) scale(0.98);
    filter: brightness(0.9);
}
button:disabled {
    opacity: 0.5;
    cursor: none;
}

input, select {
    display: block;
    font-size: 1.1em;
    width: 100%;
    margin-bottom: 10px;
}

label {
    display: block;
}

a {
    color: var(--accent-color);
    font-weight: 900;
}

small {
    font-size: .6em;
}

fieldset, input, select {
    border: 1px solid #efefef;
}

#payment-form {
    border: #F6F9FC solid 1px;
    border-radius: var(--radius);
    padding: 20px;
    margin: 20px 0;
    box-shadow: 0 30px 50px -20px rgb(50 50 93 / 25%), 0 30px 60px -30px rgb(0 0 0 / 30%);
}

#messages {
    font-family: source-code-pro, Menlo, Monaco, Consolas, 'Courier New';
    background-color: #0A253C;
    color: #00D924;
    padding: 20px;
    margin: 20px 0;
    border-radius: var(--radius);
    font-size:0.7em;
}


</style>


