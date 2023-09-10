<template>
    <div v-if="videoPlayerStore.ott === 3" class="ottTopRightDisplay bg-orange-800 ">
    <div class="h-full w-full overflow-y-scroll scrollbar-hide">

        <upgrade v-if="videoPlayerStore.ott === 3 && !userStore.isSubscriber && !userStore.isVip && !userStore.isAdmin"/>
        <div v-if="videoPlayerStore.ott === 3 && (userStore.isSubscriber || userStore.isVip || userStore.isAdmin)" class="now-playing w-full h-full bg-orange-800 p-2 overflow-y-scroll scrollbar-hide mb-64">
            <h1 class="text-xs font-semibold uppercase mb-3 w-full bg-orange-900 text-white p-2">PLAYLIST</h1>
            <div class="pb-24 w-full overflow-y-scroll scrollbar-hide"
                 :class="[{'h-[calc(100vh-22rem)]':!userStore.isMobile},{'h-[calc(100vh-20rem)]':userStore.isMobile}]">
                Add a loop here to display the playlist... scrollable. If the current channel is the users_channel,
                display the playlist for the user. If the current channel is another channel (e.g., stream), display
                the stream_playlist.
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
    'hidden': !videoPlayerStore.ott
}))

const ottChannels = computed(() => ({
    channelsOttMobile: userStore.isMobile,
    channelsOttDesktop: !userStore.isMobile
}))

</script>

<style scoped>


</style>
