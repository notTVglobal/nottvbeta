<template>
  <div v-if="show" class="hand-animation-container">
    <span class="hand-emoji">{{ handDirection === 'right' ? '👉' : '👈' }}</span>
    <span class="click-me">Click Me!</span>
  </div>
</template>

<script setup>
import { ref, watchEffect, computed } from 'vue';
import { useAppSettingStore } from '@/Stores/AppSettingStore';

const show = ref(false);
const appSettingStore = useAppSettingStore();

// Define a prop to control the direction of the hand
const props = defineProps({
  direction: {
    type: String,
    default: 'right', // Default direction is 'right'
  },
});

const handDirection = computed(() => props.direction);

// Watch for changes to the currentPage and trigger the animation
watchEffect(() => {
  if (appSettingStore.currentPage === 'stream') {
    show.value = true;
    setTimeout(() => {
      show.value = false;
    }, 3000); // Hide after 3 seconds
  }
});
</script>

<style scoped>
.hand-animation-container {
  position: fixed;
  bottom: 100px;
  left: 10px; /* Start closer to the left side of the screen */
  z-index: 1000;
  display: flex;
  flex-direction: column;
  align-items: center;
  animation: fly-in 3s ease forwards;
}

.hand-emoji {
  font-size: 2rem;
  animation: fly-hand 3s ease forwards;
}

.click-me {
  font-size: 1.5rem;
  margin-top: 10px;
  animation: fade-in 3s ease forwards;
}

@keyframes fly-in {
  0% {
    transform: translateX(-10%) translateY(0%); /* Start closer to the left edge */
    opacity: 0;
  }
  50% {
    transform: translateX(50%) translateY(-20%) scale(1.5); /* Move towards the center with a slight upward curve */
    opacity: 1;
  }
  100% {
    transform: translateX(90%) translateY(-40%) scale(0.5); /* Move towards the right side with an upward curve */
    opacity: 0;
  }
}

@keyframes fly-hand {
  0% {
    transform: translateX(-10%) translateY(0%); /* Start closer to the left edge */
    opacity: 0;
  }
  50% {
    transform: translateX(50%) translateY(-20%) scale(1.5); /* Move towards the center with a slight upward curve */
    opacity: 1;
  }
  100% {
    transform: translateX(90%) translateY(-40%) scale(0.5); /* Move towards the right side with an upward curve */
    opacity: 0;
  }
}

@keyframes fade-in {
  0% {
    opacity: 0;
  }
  50% {
    opacity: 1;
  }
  100% {
    opacity: 0;
  }
}
</style>
