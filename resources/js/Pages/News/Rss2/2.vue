<template>
  <div>
    <div id="topDiv">
      <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

    </div>
  </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
const appSettingStore = useAppSettingStore()
import { useUserStore } from "@/Stores/UserStore"
import { onMounted } from "vue"
import Message from "@/Components/Global/Modals/Messages"

const videoPlayerStore = useVideoPlayerStore()
const userStore = useUserStore()

onMounted(() => {
  videoPlayerStore.makeVideoTopRight();
  if (userStore.isMobile) {

    appSettingStore.ott = 0
appSettingStore.pageIsHidden = false
  }
  document.getElementById("topDiv").scrollIntoView()
});

</script>
