<template>
  <div>
    <!-- Placeholder URL Image -->
    <img v-if="image && image?.placeholder_url && !placeholder"
         :src="image?.placeholder_url"
         :alt="alt"
         :class="props.class"
         class="hover:cursor-pointer"
         @click="setImage(image.placeholder_url, alt)" />

    <img v-if="placeholder"
         :src="placeholder"
         :alt="alt"
         :class="props.class"
         class="hover:cursor-pointer"
         @click="setImage(placeholder, alt)" />

    <!-- Storage Image -->
    <img v-if="image && !image?.folder && !image?.placeholder_url && !placeholder"
         :src="'/storage/images/' + image?.name"
         :alt="alt"
         :class="props.class"
         class="hover:cursor-pointer"
         @click="setImage('/storage/images/' + image.name, alt)" />

    <!-- CDN Image -->
    <img v-if="image && image?.folder && !image?.placeholder_url && !placeholder"
         :src="image?.cdn_endpoint + image?.cloud_folder + image?.folder + '/' + image?.name"
         :alt="alt"
         :class="props.class"
         class="hover:cursor-pointer"
         @click="setImage(image.cdn_endpoint + image.cloud_folder + image.folder + '/' + image.name, alt)" />
  <div class="flex w-full justify-center italic text-sm" :class="captionClass">
    {{ caption }}
  </div>
  </div>
</template>

<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'

let props = defineProps({
  image: Object,
  placeholder: String,
  class: String,
  alt: String,
  caption: String,
  captionClass: String,
})

const appSettingStore = useAppSettingStore()

const setImage = (imageUrl, imageAlt) => {
  console.log('click')
  appSettingStore.imageLightboxModal.imageUrl = imageUrl;
  appSettingStore.imageLightboxModal.imageAlt = imageAlt;
  appSettingStore.showImageLightboxModal = true
}
</script>
