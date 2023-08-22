<template>

    <Head title="Order Summary" />

    <div class="place-self-center flex flex-col gap-y-3">
        <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

            <Message v-if="showMessage" @close="showMessage = false" :message="props.message"/>

            <header class="flex justify-between mb-3">
                <div id="topDiv">
                    <h1 class="text-3xl font-semibold pb-3">Order Summary</h1>
                </div>
            </header>

            <div class="mb-4">
                <p class="mt-4">
                    This is the order summary page.
                </p>
            </div>

            <!--            <div class="flex flex-row justify-end gap-x-4 mb-4">-->

            <!--                <input v-model="search" type="search" placeholder="Search..." class="border px-2 rounded-lg" />-->
            <!--            </div>-->

            <div class="px-2">
                <!--                Display items, services and events here.-->
            </div>

        </div>
    </div>

</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore"
import { useShopStore } from "@/Stores/ShopStore"
import Message from "@/Components/Modals/Messages"

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()
let shopStore = useShopStore()

videoPlayerStore.currentPage = 'shop'

// onBeforeMount(() => {
//     userStore.scrollToTopCounter = 0;
// })

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()

    shopStore.getProducts()
});

let props = defineProps({
    can: Object,
    message: String,
})


let showMessage = ref(true);

</script>

