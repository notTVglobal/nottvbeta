<template>

        <div v-if="videoPlayerStore.ott === 2"
             class="fixed top-44 lg:top-78 right-0 h-full w-full lg:w-96 mt-4 p-2 overflow-y-scroll scrollbar-hide bg-gray-800"
             :class="ottDisplay">

                <h1 class="text-xs font-semibold uppercase mb-3 w-full bg-green-900 text-white p-2">
                    CHANNELS
                </h1>

                <div class="overflow-y-scroll scrollbar-hide mb-64 pb-24 w-full">
                    <Channels/>
                </div>

        </div>

</template>

<script setup>
import { computed } from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useStreamStore } from "@/Stores/StreamStore"
import { useChatStore } from "@/Stores/ChatStore.js"
import { useUserStore } from "@/Stores/UserStore"
import VideoOTTChat from "@/Components/VideoPlayer/Chat/VideoOTTChat"
import Channels from "@/Components/VideoPlayer/Channels/Channels"
import ChannelFooter from "@/Components/VideoPlayer/Channels/ChannelFooter.vue";

let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let chat = useChatStore()
let userStore = useUserStore()

let props = defineProps ({
    user: Object,
})

let playVideo = (source) => {
    videoPlayerStore.loadNewSourceFromMist(source)
}

const ottDisplayShow = computed(() => ({
    'hidden': videoPlayerStore.ottClass !== 'OttOpen'
}))

const ottDisplay = computed(() => ({
    ottDisplayMobile: userStore.isMobile,
    ottDisplayDesktop: !userStore.isMobile
}))

</script>

<style scoped>


</style>
