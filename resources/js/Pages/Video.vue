<template>

    <Head title="MistServer API" />

    <div class="place-self-center flex flex-col gap-y-3 pageWidth">
        <div class="bg-white text-black p-5 mb-10">

            <h1 class="text-3xl font-semibold pb-3">MistServer API</h1>
            <p class="mb-8">
                This is a temporary page. Created for testing purposes.
            </p>
            <div class="bg-orange-300 px-2">
            Display a MistServer API call here.
            </div>

            <div>
                <video-player src="http://mist.nottv.io:8080/hls/ctd1984/index.m3u8"></video-player>
            </div>
        </div>
    </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js";
import VideoPlayer from "@/Components/VideoPlayer/VideoJS.vue";

let videoPlayer = useVideoPlayerStore();
videoPlayer.class = "videoTopRight"
videoPlayer.fullPage = false

</script>




