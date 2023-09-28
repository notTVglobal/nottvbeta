<template>
    <div class="place-self-center flex flex-col gap-y-3">
        <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">
            <div class="flex flex-col w-full text-center justify-center mt-10">
                <img
                    src="https://nottvbeta.sfo3.cdn.digitaloceanspaces.com/public/2023/09/images/pTJbtM0IeHCV1qzSFNeJD7H2mogeYM7MSzCCo1m0.png"
                    alt="celebration emoji"
                    class="w-20">

                <div class="text-4xl pl-10 pt-5">You are now a premium subscriber!</div>

            </div>
            <div class="text-center w-full mt-6 pt-3">Thank you for supporting independent media and free press.<br>
                <span class="pt-2">We value your subscription, if you have any questions or comments,</span><br>
                Please email <a href="mailto:cathy@not.tv" class="text-blue-600 hover:text-blue-500">cathy@not.tv</a></div>
            <div class="text-center w-full mt-6 pt-3">If you don't see the premium features you may need to refresh your browser page.</div>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from "vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore"
import { useShopStore } from "@/Stores/ShopStore"
import {Inertia} from "@inertiajs/inertia";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()
let shopStore = useShopStore()

userStore.currentPage = 'subscriptionSuccess'
userStore.showFlashMessage = true;

let props = defineProps({
    userIsSubscriber: null
})

onMounted(async() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()

    if(props.userIsSubscriber) {
        userStore.isSubscriber = true
    }

    Inertia.reload()

});

</script>
