<template>
  <Head title="Welcome" />
</template>

<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useUserStore } from '@/Stores/UserStore'
import { onMounted, onUnmounted } from 'vue'

usePageSetup('creatorWelcome')

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()
const userStore = useUserStore()

const props = defineProps({
  user_name: String
})

appSettingStore.osd = false
appSettingStore.showOttButtons = false
appSettingStore.showOtt = false
appSettingStore.osdSlot.a = false
appSettingStore.osdSlot.b = false
appSettingStore.osdSlot.c = false
appSettingStore.osdSlot.d = false
videoPlayerStore.makeVideoFullPage()
videoPlayerStore.controls = false

onMounted(() => {
  appSettingStore.showCreatorWelcomeModal = true
  userStore.user.name = props.user_name
})

onUnmounted(() => {
  appSettingStore.showCreatorWelcomeModal = false
})
</script>