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
    videoPlayerStore.loggedIn = true
    videoPlayerStore.ott = 0
    videoPlayerStore.osd = true

    videoPlayerStore.ottButtons = true
    videoPlayerStore.ottChannels = false
    videoPlayerStore.ottChat = false
    videoPlayerStore.ottPlaylist = false
    videoPlayerStore.ottFilters = false

})

onUnmounted(() => {
    videoPlayerStore.currentPageIsStream = false;
    videoPlayerStore.ott = 0
    videoPlayerStore.osd = true

    videoPlayerStore.ottButtons = true
    videoPlayerStore.ottChannels = false
    videoPlayerStore.ottChat = false
    videoPlayerStore.ottPlaylist = false
    videoPlayerStore.ottFilters = false
})

let props = defineProps ({
    video: Object,
    user: Object,
})

</script>

