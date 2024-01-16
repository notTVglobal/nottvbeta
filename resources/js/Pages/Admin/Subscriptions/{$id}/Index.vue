<template>
  <div id="topDiv">
    <div>
      {{ subscription }}
    </div>

  </div>

</template>

<script setup>
import { onMounted } from "vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
const appSettingStore = useAppSettingStore()
import { useUserStore } from "@/Stores/UserStore"

const videoPlayerStore = useVideoPlayerStore()
const userStore = useUserStore()

let props = defineProps({
  subscription: Object,
})

userStore.currentPage = `Admin/Subscription/${props.subscription.id}/Index`

onMounted(() => {
  videoPlayerStore.makeVideoTopRight()
  if (userStore.isMobile) {

    appSettingStore.ott = 0
appSettingStore.pageIsHidden = false
  }
  document.getElementById("topDiv").scrollIntoView()

})


</script>
