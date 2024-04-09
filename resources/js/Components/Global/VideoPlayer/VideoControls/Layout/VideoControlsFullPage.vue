<template>
  <Transition
      enter-from-class="opacity-0 scale-300"
      enter-to-class="opacity-100 scale-100"
      enter-active-class="transition ease-in-out duration-500 transform"
      leave-active-class="transition ease-in-out duration-500 transform"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-300"
  >
    <!-- Video FullPage Controls -->

    <div class="videoControls">

      <VideoControlsButtons/>

    </div>

  </Transition>
</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
const appSettingStore = useAppSettingStore()
import { useChatStore } from "@/Stores/ChatStore"
import { useUserStore } from "@/Stores/UserStore"
import VideoControlsButtons from "@/Components/Global/VideoPlayer/VideoControls/Elements/VideoControlsButtons"

const videoPlayerStore = useVideoPlayerStore()
const chatStore = useChatStore()
const userStore = useUserStore()

defineProps({
  show: Boolean
});


</script>
