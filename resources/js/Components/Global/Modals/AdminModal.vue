<!-- AdminModal.vue -->
<template>
  <div v-if="isVisible" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50">
    <div class="bg-gray-800 rounded-lg overflow-hidden w-full max-w-lg">
      <div class="flex justify-between items-center p-4 border-b border-gray-700">
        <h2 class="text-xl font-semibold text-gray-100">{{ title }}</h2>
        <button @click="close" class="text-gray-400 hover:text-gray-100">&times;</button>
      </div>
      <div class="p-4 text-gray-100">
        <slot></slot>
      </div>
    </div>
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
