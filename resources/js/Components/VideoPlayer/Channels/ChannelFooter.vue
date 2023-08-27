<template>
    <div :class="channelFooterClass">
        <span>Live One is our premiere promotional channel featuring handpicked content and creators from the notTV network.</span>
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
