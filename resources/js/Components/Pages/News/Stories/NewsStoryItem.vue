<template>
  <div class="news-item-card p-4 mb-4 rounded-lg shadow-md hover:shadow-lg transition duration-150 ease-in-out cursor-pointer bg-white">
    <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4">
      <div class="image-container">
        <SingleImage v-if="story.image" :image="story.image" alt="News Story Image" class="h-24 w-24 md:h-32 md:w-32 rounded-lg object-cover"/>
        <div v-else class="h-24 w-24 md:h-32 md:w-32 rounded-lg bg-gray-200 flex items-center justify-center text-gray-500">
          No Image
        </div>
      </div>
      <div class="flex flex-col flex-1">
        <button @click="btnRedirect(`/news/story/${story.slug}`)" class="text-left text-xl font-semibold text-blue-500 hover:text-blue-700 uppercase">
          {{ story.title }}
        </button>
        <div class="text-gray-600 text-sm">
          <span class="uppercase font-semibold">By</span> {{ story.newsPerson.name ? story.newsPerson.name : 'Unknown' }}
        </div>
        <NewsStoryItemCategoryCity :story="story" class="mt-2"/>
      </div>
    </div>
    <div class="flex justify-end mt-4">
      <div v-if="story.status === 'Creators Only'" class="text-gray-700 italic">
        {{ story.status }}
      </div>
      <div v-if="story.published_at" class="flex flex-col justify-end">
        <div class="text-xs uppercase font-semibold text-gray-500">Published</div>
        <div class="text-gray-600">
          <ConvertDateTimeToTimeAgo :dateTime="story.published_at" :timezone="timezone"/>
        </div>
        <div class="text-gray-500">{{ formatDateTimeWithYearFromUtcToUserTimezone(story.published_at) }}</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'
import ConvertDateTimeToTimeAgo from '@/Components/Global/DateTime/ConvertDateTimeToTimeAgo.vue'
import { useUserStore } from '@/Stores/UserStore'
import NewsStoryItemCategoryCity from '@/Components/Pages/News/Stories/NewsStoryItemCategoryCity.vue'

const props = defineProps({
  story: Object
})

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
const timezone = userStore.timezone

const btnRedirect = (url) => {
  appSettingStore.btnRedirect(url)
}

const formatDateTimeWithYearFromUtcToUserTimezone = (dateTime) => {
  return userStore.formatDateTimeWithYearFromUtcToUserTimezone(dateTime)
}
</script>

<style scoped>
.news-item-card {
  background-color: #ffffff; /* White background for the card */
  transition: transform 0.2s ease-in-out;
}

.news-item-card:hover {
  transform: translateY(-5px); /* Slight lift on hover */
}

.text-gray-600 {
  color: #4b5563; /* Text color */
}

.text-gray-500 {
  color: #6b7280; /* Lighter text color */
}

.text-blue-500 {
  color: #3b82f6; /* Blue text color */
}

.text-blue-500:hover {
  color: #2563eb; /* Darker blue text color on hover */
}

.text-gray-700 {
  color: #374151; /* Darker gray text color */
}

.uppercase {
  text-transform: uppercase; /* Uppercase text */
}

.font-semibold {
  font-weight: 600; /* Semi-bold font weight */
}

.rounded-lg {
  border-radius: 0.5rem; /* Large rounded corners */
}

.shadow-md {
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); /* Medium shadow */
}

.hover\:shadow-lg:hover {
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); /* Larger shadow on hover */
}

.image-container {
  width: 6rem; /* 24 pixels for both height and width */
  height: 6rem; /* 24 pixels for both height and width */
  display: flex;
  align-items: center;
  justify-content: center;
}

@media (min-width: 768px) {
  .image-container {
    width: 8rem; /* 32 pixels for both height and width */
    height: 8rem; /* 32 pixels for both height and width */
  }
}

</style>
