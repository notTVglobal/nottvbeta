<template>

    <Head title="Upgrade Account" />
    <div id="topDiv"></div>
    <div class="h-[calc(100vh-16rem)] text-center bg-gray-800 text-white p-10">


            <Message v-if="showMessage" @close="showMessage = false" :message="props.message"/>

<!--            <h1 class="text-3xl font-semibold pb-3">Become a notTV Premium Subscriber</h1>-->

            <div class="flex justify-center w-full">
                <div class="flex flex-col justify-center w-fit bg-gray-900 rounded-md py-10">

                    <h2 class="text-3xl py-8 px-2 font-bold">Choose a Subscription</h2>

                    <div class="grid grid-cols-1 xl:grid-cols-3 justify-center xl:space-x-4 space-y-8 xl:space-y-0 px-8 mx-auto">
                        <Link class="bg-gray-700 hover:bg-gray-600 rounded-lg">
                            <div class="card2 annually px-12 py-6">
                                <div class="flex justify-between mb-2">
                                    <div class="productName">Monthly</div>
                                    <div class="price">$25</div>
                                </div>
                                <div class="py-10">
                                    IMAGE HERE
                                </div>
                                <div>
                                    Get full access to all features, shows, channels and movies!
                                </div>
                                <div class="bg-gray-900 hover:bg-gray-800 rounded-lg w-5/6 mt-6 py-4 mb-4 mx-auto"
                                     @mouseover="hoverMonthly = true"
                                     @mouseleave="hoverMonthly = false">
                                    <div class="inline mr-4 p-1 rounded-full bg-gray-700 hover:bg-white hover:text-blue-500"
                                        :class="{active: hoverMonthly}"> OE</div> <span> Select Plan</span></div>

                            </div>
                        </Link>
                        <Link class="bg-gray-700 hover:bg-gray-600 rounded-lg">
                        <div class="card2 annually px-12 py-6">
                            <div class="flex justify-between mb-2">
                                <div class="productName">Yearly</div>
                                <div class="price">$250</div>
                            </div>
                            <div class="py-10">
                                IMAGE HERE
                            </div>
                            <div>
                                Get full access to all features, shows, channels and movies!
                            </div>
                            <div class="bg-gray-900 hover:bg-gray-800 rounded-lg w-5/6 mt-6 py-4 mb-4 mx-auto"
                                 @mouseover="hoverMonthly = true"
                                 @mouseleave="hoverMonthly = false">
                            <div class="bg-gray-900 hover:bg-gray-800 rounded-lg w-5/6 mt-6 py-4 mb-4 mx-auto">
                                Select Plan</div>

                        </div>
                        </Link>
                        <Link class="bg-gray-700 hover:bg-gray-600 rounded-lg">
                        <div class="card3 forever px-12 py-6">
                            <div class="flex justify-between mb-2">
                                <div class="productName">Forever</div>
                                <div class="price">$999</div>
                            </div>
                            <div class="py-10">
                                IMAGE HERE
                            </div>
                            <div>
                                Get full access to all features, shows, channels and movies!
                            </div>
                            <div class="bg-gray-900 hover:bg-gray-800 rounded-lg w-5/6 mt-6 py-4 mb-4 mx-auto">
                                Select Plan</div>

                        </div>
                        </Link>
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

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'upgrade'

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
    hoverMonthly: false,
    hoverYearly: false,
    hoverForever: false,
})

let showMessage = ref(true);

</script>

<style scoped>

.productName {
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

</style>

