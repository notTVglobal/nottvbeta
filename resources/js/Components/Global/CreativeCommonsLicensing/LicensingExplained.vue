<template>
  <div>
    <button
        @click.prevent="openImage = true"
        class="btn bg-blue-500 hover:bg-blue-600 text-white">
          Click here to see a visual diagram of the licenses</button>
  <ImageLightboxModal
      v-if="openImage"
      :imageUrl="selectedImageUrl"
      :imageAlt="selectedImageAlt"
      @closed=""/>

  </div>
</template>

<script setup>
import { ref } from 'vue'
import ImageLightboxModal from '@/Components/Global/Modals/ImageLightboxModal.vue'

const selectedImageUrl = ref('https://cdn.nottv.io/public/2024/04/images/g97Pdjt9w8ncgDOcztb46pVNgDTut6EvE8WGkCmV.png');
const selectedImageAlt = ref('Creative Commons explanation diagram');

const openImage = ref(false)

</script>