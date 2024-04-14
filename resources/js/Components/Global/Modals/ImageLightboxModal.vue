<template>
  <div v-if="isOpen" @click="close"  class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center p-4">
    <img :src="imageUrl" @click.stop class="max-w-full max-h-full md:max-w-[60%] object-cover" :alt="imageAlt" />
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  imageUrl: String,
  imageAlt: String,
});

const isOpen = ref(true);

const close = () => {
  isOpen.value = false;
  emit('closed');
};

const emit = defineEmits(['closed']);
</script>

<style>
/* Additional styles if needed */
</style>
