<template>
  <div>
    <button
        @click.prevent="openImage"
        class="btn bg-blue-500 hover:bg-blue-600 text-white">
          Click here to see a visual diagram of the licenses</button>

  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAppSettingStore } from '@/Stores/AppSettingStore'

const appSettingStore = useAppSettingStore()

const openImage = () => {
  appSettingStore.imageLightboxModal.imageUrl = ref('https://cdn.nottv.io/public/2024/04/images/g97Pdjt9w8ncgDOcztb46pVNgDTut6EvE8WGkCmV.png');
  appSettingStore.imageLightboxModal.imageAlt = ref('Creative Commons explanation diagram');
  appSettingStore.showImageLightboxModal = true
}

</script>