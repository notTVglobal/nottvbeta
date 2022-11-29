<template>
    <div>
        <JetBanner/>

        <div class="relative top-0 bg-gray-800 text-gray-200 h-full w-full ">

            <!-- Page Heading -->
                        <header v-if="$slots.header" class="bg-white shadow">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                <slot name="header" />
                            </div>
                        </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>

            <div class="relative w-full h-full top-0">
                <VideoPlayer :class="videoPlayerStore.class" class="videoContainer" :key="videoPlayerStore.key"/>
            </div>

        </div>

    </div>

</template>

<script setup>
import VideoPlayer from "@/Components/VideoPlayer/VideoPlayer.vue"
import JetBanner from '@/Jetstream/Banner.vue'
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js"

let videoPlayerStore = useVideoPlayerStore()
videoPlayerStore.videoSource = 'https://streams.not.tv/hls/naturalworld/index.m3u8'

defineProps({
    user: Object,
});

</script>

<style>
.videoContainer {
    z-index:50;
}
.headerContainer {
    z-index:100;
}
</style>
