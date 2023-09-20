<template>

    <Head title="Upgrade Account" />
    <div id="topDiv" class="h-[calc(100vh-16rem)] text-center bg-gray-800 text-white p-10">

        <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

        <!--            <h1 class="text-3xl font-semibold pb-3">Become a notTV Premium Subscriber</h1>-->

            <div class="flex justify-center w-full">
                <div class="flex flex-col justify-center w-fit bg-gray-900 rounded-md py-10 pb-96">

                    <h2 class="text-3xl py-8 px-2 font-bold">Choose a Subscription</h2>

                    <div class="flex flex-wrap justify-center space-y-3 space-x-3 px-8 mx-auto">

                            <div class="card monthly bg-gray-700 hover:bg-gray-600 hover:cursor-pointer rounded-lg px-12 pt-6"
                                 @mouseover="hoverMonthlyFull = true"
                                 @mouseleave="hoverMonthlyFull = false"
                                 @click="payNow('monthly')">
                                <div class="flex justify-between mb-2">
                                    <div class="productName">Monthly</div>
                                    <div class="price">$25</div>
                                </div>
                                <div class="py-6">
                                    <font-awesome-icon icon="fa-star" class="upgradeIcon"/>
                                </div>
                                <div>
                                    Get full access to all features, shows, channels and movies!
                                </div>
                                <div class="flex no-wrap justify-center bg-gray-900 hover:bg-gray-800 hover:cursor-pointer rounded-lg w-full mt-6 px-4 py-4 mb-4 mx-auto"
                                     @mouseover="hoverMonthly = true"
                                     @mouseleave="hoverMonthly = false">
                                    <span class="bg-gray-700 mr-4 py-1 px-2 w-fit rounded-full"
                                        :class="{active: hoverMonthly}"><font-awesome-icon icon="fa-check" /></span> <span> Select Plan</span></div>

                            </div>

                        <div class="card annually bg-gray-700 hover:bg-gray-600 hover:cursor-pointer rounded-lg px-12 pt-6"
                             @mouseover="hoverYearlyFull = true"
                             @mouseleave="hoverYearlyFull = false"
                             @click="payNow('yearly')">
                            <div class="flex justify-between mb-2">
                                <div class="productName">Yearly</div>
                                <div class="price">$250</div>
                            </div>
                            <div class="py-6">
                                <font-awesome-icon icon="fa-star" class="upgradeIcon"/><font-awesome-icon icon="fa-star" class="upgradeIcon"/>
                            </div>
                            <div>
                                Get full access to all features, shows, channels and movies!
                            </div>
                            <div class="flex no-wrap justify-center bg-gray-900 hover:bg-gray-800 hover:cursor-pointer rounded-lg w-full mt-6 px-4 py-4 mb-4 mx-auto"
                                 @mouseover="hoverYearly = true"
                                 @mouseleave="hoverYearly = false">

                                <span class="bg-gray-700 mr-4 py-1 px-2 w-fit rounded-full"
                                     :class="{active: hoverYearly}"><font-awesome-icon icon="fa-check" /></span> <span class=""> Select Plan</span></div>

                        </div>

                        <div class="hidden card forever bg-gray-700 hover:bg-gray-600 hover:cursor-pointer rounded-lg px-12 py-6"
                             @mouseover="hoverForeverFull = true"
                             @mouseleave="hoverForeverFull = false"
                             @click="payNow('forever')">
                            <div class="flex justify-between mb-2">
                                <div class="productName">Forever</div>
                                <div class="price">$999</div>
                            </div>
                            <div class="py-10">
                                <font-awesome-icon icon="fa-star" class="upgradeIcon" :class="{activeIcon: hoverForeverFull}"/><font-awesome-icon icon="fa-star" class="upgradeIcon" :class="{activeIcon: hoverForeverFull}"/><font-awesome-icon icon="fa-star" class="upgradeIcon" :class="{activeIcon: hoverForeverFull}"/>
                            </div>
                            <div>
                                Get full access to all features, shows, channels and movies!
                            </div>
                            <div class="flex no-wrap justify-center bg-gray-900 hover:bg-gray-800 hover:cursor-pointer rounded-lg w-full mt-6 px-4 py-4 mb-4 mx-auto"
                                 @mouseover="hoverForever = true"
                                 @mouseleave="hoverForever = false">
                                <span class="bg-gray-700 mr-4 py-1 px-2 w-fit rounded-full"
                                     :class="{active: hoverForever}"><font-awesome-icon icon="fa-check" /></span> <span> Select Plan</span></div>

                        </div>
                    </div>


<!--                    <div class="mx-auto mt-8 px-12">-->
<!--                        <h2 class="text-2xl font-semibold text-black dark:text-gray-100">Payment</h2>-->
<!--                        <div class="relative">-->
<!--                            <label for="name" class="leading-7 text-sm text-black dark:text-gray-200">Cardholder Name</label>-->
<!--                            <input-->
<!--                                type="text"-->
<!--                                id="card-holder-name"-->
<!--                                name="name"-->
<!--                                v-model="shopStore.customer.name"-->
<!--                                class="w-full bg-gray-100 rounded border border-gray-300 focus:border-indigo-500 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"-->
<!--                                :disabled="paymentProcessing"-->
<!--                            >-->
<!--                            <input hidden id="subscriptionSelectedPrice" v-text="shopStore.selectedSubscriptionPrice">-->
<!--                        </div>-->
<!--                        &lt;!&ndash; Stripe Elements Placeholder &ndash;&gt;-->
<!--                        <div class="mt-4">-->
<!--                            <label for="card" class="leading-7 text-sm text-black dark:text-gray-200">Credit Card</label>-->
<!--                            <div id="card-element"></div>-->
<!--                        </div>-->
<!--                        <div class="mt-4">-->
<!--                            <button id="checkout-button"-->
<!--                                    @click="payNow"-->
<!--                                    class="h-fit bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4"-->
<!--                                    :disabled="paymentProcessing">-->
<!--                                <span v-if="paymentProcessing" class="spinner" id="spinner">Processing...</span>-->
<!--                                <span v-if="!paymentProcessing" id="button-text">-->
<!--                                  Pay now-->
<!--                                </span>-->
<!--                            </button>-->
<!--                        </div>-->
<!--                        &lt;!&ndash; {/* Show any error or success messages */}&ndash;&gt;-->
<!--                        <div v-if="message" id="payment-message" class="mt-4">{{message}}</div>-->
<!--                    </div>-->


            </div>
            </div>


    </div>

</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useUserStore } from "@/Stores/UserStore"
import { useShopStore } from "@/Stores/ShopStore"
import Message from "@/Components/Modals/Messages"
// import { loadStripe } from "@stripe/stripe-js"
import { Inertia } from "@inertiajs/inertia";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()
let shopStore = useShopStore()

userStore.currentPage = 'upgrade'
userStore.showFlashMessage = true;

// onBeforeMount(() => {
//     userStore.scrollToTopCounter = 0;
// })

let props = defineProps({
    user: Object,
    // intent: Object,
    // cardElement: ref({}),
    // paymentProcessing: false,
    selectedSubscription: null,
    hoverMonthly: false,
    hoverYearly: false,
    hoverForever: false,
    hoverMonthlyFull: false,
    hoverYearlyFull: false,
    hoverForeverFull: false,
})

onMounted(async() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()
    // if (userStore.scrollToTopCounter === 0 ) {
    //
    //     userStore.scrollToTopCounter ++;
    // }

    // shopStore.stripe = await loadStripe(process.env.MIX_STRIPE_KEY);
    // // shopStore.stripe = stripe
    // let elements = shopStore.stripe.elements()
    // const cardElement = elements.create('card', {
    //     classes: {
    //         base: 'bg-gray-100 py-3 px-2 rounded border border-gray-300 focus:border-indigo-500 text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out'
    //     }
    //     // shopStore.cardElement.mount('#card-element');
    // });
    // cardElement.mount('#card-element');

})

function payNow(subscription) {
    if (subscription === 'monthly') {
        shopStore.upgradeMonthly()
        Inertia.get('/shop/subscribe')
    } if (subscription === 'yearly') {
        shopStore.upgradeYearly()
        Inertia.get('/shop/subscribe')
    } if (subscription === 'forever') {
        shopStore.upgradeForever()
        Inertia.get('/shop/subscribe')
    }
}
//  function payNow(subscription) {
//
//     shopStore.paymentProcessing = true;
//
//     const {setupIntent, error} = await shopStore.stripe.confirmCardSetup(
//         props.intent.client_secret, {
//             payment_method: {
//                 card: shopStore.cardElement,
//                 billing_details: {name: props.user.name}
//             }
//         }
//     );
//
//     if (error) {
//         this.paymentProcessing = false;
//         alert(error);
//         console.log('ERROR');
//         console.log(error);
//     } else {
//         console.log('you arrived here.');
//         axios.post('subscription-checkout', {monthly_price: subscription, setupIntent: setupIntent.payment_method})
//             .then((response) => {
//                 this.paymentProcessing = false;
//                 console.log('success: subscription created.');
//             })
//             .catch((error) => {
//                 this.paymentProcessing = false;
//                 alert("Error2: " + error);
//                 console.log('Error 2');
//             });
//     }
// }

</script>



<style scoped>

.upgradeIcon {
    font-size: xxx-large;
}

.productName {
    font-size: large;
    font-weight: bold;
    padding-top: 1rem;
}

.price {
    font-size: xxx-large;
    color: dodgerblue;
    vertical-align: top;
}

.active {
    background-color: #0a59da;
}

.activeIcon {
    color: #0a59da;
}

</style>

