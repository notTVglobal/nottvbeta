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
          class="font-bold text-xl md:text-2xl bg-gray-800 rounded-full py-3 px-6 tracking-wider hover:bg-gray-600 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 shadow-lg transition-transform duration-300 ease-in-out"
          @click="videoPlayerStore.fullscreen()">
        FULLSCREEN
      </button>

      <button v-if="videoPlayerStore.muted===true"
              class="font-bold text-xl md:text-2xl bg-gray-800 rounded-full py-3 px-6 tracking-wider hover:bg-gray-600 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 shadow-lg transition-transform duration-300 ease-in-out"
              @click="videoPlayerStore.unMute()">
        UNMUTE
      </button>

      <button v-if="videoPlayerStore.muted===false"
              class="font-bold text-xl md:text-2xl bg-gray-800 rounded-full py-3 px-6 tracking-wider hover:bg-gray-600 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 shadow-lg transition-transform duration-300 ease-in-out"
              @click="videoPlayerStore.mute()">
        MUTE
      </button>

      <button v-if="!videoPlayerStore.paused"
              class="font-bold text-xl md:text-2xl bg-gray-800 rounded-full py-3 px-6 tracking-wider hover:bg-gray-600 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 shadow-lg transition-transform duration-300 ease-in-out"
              @click="videoPlayerStore.pause()">
        PAUSE
      </button>

      <button v-if="videoPlayerStore.paused"
              class="font-bold text-xl md:text-2xl bg-gray-800 rounded-full py-3 px-6 tracking-wider hover:bg-gray-600 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 shadow-lg transition-transform duration-300 ease-in-out"
              @click="videoPlayerStore.play()">
        PLAY
      </button>

    </div>

  </Transition>
</template>

<script setup>
import { computed, inject, onMounted, onUnmounted, ref, watch } from 'vue'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'

const appSettingStore = useAppSettingStore()
import { useChatStore } from '@/Stores/ChatStore'
import { useUserStore } from '@/Stores/UserStore'

const videoPlayerStore = useVideoPlayerStore()
const chatStore = useChatStore()
const userStore = useUserStore()

defineProps({
  show: Boolean,
})

const videoControlsClass = computed(() => ({
  videoControlsWelcomeMobile: userStore.isMobile,
  videoControlsWelcomeStandard: !userStore.isMobile,
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

.fade-out {
  transition: opacity 0.5s ease-in-out;
  opacity: 0;
}

.fade-out.visible {
  opacity: 1;
}

</style>
