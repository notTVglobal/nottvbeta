<template>
  <div id="topDiv">
    <div>
      {{ subscriptions }}
    </div>
  </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
const appSettingStore = useAppSettingStore()
import { onMounted } from "vue"
import { useUserStore } from "@/Stores/UserStore"

const videoPlayerStore = useVideoPlayerStore()
const userStore = useUserStore()

userStore.currentPage = 'Admin/Subscriptions'

defineProps({
  subscriptions: Object,
})

onMounted(() => {
  videoPlayerStore.makeVideoTopRight()
  if (userStore.isMobile) {

    appSettingStore.ott = 0
appSettingStore.pageIsHidden = false
  }
  document.getElementById("topDiv").scrollIntoView()

})


</script>
