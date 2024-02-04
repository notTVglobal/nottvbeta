<template>
  <div :class="channelFooterClass">
    <span>Live is our premiere channel featuring live streams and handpicked content from notTV creators.</span>
  </div>

</template>
<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
const appSettingStore = useAppSettingStore()
import { computed } from "vue"

const videoPlayerStore = useVideoPlayerStore()

const channelFooterClass = computed(() => ({
  channelFooterFullPage: appSettingStore.fullPage,
  channelFooterTopRight: !appSettingStore.fullPage,
}))

</script>
