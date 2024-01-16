<template>
  <div>
    <img v-if="!image.folder"
         :src="'/storage/images/' + image.name"
         :alt="alt"
         :class="props.class"/>

    <img v-if="image.folder"
         :src="image.cdn_endpoint + image.cloud_folder + image.folder + '/' + image.name"
         :alt="alt"
         :class="props.class"/>
  </div>
</template>

<script setup>
let props = defineProps({
  image: Object,
  class: String,
  alt: String,
})
</script>
