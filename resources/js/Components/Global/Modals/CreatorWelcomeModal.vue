<template>
  <Transition name="fade">
    <div v-if="appSettingStore.showCreatorWelcomeModal"
         class="fixed inset-0 flex items-center justify-center z-[999]">
      <!-- Background Gradient -->
      <div class="absolute inset-0 bg-moving-gradient opacity-80"></div>

      <!-- Modal Content -->
      <div class="relative p-8 rounded-lg shadow-lg text-center bg-black bg-opacity-70 backdrop-blur-lg" :class="modalClasses">
        <h1 class="font-bold text-white mb-8" :class="titleClasses">Welcome, {{ userStore.user.name }}!</h1>
        <p class="text-white mb-12" :class="subtitleClasses">What would you like to do?</p>
        <div class="flex justify-center" :class="buttonContainerClasses">
          <button
              @click="goToStream"
              class="text-white rounded-lg flex items-center justify-center transition transform duration-300 ease-in-out"
              :class="watchStreamButtonClasses"
          >
            <span :class="iconClasses">📺</span> <br><br>Watch Stream
          </button>
          <button
              @click="goToDashboard"
              class="text-white rounded-lg flex items-center justify-center transition transform duration-300 ease-in-out"
              :class="dashboardButtonClasses"
          >
            <span :class="iconClasses">🛠️</span> <br><br>Go To Your <br>Creator Dashboard
          </button>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { computed } from 'vue';
import { useUserStore } from '@/Stores/UserStore';
import { useAppSettingStore } from '@/Stores/AppSettingStore';
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore';

const videoPlayerStore = useVideoPlayerStore();

import { router } from '@inertiajs/vue3';

const userStore = useUserStore();
const appSettingStore = useAppSettingStore();

const goToStream = () => {
  router.visit('/stream');
  appSettingStore.showCreatorWelcomeModal = false;
  videoPlayerStore.unMute();
};

const goToDashboard = () => {
  router.visit('/dashboard');
  appSettingStore.showCreatorWelcomeModal = false;
};

const modalClasses = computed(() => {
  return appSettingStore.isSmallScreen
      ? 'p-4 max-w-full max-h-full overflow-auto'
      : 'p-8 max-w-4xl max-h-full';
});

const titleClasses = computed(() => {
  return appSettingStore.isSmallScreen
      ? 'text-3xl mb-4'
      : 'text-5xl mb-8';
});

const subtitleClasses = computed(() => {
  return appSettingStore.isSmallScreen
      ? 'text-xl mb-6'
      : 'text-3xl mb-12';
});

const buttonContainerClasses = computed(() => {
  return appSettingStore.isSmallScreen
      ? 'flex-col gap-4'
      : 'flex-row gap-8';
});

const watchStreamButtonClasses = computed(() => {
  return appSettingStore.isSmallScreen
      ? 'px-6 py-4 text-2xl bg-blue-500 hover:bg-blue-600'
      : 'px-12 py-8 text-4xl bg-blue-500 hover:bg-blue-600';
});

const dashboardButtonClasses = computed(() => {
  return appSettingStore.isSmallScreen
      ? 'px-6 py-4 text-2xl bg-green-500 hover:bg-green-600'
      : 'px-12 py-8 text-4xl bg-green-500 hover:bg-green-600';
});

const iconClasses = computed(() => {
  return appSettingStore.isSmallScreen
      ? 'text-3xl mr-2'
      : 'text-5xl mr-2';
});
</script>

<style scoped>
.bg-moving-gradient {
  background: linear-gradient(270deg, #6a0dad, #ff6347, #1e90ff, #ff4500, #6a0dad);
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
