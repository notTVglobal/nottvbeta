<template>
  <div v-if="isOpen" @click="close" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center p-4 z-999">
    <div class="relative w-full h-full max-w-[80vw] max-h-[80vh] flex items-center justify-center p-4">
      <!-- Image Container -->
      <div class="relative flex items-center justify-center p-4">
        <img
            :src="currentImageUrl"
            :alt="currentImageAlt"
            @click.stop
            class="object-contain w-full h-full max-w-[80vw] max-h-[80vh]"
        />
        <!-- Close Button -->
        <div
            class="absolute top-0 right-0 mt-2 mr-2 w-8 h-8 bg-black rounded-full flex items-center justify-center cursor-pointer z-10 border-white border-2"
            @click="close"
        >
          <span class="text-white text-xl pb-1 font-semibold">&times;</span>
        </div>
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
