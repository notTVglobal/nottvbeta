<template>
<div>
    <div class="absolute w-full flex justify-between top-16 left-0 p-5 drop-shadow z-50">
        <div v-if="videoPlayerStore.videoName !== ''">
            <span class="text-xs uppercase pr-2">Now playing: </span>
            <span class="font-semibold">{{ videoPlayerStore.videoName }}</span>
        </div>
        <div v-if="channelStore.currentChannelName !== null">
            <span class="text-xs uppercase pr-2">Channel: </span>
            <span class="text-xs font-semibold">{{ channelStore.currentChannelName }}</span>
        </div>

        <div class="absolute py-6 left-0 px-5 drop-shadow z-50">
            <span v-if="channelStore.isLive" class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-white bg-opacity-80 bg-red-800 last:mr-0 mr-1">
                live
            </span>
            <div v-if="channelStore.currentChannelId !== null">
                <CurrentViewers />
            </div>
        </div>
    </div>
<!--    <div v-if="videoPlayerStore.currentPage!='stream'" @click="backToPage" class="absolute w-full flex justify-between mb-6 bottom-12 left-0 p-5 drop-shadow z-50">-->
    <div class="absolute w-full flex justify-between mb-6 bottom-4 left-0 p-5 drop-shadow z-50">
        <div>
            <button @click="backToPage" class="p-2 bg-gray-800 text-white hover:bg-gray-600">Back to Page</button>
        </div>
    </div>
</div>
</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useStreamStore } from "@/Stores/StreamStore"
import { useChannelStore } from "@/Stores/ChannelStore"
import CurrentViewers from "@/Components/VideoPlayer/CurrentViewers.vue";

let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let channelStore = useChannelStore()

function backToPage() {
    // videoPlayerStore.makeVideoTopRight();
    // chatStore.showChat = false;
    // streamStore.showOSD = false;
    window.history.back()
}

</script>
