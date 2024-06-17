<template>
  <Transition name="fade">
    <div v-if="appSettingStore.showCreatorWelcomeModal"
         class="fixed inset-0 flex items-center justify-center z-[999]">
      <!-- Background Gradient -->
      <div class="absolute inset-0 bg-moving-gradient opacity-80"></div>

      <!-- Modal Content -->
      <div class="relative p-8 rounded-lg shadow-lg text-center bg-black bg-opacity-70 backdrop-blur-lg">
        <h1 class="text-5xl font-bold text-white mb-8">Welcome, {{ userStore.user.name }}!</h1>
        <p class="text-3xl text-white mb-12">What would you like to do?</p>
        <div class="flex flex-wrap gap-8 justify-center">
          <button
              @click="goToStream"
              class="px-12 py-8 text-4xl text-white bg-blue-500 hover:bg-blue-600 rounded-lg flex items-center justify-center"
          >
            <span class="text-5xl mr-2">📺</span> <br><br>Watch Stream
          </button>
          <button
              @click="goToDashboard"
              class="px-12 py-8 text-4xl text-white bg-green-500 hover:bg-green-600 rounded-lg flex items-center justify-center"
          >
            <span class="text-5xl mr-2">🛠️</span> <br><br>Go To Your <br>Creator Dashboard
          </button>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { useUserStore } from '@/Stores/UserStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
const videoPlayerStore = useVideoPlayerStore()

import { router } from '@inertiajs/vue3'

const userStore = useUserStore()
const appSettingStore = useAppSettingStore()

const goToStream = () => {
  router.visit('/stream')
  appSettingStore.showCreatorWelcomeModal = false
  videoPlayerStore.unMute()
}

const goToDashboard = () => {
  router.visit('/dashboard')
  appSettingStore.showCreatorWelcomeModal = false
}
</script>

<style scoped>
.bg-moving-gradient {
  background: linear-gradient(270deg, #6a0dad, #ff6347, #1e90ff, #ff4500);
  background-size: 400% 400%;
  animation: gradientShift 15s ease infinite;
}

@keyframes gradientShift {
  0% {
    background-position: 0% 50%;
  }
  100% {
    background-position: 100% 50%;
  }
}

button {
  transition: transform 0.3s ease;
}

button:hover {
  transform: scale(1.05);
}

.backdrop-blur-lg {
  backdrop-filter: blur(10px);
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 1s ease;
}

.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
  opacity: 0;
}

.fixed.inset-0.flex {
  /* Ensure no layout shift during transition */
  position: fixed;
  inset: 0;
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>
