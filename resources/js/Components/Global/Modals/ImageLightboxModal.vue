<template>
  <div v-if="isOpen" @click="close" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center p-4 z-999">
    <div class="relative max-w-full max-h-full md:max-w-[60%] object-cover">
      <img :src="currentImageUrl" :alt="currentImageAlt" @click.stop class="" />
      <!-- Close Button -->
      <div class="absolute top-0 right-0 -mt-3 -mr-3 w-8 h-8 bg-black rounded-full flex items-center justify-center cursor-pointer z-10" @click="close">
        <span class="text-white text-xl pb-1">&times;</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, watch } from 'vue'
import { useAppSettingStore } from '@/Stores/AppSettingStore'

const appSettingStore = useAppSettingStore()

const props = defineProps({
  imageUrl: String,
  imageAlt: String,
});

const isOpen = ref(true);

const close = () => {
  isOpen.value = false;
  appSettingStore.showImageLightboxModal = false
  appSettingStore.imageLightboxModal.imageUrl = ''
  appSettingStore.imageLightboxModal.imageAlt = ''
  emit('closed');
};

const emit = defineEmits(['closed']);

// Computed properties to default to store values if props are not provided
const currentImageUrl = computed(() => {
  return props.imageUrl || appSettingStore.imageLightboxModal.imageUrl;
});

const currentImageAlt = computed(() => {
  return props.imageAlt || appSettingStore.imageLightboxModal.imageAlt;
});

// Watch for changes in the store
watch(() => appSettingStore.imageLightboxModal, (newVal) => {
  // This will react whenever the store's imageLightboxModal object changes
  // You can use newVal to do something if necessary
  console.log('Store imageLightboxModal changed:', newVal);
}, { deep: true });
</script>

<style>
/* Additional styles if needed */
</style>
