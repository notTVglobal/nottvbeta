<template>
    <Head title="Stream" />


</template>


<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useStreamStore } from "@/Stores/StreamStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import {onBeforeUnmount, onMounted, onUnmounted} from "vue";

let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let chatStore = useChatStore()

videoPlayerStore.currentPageIsStream = true;

onMounted(() => {
    videoPlayerStore.makeVideoFullPage()
    if (streamStore.currentChannel != 'Stream') {
        videoPlayerStore.videoSource = 'https://mist2.not.tv/hls/dunepull/index.m3u8';
        videoPlayerStore.videoSourceType = "application/x-mpegURL"
        videoPlayerStore.videoName = 'Dune';
        videoPlayerStore.loadNewSource()
    }

})

onBeforeUnmount(() => {
    chatStore.showChat = false
    streamStore.showOSD = true

})

onUnmounted(() => {
    videoPlayerStore.currentPageIsStream = false;
})

chatStore.showChat = false
streamStore.showOSD = false
videoPlayerStore.loggedIn = true
videoPlayerStore.currentView = 'stream'
videoPlayerStore.currentPage = 'stream'
streamStore.currentChannel = 'Stream'

let props = defineProps ({
    video: Object,
    user: Object,
})

</script>

