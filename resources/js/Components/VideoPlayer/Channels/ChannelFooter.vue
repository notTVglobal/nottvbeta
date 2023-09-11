<template>
    <div :class="channelFooterClass">
        <span>Live is our premiere channel featuring live streams and handpicked content from notTV creators.</span>
    </div>

</template>
<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { computed } from "vue";

let videoPlayerStore = useVideoPlayerStore()

const channelFooterClass = computed(() => ({
    channelFooterFullPage: videoPlayerStore.fullPage,
    channelFooterTopRight: !videoPlayerStore.fullPage,
}))

</script>
