<template>
    <Head title="Stream" />


</template>


<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useStreamStore } from "@/Stores/StreamStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import {onBeforeMount, onBeforeUnmount, onMounted, onUnmounted} from "vue";
import videojs from 'video.js';

let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let chatStore = useChatStore()

videoPlayerStore.currentPageIsStream = true;

onMounted(() => {
    videoPlayerStore.makeVideoFullPage()
    videoPlayerStore.showOSD = true
    videoPlayerStore.showNav = true
    videoPlayerStore.showControls = true
    videoPlayerStore.ottClass = 'OttClose'
    videoPlayerStore.ott = 0
    let videoJs = videojs('main-player')
    if (videoJs.muted(false)) {
        videoPlayerStore.unmute()
    }

})
// if (streamStore.currentChannel != "Stream") {
//     let source = "mist1pull1";
//     videoPlayerStore.videoName = "notTV One";
//     streamStore.currentChannel = "notTV One";
//     videoPlayerStore.loadNewSourceFromMist(source);
// }

onBeforeUnmount(() => {
    chatStore.showChat = false
    streamStore.showOSD = true
    videoPlayerStore.showOttButtons = true
    videoPlayerStore.showChannels = false
    videoPlayerStore.showPlaylist = false
    videoPlayerStore.showFilters = false

})

onUnmounted(() => {
    videoPlayerStore.currentPageIsStream = false;
})

chatStore.showChat = false
streamStore.showOSD = true
videoPlayerStore.showOttButtons = true
videoPlayerStore.showChannels = false
videoPlayerStore.showPlaylist = false
videoPlayerStore.showFilters = false
videoPlayerStore.loggedIn = true
videoPlayerStore.currentView = 'stream'
videoPlayerStore.currentPage = 'stream'

let props = defineProps ({
    video: Object,
    user: Object,
})

</script>

