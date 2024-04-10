<template>
  <Transition
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      enter-active-class="transition duration-300"
      leave-active-class="transition duration-200"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
  >
    <!--&lt;!&ndash; this transition doesn't seem to be working&ndash;&gt;-->
    <!--        <div v-if="show">-->

    <!-- Video Welcome Controls -->

    <div :class="videoControlsClass">
      <button
          class="text-lg md:text-xl bg-gray-800 rounded-full py-2 px-4 tracking-wider hover:bg-gray-600"
          @click="videoPlayerStore.fullscreen()">
        FULLSCREEN
      </button>

      <button v-if="videoPlayerStore.muted===true"
              class="text-lg md:text-xl bg-gray-800 rounded-full py-2 px-4 tracking-wider hover:bg-gray-600"
              @click="videoPlayerStore.unMute()">
        UNMUTE
      </button>

      <button v-if="videoPlayerStore.muted===false"
              class="text-lg md:text-xl bg-gray-800 rounded-full py-2 px-4 tracking-wider hover:bg-gray-600"
              @click="videoPlayerStore.mute()">
        MUTE
      </button>

      <button v-if="!videoPlayerStore.paused"
              class="text-lg md:text-xl bg-gray-800 rounded-full py-2 px-4 tracking-wider hover:bg-gray-600"
              @click="videoPlayerStore.pause()">
        PAUSE
      </button>

      <button v-if="videoPlayerStore.paused"
              class="text-lg md:text-xl bg-gray-800 rounded-full py-2 px-4 tracking-wider hover:bg-gray-600"
              @click="videoPlayerStore.play()">
        PLAY
      </button>

    </div>

  </Transition>
</template>

<script setup>
import { computed } from "vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
const appSettingStore = useAppSettingStore()
import { useChatStore } from "@/Stores/ChatStore"
import { useUserStore } from "@/Stores/UserStore"

const videoPlayerStore = useVideoPlayerStore()
const chatStore = useChatStore()
const userStore = useUserStore()

defineProps({
  show: Boolean
});

function backToPage() {
  // videoPlayerStore.makeVideoTopRight();
  // chatStore.showChat = false;
  // streamStore.showOSD = false;
  window.history.back()
}


const videoControlsClass = computed(() => ({
  videoControlsWelcomeMobile: userStore.isMobile,
  videoControlsWelcomeStandard: !userStore.isMobile
}))


</script>

<style scoped>
.videoControlsMobile {
  bottom: 12rem !important;
  margin-left: 0;
  margin-right: 0;
  padding-bottom: 0rem;
  right: 0px;
  width: 100%;
  justify-content: center;
}


</style>
