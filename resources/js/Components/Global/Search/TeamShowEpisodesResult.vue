<template>
  <div class="episode-result bg-gray-600 p-3 rounded-lg mb-2">
    <SingleImage :src="result.image" :alt="`Cover image for ${result.show.name}`" />
    <div class="episode-info">
      <h3 class="text-white">{{ result.name }}</h3>
      <p class="text-gray-300">{{ result.description }}</p>
      <p class="text-gray-400">{{ formattedDate }}</p>
      <!-- Time Ago component -->
      <ConvertDateTimeToTimeAgo
          v-if="timeZoneConvertedDateTime"
          :dateTime="timeZoneConvertedDateTime"
          class="text-yellow-400" />
      <Link :href="`shows/${result.show_slug}/episode/${result.slug}`" class="text-blue-400 hover:text-blue-600">Go to Episode</Link>
    </div>
  </div>
</template>

<script setup>
import { computed, defineProps } from 'vue'
import { useUserStore } from '@/Stores/UserStore'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'
import ConvertDateTimeToTimeAgo from '@/Components/Global/DateTime/ConvertDateTimeToTimeAgo.vue'

const userStore = useUserStore()

const props = defineProps({
  result: Object
});

// Computed property to format the release date into the user's timezone
const formattedDate = computed(() => {
  return userStore.formatDateTimeInUserTimezone(props.result.release_dateTime);
});

// Computed property to create a processed date for ConvertDateTimeToTimeAgo
const timeZoneConvertedDateTime = computed(() => {
  return userStore.formatDateTimeInUserTimezone(props.result.release_dateTime);
});

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
</style>
