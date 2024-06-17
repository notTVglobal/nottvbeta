<template>
  <Transition
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      enter-active-class="transition duration-800"
      leave-active-class="transition duration-800"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
  >

    <div v-if="videoPlayerStore.controls" class="flex justify-center">

      <!-- Video TopRight Controls -->
      <div class="lg:flex fixed justify-center right-1 top-56 px-2 w-96 z-50">

        <VideoControlsButtons/>

      </div>
      
    </div>

  </Transition>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useChatStore } from '@/Stores/ChatStore'
import { useUserStore } from '@/Stores/UserStore'
import VideoControlsButtons from '@/Components/Global/VideoPlayer/VideoControls/Elements/VideoControlsButtons'

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()
const chatStore = useChatStore()
const userStore = useUserStore()

defineProps({
  show: Boolean,
})

const goToStreamPage = () => {
  videoPlayerStore.makeVideoFullPage()
  router.visit('stream')
}


</script>

<style scoped>

.sr-only {
  position: absolute;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  width: 1px;
  height: 1px;
  margin: -1px;
  padding: 0;
  border: 0;
}

/* Normal state styles for the icon */
.icon-container {
  display: inline-block;
}

.icon {
  fill: currentColor; /* Use the current text color as the icon color */
  transition: fill 0.3s ease; /* Smooth color transition */
}

/* Hover state styles for the icon */
.icon-container:hover .icon {
  fill: #f59e0b; /* Change to text-yellow-500 color on hover */
}


</style>
