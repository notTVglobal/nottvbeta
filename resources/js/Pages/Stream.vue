<template>
    <Head title="Stream" />

</template>


<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js";

let videoPlayer = useVideoPlayerStore();

videoPlayer.fullPage = true;
videoPlayer.class = "videoFullPage";

defineProps ({
    video: Object
})

</script>

