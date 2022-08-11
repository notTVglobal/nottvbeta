<template>

    <Head title="Shop" />

    <div class="place-self-center flex flex-col gap-y-3 pageWidth">
        <div class="bg-white text-black p-5 mb-10">

            <h1 class="text-3xl font-semibold pb-3">Shop</h1>
            <p class="mb-8">
                Our notTV Shop.
            </p>
            <div class="bg-orange-300 px-2">
                Display items here.
            </div>

        </div>
    </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
let videoPlayer = useVideoPlayerStore()
videoPlayer.class = "videoTopRight"
videoPlayer.videoContainerClass = "videoContainerTopRight"
videoPlayer.fullPage = false

</script>

