<template>
  <div v-if="show" class="fixed inset-0 flex items-center justify-center z-50">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black opacity-50 z-40"></div>
    <!-- Modal -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden max-w-lg mx-auto z-50">
      <div class="p-6">
        <h2 class="text-2xl font-semibold mb-4">{{ title }}</h2>
        <p class="text-gray-700 dark:text-gray-300 mb-6">{{ message }}</p>
        <div class="flex justify-end space-x-4">
          <button
              @click="cancel"
              class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition"
          >
            Cancel
          </button>
          <button
              @click="confirm"
              class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition"
          >
            Confirm
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
  show: Boolean,
  title: String,
  message: String,
});

const emit = defineEmits(['confirm', 'cancelDelete']);

const confirm = () => emit('confirm');
const cancel = () => emit('cancelDelete');
</script>

<style scoped>
/* Add your styles here */
</style>
