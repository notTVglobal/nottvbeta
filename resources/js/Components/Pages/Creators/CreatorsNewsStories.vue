<template>
  <!-- News Stories Section -->
  <div v-if="newsStories && newsStories.length" class="w-full mb-12 py-8">
    <h2 class="text-3xl font-bold pb-8 text-center">News Stories</h2>
    <div class="flex flex-col w-fit mx-auto justify-center items-start max-w-4xl gap-6">
      <div v-if="loading" class="flex w-full justify-center">
        <span class="loading loading-ball text-lg text-info"/>
      </div>
      <div v-else>
        <div v-for="story in newsStories" :key="story.id"
             class="w-full bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
          <div @click="appSettingStore.btnRedirect(`/news/story/${story.slug}`)"
               class="flex gap-4 p-4 hover:bg-gray-100 rounded-lg transition duration-300 cursor-pointer">
            <SingleImage :image="story.image" :alt="`News Story Image`"
                         class="w-28 h-28 rounded-lg object-cover shadow-sm"/>
            <div class="flex flex-col justify-center">
              <p class="text-xl font-semibold text-blue-500 hover:text-blue-400 hover:underline mb-1">{{
                  story.title
                }}</p>
              <p class="text-gray-700 mb-2">{{ story.summary }}</p>
              <p class="text-sm text-gray-500">{{ formatDate(story.published_at) }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'

const appSettingStore = useAppSettingStore()

const props = defineProps({
  creator: Object,
})

const newsStories = ref(null)
const loading = ref(true)

const creatorSlug = props.creator.slug

const fetchNewsStories = async () => {
  try {
    const response = await axios.get(`/creator/${creatorSlug}/news-stories`)
    newsStories.value = response.data
    loading.value = false
  } catch (error) {
    console.error('Error fetching news stories:', error)
    loading.value = false
  }
}

onMounted(() => {
  fetchNewsStories()
})
</script>

<style scoped>
.flex-col > div {
  background-color: #fff; /* White background for news stories */
}

.flex-col > div:hover {
  background-color: #f8fafc; /* Light background on hover */
}

.hover\:shadow-lg:hover {
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); /* Larger shadow on hover */
}

.hover\:bg-gray-100:hover {
  background-color: #f3f4f6; /* Slightly darker light background on hover */
}

.text-blue-500 {
  color: #3b82f6; /* Blue text color */
}

.hover\:text-blue-400:hover {
  color: #60a5fa; /* Lighter blue text color on hover */
}
</style>
