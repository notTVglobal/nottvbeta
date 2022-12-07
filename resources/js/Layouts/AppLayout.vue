<template>
    <div class="top-0 left-0 bg-gray-800 text-gray-200 h-screen w-screen scrollbar-hidden-y overscroll-x-none">

        <!-- Navbar -->
        <div v-if="$page.props.user!=null" v-show="videoPlayerStore.showNav || ! videoPlayerStore.fullPage" class="fixed top-0 w-full nav-mask">
            <ResponsiveNavigationMenu/>
            <NavigationMenu />
        </div>


            <div class="w-full">
                <VideoPlayer class="z-50" :key="videoPlayerStore.key" :user="props.user"/>
<!--                <VideoPlayer :class="videoPlayerStore.class" class="z-50" :key="videoPlayerStore.key" :user="props.user"/>-->

            </div>

<div>
                <div v-if="!videoPlayerStore.fullPage" class="fixed top-72 w-full sm:w-96 right-0 z-30">
                    <videoOTTButtons class="videoOTT z-fivehundred"  v-if="$page.props.user!=null"/>
                </div>

                <VideoOTT v-show="$page.props.user!=null" :user="props.user" class="fixed top-78 h-screen right-0 w-full sm:w-96 mt-2 overflow-y-scroll" :class="videoPlayerStore.ottClass"/>


                <!-- Page Content -->
                <main v-if="$page.props.user===null" class="fixed top-0 h-screen w-screen overflow-y-scroll z-50">
                    <slot />
                </main>

                <main v-if="$page.props.user!=null" class="absolute top-80 pb-24 sm:top-16 w-full sm:w-[calc(100vw-24rem)] h-[calc(100vh-19rem)] sm:h-[calc(100vh-4rem)] z-20 overflow-y-scroll overscroll-x-none">
                        <slot />
                </main>
</div>
<!--        h-[calc(h-100vh-19rem)] fixed top-76 h-screen md:top-16 md:w-[calc(100vw-24rem)] overflow-y-scroll z-10-->

    </div>
</template>

<script setup>
import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/Navigation/NavigationMenu"
import VideoPlayer from "@/Components/VideoPlayer/VideoPlayer"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useStreamStore } from "@/Stores/StreamStore"
import { useUserStore } from "@/Stores/UserStore"
import VideoOTT from '@/Components/VideoPlayer/VideoOTT'
import VideoOTTButtons from '@/Components/VideoPlayer/VideoOTTButtons'
import { onMounted, onBeforeMount } from "vue";

let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let userStore = useUserStore()

// videoPlayerStore.videoSource = "https://mist2.not.tv/threestooges.mp4"
// videoPlayerStore.videoSource = "https://mist2.not.tv/hls/dunepull/index.m3u8"
videoPlayerStore.videoSource = "https://mist2.not.tv/hls/tmr1984pull/index.m3u8"
// videoPlayerStore.videoSource = "https://mist2.not.tv/hls/vmixlive/index.m3u8"
// videoPlayerStore.videoSourceType = "video/mp4"
videoPlayerStore.videoSourceType = "application/x-mpegURL"
videoPlayerStore.videoName = "The Terminator"
streamStore.currentChannel = "Stream"
userStore.showNavDropdown = false

let isStreamPage = null

function setPage() {
    if (videoPlayerStore.currentPage === "stream") {
        isStreamPage = true;
    } else
        isStreamPage = false;
}

setPage()

// tec21: this fixes the video size issue when
// a user reloads a page with the video in
// the top right. But, it keeps the video
// in fullPage until they click "back to page"
//
// onMounted(() => {
//     videoPlayerStore.makeVideoFullPage();
//
// });


userStore.checkIsMobile()
onMounted(() => {
    // videoPlayerStore.makeVideoFullPage()
})

let props = defineProps({
    user: Object,
});

// const loadVideoCSS = async () => {
//     await videoPlayerStore.makeVideoFullPage();
//     // await videoPlayerStore.makeVideoTopRight();
// }

// onMounted(() => {
//     loadVideoCSS();
// });


</script>
<style scoped>
.divZ {
    z-index: 900;

}

</style>
