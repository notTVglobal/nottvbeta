<template>
  <div class="bg-gray-900 p-5 xl:px-10">
    <!-- Container for image and text sections -->
    <div class="flex flex-col mx-auto px-10 xl:mx-0 xl:flex-row justify-start items-start gap-4">
      <!-- Image Section -->
      <div class="flex-shrink-0 max-w-xs sm:max-w-md md:max-w-lg xl:max-w-xl mb-4 xl:mb-0 mx-auto">
        <Link :href="`/shows/${show.slug}/`">
          <SingleImage
              :image="show.image"
              :alt="`Show Poster`"
              :class="`w-full h-auto max-h-96 object-contain hover:opacity-80 transition-opacity duration-300`"
          />
        </Link>
      </div>

      <!-- Title Card and Category Section -->
      <div class="flex flex-col text-left w-full xl:flex-1 p-4 mb-4">
        <div class="flex flex-col items-center xl:items-start text-center xl:text-left bg-gray-800 text-white shadow-lg rounded-lg w-full xl:w-auto p-4">
          <!-- Episode Name -->
          <h3 class="text-2xl sm:text-3xl font-semibold mb-2">{{ episode.name }}</h3>
          <!-- Show Name -->
          <div class="text-lg sm:text-xl font-semibold text-blue-500 hover:text-blue-700 mb-2">
            <Link :href="`/shows/${show.slug}/`">{{ show.name }}</Link>
          </div>
          <!-- Team Name -->
          <Link :href="`/teams/${team.slug}`" class="text-blue-300 hover:text-blue-500 mb-2">
            <span class="text-xs sm:text-sm uppercase font-semibold">{{ team.name }}</span>
          </Link>
          <!-- Release Date -->
          <div class="text-yellow-500 mb-2" v-if="episode.release_dateTime">
            {{ userStore.formatDateInUserTimezone(episode.release_dateTime, 'MMMM DD, YYYY') }}
          </div>
          <!-- Episode Number or ID -->
          <div class="text-gray-500 mb-2" v-if="!episode.episode_number">Episode {{ episode.id }}</div>
          <div class="text-gray-500 mb-2" v-if="episode.episode_number">Episode {{ episode.episode_number }}</div>
          <!-- Convert DateTime to Time Ago -->
          <ConvertDateTimeToTimeAgo
              v-if="episode.scheduled_release_dateTime"
              :dateTime="episode.scheduled_release_dateTime"
              :class="`text-green-400 mt-2`"
          />
        </div>

        <!-- Category and Subcategory Section -->
        <div class="flex flex-col mt-4 p-4 items-center xl:items-start text-center xl:text-left w-full">
          <span class="text-lg uppercase tracking-wider text-yellow-700">{{ show?.category?.name }}</span>
          <span class="text-sm tracking-wide text-yellow-500">{{ show?.subCategory?.name }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useUserStore } from '@/Stores/UserStore'
import ConvertDateTimeToTimeAgo from '@/Components/Global/DateTime/ConvertDateTimeToTimeAgo.vue'
import SingleImageWithModal from '@/Components/Global/Multimedia/SingleImageWithModal.vue'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'

const userStore = useUserStore()

const props = defineProps({
  show: Object,
  episode: Object,
  team: Object
})

</script>

<style scoped>
/* Shadow effect for the title card */
.shadow-lg {
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Ensure minimal spacing between image and title card and image and left side */
.bg-gray-900 {
  padding: 0; /* Remove padding on all sides */
}


.gap-2 {
  gap: 0.5rem; /* Minimal spacing between the image and title card */
}
</style>