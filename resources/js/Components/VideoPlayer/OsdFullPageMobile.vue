<template>
<div>
    <div class="videoFullPageMobileOSD absolute w-full left-0 p-5 drop-shadow z-50">
        <div v-if="videoPlayerStore.videoName !== null">
            <span class="text-xs uppercase pr-2">Now playing: </span>
            <span class="font-semibold">{{ videoPlayerStore.videoName }}</span>
        </div>
        <div v-if="channelStore.currentChannelName !== null">
            <span class="text-xs uppercase pr-2">Channel: </span>
            <span class="text-xs font-semibold">{{ channelStore.currentChannelName }}</span>
        </div>

        <div class="absolute pt-1 left-0 px-5 drop-shadow z-50">
            <span v-if="channelStore.isLive" class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-white bg-opacity-80 bg-red-800 uppercase last:mr-0 mr-1">
                live
            </span>
            <div v-if="channelStore.currentChannelId !== null">
                <CurrentViewers />
            </div>
        </div>
    </div>
    <div class="fixed w-fit bottom-24 lg:bottom-8 left-0 p-5 drop-shadow z-50">
        <button @click="backToPage" class="p-2 bg-gray-600 text-white" >Back to Page</button>
    </div>
</div>
</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useStreamStore } from "@/Stores/StreamStore"
import { useUserStore } from "@/Stores/UserStore"
import { useChatStore } from "@/Stores/ChatStore"
import { useChannelStore } from "@/Stores/ChannelStore"
import CurrentViewers from "@/Components/VideoPlayer/CurrentViewers.vue";

let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let userStore = useUserStore()
let chatStore = useChatStore()
let channelStore = useChannelStore()

function backToPage() {
    // videoPlayerStore.makeVideoTopRight();
    // chatStore.showChat = false;
    // streamStore.showOSD = false;
    window.history.back()
}

let isMobile = userStore.isMobile;
let videoName = videoPlayerStore.videoName;
let isLive = streamStore.isLive;
let currentPage = videoPlayerStore.currentPage;

</script>
