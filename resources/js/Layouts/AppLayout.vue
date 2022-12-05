<template>
    <div class="top-0 left-0 bg-gray-800 text-gray-200 h-screen w-screen">

        <!-- Navbar -->
        <div v-if="$page.props.user!=null" class="fixed top-0 w-full nav-mask">
            <ResponsiveNavigationMenu/>
            <NavigationMenu />
        </div>


        <div>
            <VideoPlayer :class="videoPlayerStore.class" class="w-full h-full" :key="videoPlayerStore.key" :user="props.user"/>

        </div>


        <div v-if="!videoPlayerStore.fullPage" class="fixed top-72 right-0 w-96">
            <videoOTTButtons class="videoOTT"  v-if="$page.props.user!=null"/>
        </div>

        <VideoOTT v-if="$page.props.user!=null" :user="props.user"  class="fixed top-76 right-0 mt-2 w-96 h-[calc(100vh-4rem)] overflow-y-scroll"/>


        <!-- Page Content -->
        <main v-if="$page.props.user===null" class="fixed top-0 h-screen w-screen overflow-y-scroll z-10">
            <slot />
        </main>

        <main v-if="$page.props.user!=null" class="fixed top-16 w-[calc(100vw-24rem)] h-[calc(100vh-4rem)] overflow-y-scroll">
            <slot />
        </main>


    </div>

</template>

<script setup>
import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/Navigation/NavigationMenu"
import VideoPlayer from "@/Components/VideoPlayer/VideoPlayer"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useStreamStore } from "@/Stores/StreamStore"
import VideoOTT from '@/Components/VideoPlayer/VideoOTT'
import VideoOTTButtons from '@/Components/VideoPlayer/VideoOTTButtons'
import { onMounted } from "vue";

let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()

videoPlayerStore.videoSource = "https://mist2.not.tv/hls/naturalworld/index.m3u8"
videoPlayerStore.videoName = "Main Stream"
streamStore.currentChannel = "Stream"

// tec21: this fixes the video size issue when
// a user reloads a page with the video in
// the top right. But, it keeps the video
// in fullPage until they click "back to page"
//
// onMounted(() => {
//     videoPlayerStore.makeVideoFullPage();
//
// });

let props = defineProps({
    user: Object,
});

const loadVideoCSS = async () => {
    await videoPlayerStore.makeVideoFullPage();
    // await videoPlayerStore.makeVideoTopRight();
}

// onMounted(() => {
//     loadVideoCSS();
// });


</script>
