<template>
  <div
      class="episode-result bg-gray-600 p-3 rounded-lg mb-2 cursor-pointer hover:bg-gray-800 transition-colors duration-300"
      @click="router.visit(`/shows/${result.show.slug}/episode/${result.slug}`)"
  >
    <p @click.stop="router.visit(`/shows/${result.show.slug}`)" class="text-center font-thin tracking-wider text-white hover:text-blue-400 transition-colors duration-300">{{ truncateText(result.show.name, 50) }}</p>
    <div class="image-wrapper hover:opacity-75 transition-opacity duration-300 mx-auto">
      <SingleImage :image="result.image" :alt="`Cover image for ${result.show.name}`" />
    </div>
    <div class="episode-info">
      <h3 class="text-center text-white hover:text-blue-400 transition-colors duration-300">{{ truncateText(result.name, 50) }}</h3>
      <p class="text-center text-gray-300"><span v-html="truncateText(result.description, 50)"/></p>
      <div class="flex flex-row flex-wrap justify-center gap-2 mt-2">
        <ConvertDateTimeToTimeAgo
            v-if="timeZoneConvertedDateTime"
            :dateTime="timeZoneConvertedDateTime"
            class="text-center text-yellow-400"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, defineProps } from 'vue'
import { useUserStore } from '@/Stores/UserStore'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'
import ConvertDateTimeToTimeAgo from '@/Components/Global/DateTime/ConvertDateTimeToTimeAgo.vue'
import { router } from '@inertiajs/vue3'

const userStore = useUserStore()

const props = defineProps({
  result: Object
});

// Computed property to create a processed date for ConvertDateTimeToTimeAgo
const timeZoneConvertedDateTime = computed(() => {
  return userStore.formatDateTimeInUserTimezone(props.result.releaseDateTime);
});

// Method to truncate text and append ellipsis
function truncateText(text, maxLength) {
  if (text.length > maxLength) {
    return text.substring(0, maxLength) + '...';
  }
  return text;
}

</script>

<style scoped>
/* Add styles for the episode result items */
.episode-result {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}
.episode-info h3 {
  margin: auto;
}
.image-wrapper img {
  transition: transform 0.3s ease;
}
.image-wrapper:hover img {
  transform: scale(1.05);
}
.episode-info h3:hover {
  color: #3182ce; /* Tailwind color blue-500 */
}
</style>
