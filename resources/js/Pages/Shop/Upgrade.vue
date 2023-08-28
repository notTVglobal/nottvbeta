<template>

    <Head title="Upgrade Account" />
    <div id="topDiv" class="h-[calc(100vh-16rem)] text-center bg-gray-800 text-white p-10">


            <Message v-if="showMessage" @close="showMessage = false" :message="props.message"/>

<!--            <h1 class="text-3xl font-semibold pb-3">Become a notTV Premium Subscriber</h1>-->

            <div class="flex justify-center w-full">
                <div class="flex flex-col justify-center w-fit bg-gray-900 rounded-md py-10 mb-24">

                    <h2 class="text-3xl py-8 px-2 font-bold">Choose a Subscription</h2>

                    <div class="grid grid-cols-1 xl:grid-cols-3 justify-center xl:space-x-4 space-y-8 xl:space-y-0 px-8 mx-auto">

                            <div class="card monthly bg-gray-700 hover:bg-gray-600 hover:cursor-pointer rounded-lg px-12 py-6"
                                 @mouseover="hoverMonthlyFull = true"
                                 @mouseleave="hoverMonthlyFull = false"
                                 @click="payNow('plan_LyCOYZAqzVdFpz')">
                                <div class="flex justify-between mb-2">
                                    <div class="productName">Monthly</div>
                                    <div class="price">$25</div>
                                </div>
                                <div class="py-10">
                                    <font-awesome-icon icon="fa-star" class="upgradeIcon" :class="{activeIcon: hoverMonthlyFull}"/>
                                </div>
                                <div>
                                    Get full access to all features, shows, channels and movies!
                                </div>
                                <div class="flex no-wrap justify-center bg-gray-900 hover:bg-gray-800 rounded-lg w-full mt-6 px-4 py-4 mb-4 mx-auto"
                                     @mouseover="hoverMonthly = true"
                                     @mouseleave="hoverMonthly = false">
                                    <span class="bg-gray-700 mr-4 py-1 px-2 w-fit rounded-full"
                                        :class="{active: hoverMonthly}"><font-awesome-icon icon="fa-check" /></span> <span> Select Plan</span></div>

                            </div>

                        <div class="card annually bg-gray-700 hover:bg-gray-600 hover:cursor-pointer rounded-lg px-12 py-6"
                             @mouseover="hoverYearlyFull = true"
                             @mouseleave="hoverYearlyFull = false"
                             @click="payNow('price_1NhgZTKahp38LUVY8n9Skgwf')">
                            <div class="flex justify-between mb-2">
                                <div class="productName">Yearly</div>
                                <div class="price">$250</div>
                            </div>
                            <div class="py-10">
                                <font-awesome-icon icon="fa-star" class="upgradeIcon" :class="{activeIcon: hoverYearlyFull}"/><font-awesome-icon icon="fa-star" class="upgradeIcon" :class="{activeIcon: hoverYearlyFull}"/>
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

                        <div class="card forever bg-gray-700 hover:bg-gray-600 rounded-lg  px-12 py-6"
                             @mouseover="hoverForeverFull = true"
                             @mouseleave="hoverForeverFull = false"
                             @click="payNow('price_1NhgZyKahp38LUVY1MOhE5L5')">
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
                            <div class="flex no-wrap justify-center bg-gray-900 hover:bg-gray-800 rounded-lg w-full mt-6 px-4 py-4 mb-4 mx-auto"
                                 @mouseover="hoverForever = true"
                                 @mouseleave="hoverForever = false">
                                <span class="bg-gray-700 mr-4 py-1 px-2 w-fit rounded-full"
                                     :class="{active: hoverForever}"><font-awesome-icon icon="fa-check" /></span> <span> Select Plan</span></div>

                        </div>
                    </div>



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
import { loadStripe } from "@stripe/stripe-js"

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()
let shopStore = useShopStore()

videoPlayerStore.currentPage = 'upgrade'

// onBeforeMount(() => {
//     userStore.scrollToTopCounter = 0;
// })

let props = defineProps({
    user: Object,
    intent: Object,
    stripe: {},
    cardElement: {},
    paymentProcessing: false,
    selectedSubscription: null,
    message: String,
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
    shopStore.stripe = await loadStripe(process.env.MIX_STRIPE_KEY);
    const elements = shopStore.stripe.elements('paymentRequestButton')
    shopStore.cardElement = elements.create()
    // shopStore.cardElement.mount('#card-element');

});



let showMessage = ref(true);

async function payNow(subscription) {

    shopStore.paymentProcessing = true;

    const {setupIntent, error} = await shopStore.stripe.confirmCardSetup(
        props.intent.client_secret, {
            payment_method: {
                card: shopStore.cardElement,
                billing_details: {name: props.user.name}
            }
        }
    );

    if (error) {
        this.paymentProcessing = false;
        alert(error);
        console.log('ERROR');
        console.log(error);
    } else {
        console.log('you arrived here.');
        axios.post('subscription-checkout', {monthly_price:subscription,setupIntent:setupIntent.payment_method})
            .then((response) => {
                this.paymentProcessing = false;
                console.log('success: subscription created.');
            })
            .catch((error) => {
                this.paymentProcessing = false;
                alert("Error2: "+error);
                console.log('Error 2');
            });
    }
}

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

