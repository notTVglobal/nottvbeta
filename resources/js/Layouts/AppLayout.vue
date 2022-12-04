<template>
    <div class="top-0 left-0 bg-gray-800 text-gray-200 h-screen w-screen">

        <!-- Navbar -->
        <div class="fixed top-0 w-full nav-mask">
            <ResponsiveNavigationMenu/>
            <NavigationMenu />
        </div>

        <!-- Page Content -->
        <main class="fixed top-16 w-[calc(100vw-24rem)] h-[calc(100vh-4rem)] overflow-y-scroll">
            <slot />
        </main>

        <div>
            <VideoPlayer :class="videoPlayerStore.class" class="videoContainer" :key="videoPlayerStore.key" :user="props.user"/>

        </div>

        <div v-if="!videoPlayerStore.fullPage" class="fixed top-72 right-0 w-96">
            <videoOTTButtons class="videoOTT"/>
        </div>

        <VideoOTT :user="props.user"  class="fixed top-76 right-0 mt-2 w-96 h-[calc(100vh-4rem)] overflow-y-scroll"/>


    </div>

</template>

<script setup>
import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/Navigation/NavigationMenu"
import VideoPlayer from "@/Components/VideoPlayer/VideoPlayer.vue"
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js"
import VideoOTT from '@/Components/VideoPlayer/VideoOTT'
import VideoOTTButtons from '@/Components/VideoPlayer/VideoOTTButtons'

let videoPlayerStore = useVideoPlayerStore()
videoPlayerStore.videoSource = "https://mist2.not.tv/hls/naturalworld/index.m3u8"
videoPlayerStore.videoName = "Natural World"

let props = defineProps({
    user: Object,
});

</script>

<style>
.videoContainer {
    z-index:50;
}
</style>
