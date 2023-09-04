<template>
    <div v-if="videoPlayerStore.ott !== 0" class="fixed top-44 lg:top-78 right-0 w-full h-full lg:w-96 mt-4 overflow-y-none z-40"
             :class="{'lg:mt-3':userStore.isMobile, 'lg:mt-2':!userStore.isMobile}">
        <div class="h-full w-full overflow-y-scroll scrollbar-hide">
            <upgrade v-if="videoPlayerStore.ott === 2 && (!userStore.userIsSubscriber || !userStore.userIsVip)"/>
            <div v-if="videoPlayerStore.ott === 2 && (userStore.userIsSubscriber || userStore.userIsVip)">

                    <h1 class="text-xs font-semibold uppercase mb-3 w-full bg-green-900 text-white p-2">
                        CHANNELS
                    </h1>

                    <div class="overflow-y-scroll scrollbar-hide mb-64 pb-24 w-full">
                        <Channels/>
                    </div>

            </div>
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
import Upgrade from "@/Components/VideoPlayer/OttTopRightDisplay/Upgrade.vue";

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
