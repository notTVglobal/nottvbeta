<template>

    <upgrade v-if="videoPlayerStore.ott === 2 && !userStore.isSubscriber && !userStore.isVip && !userStore.isAdmin" />
    <div v-if="videoPlayerStore.ott === 2 && (userStore.isSubscriber || userStore.isVip || userStore.isAdmin)">

        <div ref="scrollRef"
             v-if="videoPlayerStore.ott === 2"
             class="channelsMenu ottTopRightDisplay bg-green-900 overflow-y-auto scrollbar-custom">

            <h1 class="text-xs font-semibold uppercase w-full bg-green-900 text-white px-2 mt-2 mb-2 justify-center">
                CHANNELS
            </h1>

            <div class="w-full">
                <Channels/>
            </div>

            <div class="fixed w-full bottom-4 text-center">
                <ScrollDownIndicator />
            </div>

        </div>
    </div>

</template>

<script setup>
import {computed, provide, ref} from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useStreamStore } from "@/Stores/StreamStore"
import { useChatStore } from "@/Stores/ChatStore.js"
import { useUserStore } from "@/Stores/UserStore"
import Channels from "@/Components/VideoPlayer/Channels/Channels"
import Upgrade from "@/Components/VideoPlayer/OttTopRightDisplay/Upgrade.vue"
import ScrollDownIndicator from "@/Components/UserHints/ScrollDownIndicator.vue"

let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let chat = useChatStore()
let userStore = useUserStore()

const scrollRef = ref(null);
provide('scrollRef', scrollRef);

let props = defineProps ({
    user: Object,
})

let playVideo = (source) => {
    videoPlayerStore.loadNewSourceFromMist(source)
}

const ottDisplayShow = computed(() => ({
    'hidden': !videoPlayerStore.ott
}))

const ottDisplay = computed(() => ({
    ottDisplayMobile: userStore.isMobile,
    ottDisplayDesktop: !userStore.isMobile
}))

</script>

<style scoped>


</style>
