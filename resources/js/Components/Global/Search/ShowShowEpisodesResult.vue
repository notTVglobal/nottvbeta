<template>
  <div class="search-result-item bg-gray-700 p-4 rounded-lg mb-2">
    <SingleImage :image="result.image" :alt="result.show.name" />
    <h3 class="text-white">{{ result.name }}</h3>
    <p class="text-gray-400">{{ result.release_dateTime | datetime }}</p>
    <a :href="`/${result.slug}/${result.show_slug}`" class="text-blue-500 hover:text-blue-400">Learn more</a>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'

const props = defineProps({
  result: Object
});

</script>

<style scoped>
/* Add styles specific to search result items here */
</style>
