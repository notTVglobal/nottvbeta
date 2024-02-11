<template>
  <div>
    <img v-if="image && image?.placeholder_url"
         :src="image?.placeholder_url"
         :alt="alt"
         :class="props.class"/>

    <img v-if="image && !image?.folder && !image?.placeholder_url"
         :src="'/storage/images/' + image?.name"
         :alt="alt"
         :class="props.class"/>

    <img v-if="image && image?.folder && !image?.placeholder_url"
         :src="image?.cdn_endpoint + image?.cloud_folder + image?.folder + '/' + image?.name"
         :alt="alt"
         :class="props.class"/>
  </div>
</template>

<script setup>
let props = defineProps({
  image: Object,
  placeholder: String,
  class: String,
  alt: String,
})
</script>
