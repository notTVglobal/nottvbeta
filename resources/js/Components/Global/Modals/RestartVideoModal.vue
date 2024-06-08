<template>
  <transition name="fade">
    <div v-if="isVisible" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-75 z-999">
      <div class="bg-gray-800 rounded-lg shadow-lg p-6 w-11/12 max-w-md mx-auto">
        <h2 class="text-xl font-semibold mb-4 text-white">Video is already playing</h2>
        <p class="mb-6 text-gray-300">Do you want to restart the video from the beginning?</p>
        <div class="flex justify-end">
          <button @click="cancel" class="bg-gray-600 text-white font-semibold px-4 py-2 mr-2 rounded hover:bg-gray-700">
            Cancel
          </button>
          <button @click="restart" class="bg-green-600 text-white font-semibold px-4 py-2 rounded hover:bg-green-700">
            Restart
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
  isVisible: {
    type: Boolean,
    required: true,
  },
});

const emit = defineEmits(['restart', 'close']);

const cancel = () => {
  emit('close');
};

const restart = () => {
  emit('restart');
};
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}
</style>
