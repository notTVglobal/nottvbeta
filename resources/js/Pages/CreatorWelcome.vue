<template>
  <Head title="Welcome" />
</template>

<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()

appSettingStore.osd = false
appSettingStore.showOttButtons = false
appSettingStore.showOtt = false
appSettingStore.osdSlot.a = false
appSettingStore.osdSlot.b = false
appSettingStore.osdSlot.c = false
appSettingStore.osdSlot.d = false
videoPlayerStore.makeVideoFullPage()
videoPlayerStore.controls = false

</script>