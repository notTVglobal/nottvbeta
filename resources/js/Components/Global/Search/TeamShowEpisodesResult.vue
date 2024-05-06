<template>
  <div
      class="episode-result bg-gray-600 p-3 rounded-lg mb-2 cursor-pointer hover:bg-gray-800 transition-colors duration-300"
      @click="Inertia.visit(`/shows/${result.show.slug}/episode/${result.slug}`)"
  >
    <p @click.stop="Inertia.visit(`/shows/${result.show.slug}`)" class="font-thin tracking-wider text-white hover:text-blue-400 transition-colors duration-300">{{ result.show.name }}</p>
    <div class="image-wrapper hover:opacity-75 transition-opacity duration-300">
      <SingleImage :image="result.image" :alt="`Cover image for ${result.show.name}`" />
    </div>
    <div class="episode-info">
      <h3 class="text-white hover:text-blue-400 transition-colors duration-300">{{ result.name }}</h3>
      <p class="text-gray-300">{{ truncateText(result.description, 300) }}</p>
      <div class="flex flex-row flex-wrap justify-start gap-2 mt-2">
        <ConvertDateTimeToTimeAgo
            v-if="timeZoneConvertedDateTime"
            :dateTime="timeZoneConvertedDateTime"
            class="text-yellow-400"
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
import { Inertia } from '@inertiajs/inertia'

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
  margin: 0.25rem 0;
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
