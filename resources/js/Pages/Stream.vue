<template>
    <Head title="Stream" />

    <ChatForStreamPageV2 :user="props.user"/>
    <button v-if="!chatStore.showChat" @click="chatStore.showChat = true"
        class="opacity-80 chatButtonForStreamPage w-20 h-20 rounded-full bg-orange-400 text-orange-100
        hover:bg-orange-600 hover:text-orange-300 cursor-pointer grid justify-center content-center">
        <font-awesome-icon icon="fa-comments" class="text-3xl"/><div>CHAT</div>
    </button>

</template>


<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useStreamStore } from "@/Stores/StreamStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import ChatForStreamPageV2 from "@/Components/Chat/ChatForStreamPageV2"
import { onBeforeMount, onMounted } from "vue";

let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let chatStore = useChatStore()

onBeforeMount(() => {
    videoPlayerStore.makeVideoFullPage();
    
});

onMounted(() => {
    if (streamStore.currentChannel != 'Stream') {
        videoPlayerStore.videoSource = 'https://mist2.not.tv/hls/dunepull/index.m3u8';
        videoPlayerStore.videoName = 'Dune';
        videoPlayerStore.loadNewSource()
    }
})
chatStore.showChat = false
videoPlayerStore.loggedIn = true
videoPlayerStore.currentView = 'stream'
videoPlayerStore.currentPage = 'stream'

let props = defineProps ({
    video: Object,
    user: Object,
})


</script>

