<template>

    <Head title="Video" />

    <div class="place-self-center flex flex-col gap-y-3 mr-96">
        <div class="bg-white text-black p-5 mb-10">

            <h1 class="text-3xl font-semibold pb-3">Videos</h1>
            <p class="mb-8">
                This is a temporary page. Created for testing purposes.
            </p>
            <div class="bg-orange-300 px-2">
            Display a MistServer API call here.
            </div>

<!--            <VideoPlayer :video="video"/>-->
        </div>
    </div>

</template>

<script setup>
// import VideoPlayer from "@/Components/VideoPlayer.vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js";
let videoPlayer = useVideoPlayerStore();
videoPlayer.class = "videoTopRight"

</script>


