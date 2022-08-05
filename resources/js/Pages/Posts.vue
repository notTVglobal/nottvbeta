<template>

    <Head title="Posts" />

    <div class="place-self-center flex flex-col gap-y-3 mr-96">
        <div class="bg-white text-black p-5 mb-10">

            <h1 class="text-3xl font-semibold pb-3">Posts</h1>
            <p class="mb-8">
                Display our posts here of new events, blog items, etc.
            </p>
            <div class="bg-orange-300 px-2">
                Display posts here.
            </div>

        </div>
    </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js";
let videoPlayer = useVideoPlayerStore();
videoPlayer.class = "videoTopRight"
videoPlayer.fullPage = false

</script>


