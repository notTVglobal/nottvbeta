<template>
  <div>
    <!-- Placeholder URL Image -->
    <img v-if="image && image?.placeholder_url"
         :src="image?.placeholder_url"
         :alt="alt"
         :class="props.class"
         class="hover:cursor-pointer"
         @click="setImage(image.placeholder_url, alt)" />

    <!-- Storage Image -->
    <img v-if="image && !image?.folder && !image?.placeholder_url"
         :src="'/storage/images/' + image?.name"
         :alt="alt"
         :class="props.class"
         class="hover:cursor-pointer"
         @click="setImage('/storage/images/' + image.name, alt)" />

    <!-- CDN Image -->
    <img v-if="image && image?.folder && !image?.placeholder_url"
         :src="image?.cdn_endpoint + image?.cloud_folder + image?.folder + '/' + image?.name"
         :alt="alt"
         :class="props.class"
         class="hover:cursor-pointer"
         @click="setImage(image.cdn_endpoint + image.cloud_folder + image.folder + '/' + image.name, alt)" />
  </div>
</template>

<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'

let props = defineProps({
  image: Object,
  placeholder: String,
  class: String,
  alt: String,
})

const appSettingStore = useAppSettingStore()

const setImage = (imageUrl, imageAlt) => {
  console.log('click')
  appSettingStore.imageLightboxModal.imageUrl = imageUrl;
  appSettingStore.imageLightboxModal.imageAlt = imageAlt;
  appSettingStore.showImageLightboxModal = true
}
</script>
