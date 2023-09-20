<template>
    <form id="payment-form">
        <div id="payment-element">
            <!-- Stripe will create form elements here -->
        </div>
        <button type="submit" @click="handleSubmit">Pay via Stripe</button>
    </form>
</template>

<script setup>
import { ref, onMounted } from "vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore";
import { loadStripe } from "@stripe/stripe-js";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

userStore.currentPage = 'payment'


onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()


});

let stripe = loadStripe('pk_test_51KJwK5Kahp38LUVYOjg7h425exCr6UZmMm1M24d31ZaS0HTsgPWIZE9Hd2F0KnREVHuPm2VHesX3J5SQfFFg7fTC00DMNpq1Lj');

const token = ref(null)
const elements = ref(null)

onMounted(() => {
    axios.post('INITIATE_PAYMENT_API', {
        amount: 150,
        currency: 'USD'
    }).then(response => {
        token.value = response.token // Use to identify the payment
        const options = {
            clientSecret: response.clientSecret,
        }

        elements.value = stripe.elements(options);
        const paymentElement = elements.value.create('payment');
        paymentElement.mount('#payment-element');
    }).catch(error => {
        // throw error
    })
})

const handleSubmit = async (e) => {
    e.preventDefault();

    const { error } = await stripe.value.confirmPayment({
        elements: elements.value,
        redirect: "if_required"
    });

    if (error === undefined) {
        axios.post("PAYMENT_SUCCESS_API", {
            token: token.value,
        })
    } else {
        axios.post("PAYMENT_FAILURE_API", {
            token: token.value,
            code: error.code,
            description: error.message,
        })
    }
}
</script>
