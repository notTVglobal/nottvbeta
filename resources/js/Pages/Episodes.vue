<template>
    <Head title="Shows" />

    Shows

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js";
let videoPlayer = useVideoPlayerStore();
videoPlayer.class = "videoTopRight"
videoPlayer.fullPage = false
</script>
