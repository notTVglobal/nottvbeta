<template>
    <div class="top-0 left-0 bg-gray-800 text-gray-200 h-screen w-screen">

        <!-- Navbar -->
        <div v-if="$page.props.user!=null" class="fixed top-0 w-full nav-mask">
            <ResponsiveNavigationMenu/>
            <NavigationMenu />
        </div>


            <div>
                <VideoPlayer :class="videoPlayerStore.class" class="z-50" :key="videoPlayerStore.key" :user="props.user"/>

            </div>

<div>
                <div v-if="!videoPlayerStore.fullPage" class="fixed top-72 w-full md:w-96 right-0 z-30">
                    <videoOTTButtons class="videoOTT z-fivehundred"  v-if="$page.props.user!=null"/>
                </div>

                <VideoOTT v-if="$page.props.user!=null" :user="props.user" class="fixed top-76 h-screen right-0 w-full md:w-96 mt-2 overflow-y-scroll" :class="videoPlayerStore.ottClass"/>


                <!-- Page Content -->
                <main v-if="$page.props.user===null" class="fixed top-0 h-screen w-screen overflow-y-scroll z-50">
                    <slot />
                </main>

                <main v-if="$page.props.user!=null" class="fixed top-76 md:top-16 md:w-[calc(100vw-24rem)] h-[calc(100vh-19rem)] md:h-[calc(100vh-4rem)] overflow-y-scroll z-20">
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

videoPlayerStore.videoSource = "https://mist2.not.tv/hls/threestooges/index.m3u8"
videoPlayerStore.videoName = "Main Stream"
streamStore.currentChannel = "Stream"
userStore.showNavDropdown = false;

// tec21: this fixes the video size issue when
// a user reloads a page with the video in
// the top right. But, it keeps the video
// in fullPage until they click "back to page"
//
// onMounted(() => {
//     videoPlayerStore.makeVideoFullPage();
//
// });

onMounted(() => {
    videoPlayerStore.makeVideoFullPage()
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
