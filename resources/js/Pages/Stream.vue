<template>
    <Head title="Stream" />


</template>


<script setup>
import {onBeforeMount, onMounted, onUnmounted} from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useStreamStore } from "@/Stores/StreamStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"

let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let chatStore = useChatStore()

onBeforeMount(() => {
    videoPlayerStore.currentPageIsStream = true;
    videoPlayerStore.currentView = 'stream'
    videoPlayerStore.currentPage = 'stream'
})

onMounted(() => {
    videoPlayerStore.makeVideoFullPage()
    videoPlayerStore.showOsdAndControlsAndNav()
    videoPlayerStore.ottClass = 'OttClose'
    videoPlayerStore.ott = 0

    videoPlayerStore.showOttButtons = true
    chatStore.showChat = false
    videoPlayerStore.showChannels = false
    videoPlayerStore.showPlaylist = false
    videoPlayerStore.showFilters = false
    videoPlayerStore.loggedIn = true

})

onUnmounted(() => {
    videoPlayerStore.currentPageIsStream = false;
    chatStore.showChat = false
    streamStore.showOSD = true
    videoPlayerStore.showOttButtons = true
    videoPlayerStore.showChannels = false
    videoPlayerStore.showPlaylist = false
    videoPlayerStore.showFilters = false
})

let props = defineProps ({
    video: Object,
    user: Object,
})

</script>

