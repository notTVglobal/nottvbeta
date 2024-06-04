<template>
  <div class="mx-auto max-w-7xl px-4">
    <!-- Header Section -->
    <div class="pt-10 flex flex-col md:flex-row md:justify-between md:items-center gap-y-4">
      <h2 class="text-center text-xl md:text-3xl font-semibold">News Stories</h2>
      <button
          v-if="props?.can?.viewNewsroom"
          @click="appSettingStore.btnRedirect(`/newsroom`)"
          class="px-4 py-2 text-white bg-yellow-600 hover:bg-yellow-500 rounded-lg disabled:bg-gray-400"
      >
        Newsroom
      </button>
    </div>

    <!-- Search and Buttons Section -->
    <div class="pt-10 flex flex-col md:flex-row md:justify-between md:items-center gap-y-4">
      <div class="flex justify-center w-full md:w-1/3">
        <div class="relative w-full">
          <input
              v-model="search"
              type="search"
              class="bg-gray-50 text-black text-md rounded-full focus:outline-none focus:shadow w-full pl-10 px-3 py-2"
              placeholder="Search..."
          />
          <div class="absolute top-0 flex items-center h-full ml-3">
            <svg class="fill-current text-gray-400 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
              <path
                  d="M456.69 421.39 362.6 327.3a173.81 173.81 0 0 0 34.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 0 0 327.3 362.6l94.09 94.09a25 25 0 0 0 35.3-35.3ZM97.92 222.72a124.8 124.8 0 1 1 124.8 124.8 124.95 124.95 0 0 1-124.8-124.8Z"
              />
            </svg>
          </div>
        </div>
      </div>
      <div class="flex justify-center w-full md:w-auto gap-4">
        <NewsTipButton/>
        <UploadPressReleaseButton/>
      </div>
    </div>

    <div class="pt-10">
      <div class="flex flex-col space-y-4">
        <div v-for="story in newsStories.data" :key="story.id" class="news-item-container">
          <NewsStoryItem :story="story" />
        </div>
      </div>
    </div>

    <div v-if="newsStories.data.length === 0" class="w-full flex flex-row justify-center text-sm italic my-24">
      No Results. Try again!
    </div>

    <div class="w-full flex justify-center">
      <Pagination :data="newsStories.meta" class=""/>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import Pagination from '@/Components/Global/Paginators/Pagination.vue'
import throttle from 'lodash/throttle'
import { router } from '@inertiajs/vue3'
import NewsTipButton from '@/Components/Global/News/NewsTipButton.vue'
import UploadPressReleaseButton from '@/Components/Global/News/UploadPressReleaseButton.vue'
import NewsStoryItem from '@/Components/Pages/News/Stories/NewsStoryItem.vue'

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()

const props = defineProps({
  newsStories: Object,
  filters: Object,
  can: Object,
})

const sort = (field) => {
  // Add sorting logic here
}

let search = ref(props.filters.search)

watch(search, throttle(function (value) {
  router.get('/news', {search: value}, {
    preserveState: true,
    replace: true,
  })
}, 300))
</script>

<style scoped>
/* Add custom styles or utility classes from Tailwind CSS here */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.news-item-container {
  background-color: #ffffff; /* White background for the card */
  padding: 1rem; /* Padding for spacing */
  border-radius: 0.5rem; /* Rounded corners */
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); /* Medium shadow */
  transition: transform 0.2s ease-in-out;
}

.news-item-container:hover {
  transform: translateY(-5px); /* Slight lift on hover */
}

input[type="search"] {
  padding-right: 2.5rem; /* Ensure padding on the right side */
}

@media (min-width: 768px) {
  .news-item-container {
    padding: 1.5rem; /* Increase padding for medium screens and above */
  }
}

@media (min-width: 1024px) {
  .news-item-container {
    padding: 2rem; /* Increase padding for large screens and above */
  }
}

@media (min-width: 1280px) {
  .news-item-container {
    padding: 2.5rem; /* Increase padding for extra large screens and above */
  }
}
</style>
