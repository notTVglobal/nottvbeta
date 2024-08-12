<!-- AdminModal.vue -->
<template>
  <div v-if="isVisible">
    <!-- Background overlay stays in the original DOM structure -->
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75" style="z-index: 1000;"></div>

    <!-- Modal content is teleported to the body with a high z-index -->
    <teleport to="body">
      <div
          class="fixed top-1/2 left-1/2 bg-gray-800 rounded-lg overflow-hidden w-full max-w-lg transform -translate-x-1/2 -translate-y-1/2"
          style="z-index: 9999;"
      >
        <div class="flex justify-between items-center p-4 border-b border-gray-700">
          <h2 class="text-xl font-semibold text-gray-100">{{ title }}</h2>
          <button @click="close" class="text-gray-400 hover:text-gray-100">&times;</button>
        </div>
        <div class="p-4 text-gray-100">
          <slot></slot>
        </div>
      </div>
    </teleport>
  </div>
</template>

<script setup>
import { ref, defineProps, defineEmits } from 'vue';

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  isVisible: {
    type: Boolean,
    required: true
  }
});

const emit = defineEmits(['close']);

const close = () => {
  emit('close');
};
</script>

<style scoped>
/* Add any additional styles you need here */
</style>
