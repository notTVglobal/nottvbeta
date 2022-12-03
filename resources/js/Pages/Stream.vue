<template>
    <Head title="Stream" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <ChatForStreamPageV2 :user="props.user"/>
    <button v-if="!chatStore.showChat" @click="chatStore.showChat = true"
        class="opacity-80 chatButtonForStreamPage w-20 h-20 rounded-full bg-orange-400 text-orange-100
        hover:bg-orange-600 hover:text-orange-300 cursor-pointer grid justify-center content-center">
        <font-awesome-icon icon="fa-comments" class="text-3xl"/><div>CHAT</div>
    </button>

</template>


<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import ChatForStreamPageV2 from "@/Components/Chat/ChatForStreamPageV2"
import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/Navigation/NavigationMenu"
import {onMounted} from "vue";

let videoPlayer = useVideoPlayerStore()
let chatStore = useChatStore()

onMounted(() => {
    videoPlayer.makeVideoFullPage();
    if (videoPlayer.videoSource != 'https://mist2.not.tv/hls/dunepull/index.m3u8') {
        videoPlayer.videoSource = 'https://mist2.not.tv/hls/dunepull/index.m3u8';
        videoPlayer.videoName = 'Dune';
        videoPlayer.loadNewSource()
    }

});
chatStore.showChat = false
videoPlayer.loggedIn = true
videoPlayer.currentView = 'stream'
videoPlayer.currentPage = 'stream'

let props = defineProps ({
    video: Object,
    user: Object,
})


</script>

