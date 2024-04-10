<template>
  <transition name="welcome-animation" @after-leave="$emit('completed')">
    <div v-if="showWelcome" class="welcome-message">
      Welcome!
    </div>
  </transition>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const showWelcome = ref(true);

onMounted(() => {
  setTimeout(() => {
    showWelcome.value = false;
  }, 500); // Display the welcome message for half a second
});
</script>

<style>
.welcome-message {
  font-size: 3rem;
  font-weight: bold;
  color: #FFD700; /* Gold */
  text-shadow: 0 0 10px #FF8C00, 0 0 20px #FF8C00; /* Deep orange glow */
  animation: popIn 0.5s ease-out forwards;
  text-align: center;
  opacity: 0;
}

@keyframes popIn {
  0% {
    transform: scale(0);
    opacity: 0;
  }
  50% {
    transform: scale(1.2);
    opacity: 1;
  }
  100% {
    transform: scale(1);
    opacity: 1;
  }
}

/* Welcome Animation */
.welcome-animation-enter-active, .welcome-animation-leave-active {
  transition: opacity 0.5s;
}
.welcome-animation-enter, .welcome-animation-leave-to {
  opacity: 0;
}
</style>
