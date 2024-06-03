<template>
  <div class="pt-8 max-w-7xl pb-24">
    <div class="flex flex-wrap items-center justify-between mx-auto pb-10">
      <h1 class="text-4xl font-bold mb-6">{{ newsCategory.name }}</h1>
      <button v-if="newsStore.selectedSubCategoryId" @click="resetFilter"
              class="bg-gray-800 tracking-wide text-gray-300 p-4 rounded-lg shadow-md cursor-pointer block border-b-2 border-transparent font-medium leading-5 hover:text-white hover:bg-indigo-400 hover:border-indigo-400 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
        See All Latest Stories
      </button>
      <BackButton/>
    </div>



    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
      <!-- Subcategories Section -->
      <div class="xl:col-span-1">
        <h2 class="text-2xl font-semibold mb-4">Subcategories</h2>
        <div class="block xl:hidden mb-4">
          <select v-model="newsStore.selectedSubCategoryId" @change="handleSelectChange"
                  class="w-full p-3 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Select a subcategory...</option>
            <option v-for="subCategory in newsSubCategories" :key="subCategory.id" :value="subCategory.id">
              {{ subCategory.name }}
            </option>
          </select>
        </div>
        <ul class="hidden xl:block space-y-2">
          <li v-for="subCategory in newsSubCategories" :key="subCategory.id"
              @click="handleSubCategoryClick(subCategory.id, subCategory.name, subCategory.description)"
              :class="computedClasses(subCategory)">
            {{ subCategory.name }}
          </li>
        </ul>
      </div>

      <!-- Latest Stories Section -->
      <div class="xl:col-span-2 min-w-full">
        <div class="flex flex-wrap justify-between items-center mb-4 gap-3">
          <h2 class="text-2xl font-semibold">{{ newsStore.selectedSubCategoryName || 'Latest Stories' }}</h2>


          <div v-if="newsStore.selectedSubCategoryDescription" class="bg-blue-100 p-4 rounded-lg shadow-inner mb-6">
            <p class="text-blue-900">{{ newsStore.selectedSubCategoryDescription }}</p>
          </div>

        </div>

        <div v-if="newsStore.filteredNewsStories.length" class="space-y-6">
          <div v-for="newsStory in newsStore.filteredNewsStories" :key="newsStory.id"
               @click.prevent="appSettingStore.btnRedirect(`/news/story/${newsStory.slug}`)"
               class="relative flex flex-col lg:flex-row bg-white p-4 rounded-lg shadow-md news-card">

            <div class="news-card-image lg:w-1/4 mb-4 lg:mb-0">
              <SingleImage :image="newsStory.image" :alt="newsStory.name"
                           class="w-full max-h-36 rounded-lg object-cover"/>
            </div>
            <div class="lg:w-3/4 lg:pl-4">
              <h3 class="text-xl font-bold mb-2">{{ newsStory.title }}</h3>
              <p class="text-gray-600 mb-2">{{ newsStory.summary }}</p>
              <p v-if="newsStory.subCategory" class="text-gray-500 text-sm mb-2">{{ newsStory.subCategory.name }}</p>
              <ExpandableNewsStoryItem :content="newsStory.content" :hideButton="true"/>
            </div>
            <div class="absolute bottom-4 left-4 text-gray-500 text-sm">
              <ConvertDateTimeToTimeAgo :dateTime="newsStory.published_at" :timezone="userStore.timezone"/>
            </div>
            <div class="absolute bottom-4 right-4 text-gray-500 text-sm">
              <span v-if="newsStory.city">{{ newsStory.city.name }}</span>
              <span v-if="newsStory.city && newsStory.province">, </span>
              <span v-if="newsStory.province">{{ newsStory.province.name }}</span>
              <span v-if="newsStory.federalElectoralDistrict">{{ newsStory.federalElectoralDistrict.name }}</span>
              <span v-else-if="newsStory.subnationalElectoralDistrict">{{
                  newsStory.subnationalElectoralDistrict.name
                }}</span>
            </div>

          </div>
          <button v-if="currentPage < totalPages" @click="loadMoreStories"
                  class="text-sm text-blue-500 hover:underline">Load More Stories
          </button>
        </div>
        <div v-else class="min-w-full flex flex-col items-center justify-center gap-8">
          <p class="text-gray-800 text-lg p-6 bg-gray-200 rounded-lg shadow-md text-center">
            📭 No news stories found for this category.
          </p>
          <NewsTipButton />
        </div>
      </div>
    </div>
  </div>
</template>


<script setup>
import { nextTick, onBeforeUnmount, onMounted, ref } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { useUserStore } from '@/Stores/UserStore'
import { useNewsStore } from '@/Stores/NewsStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'

import Pagination from '@/Components/Global/Paginators/Pagination.vue'
import ExpandableNewsStoryItem from '@/Components/Global/Text/ExpandableNewsStoryItem.vue'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'
import ConvertDateTimeToTimeAgo from '@/Components/Global/DateTime/ConvertDateTimeToTimeAgo.vue'
import NewsTipButton from '@/Components/Global/News/NewsTipButton.vue'
import BackButton from '@/Components/Global/Buttons/BackButton.vue'

const userStore = useUserStore()
const newsStore = useNewsStore()
const appSettingStore = useAppSettingStore()

const page = usePage().props

const props = defineProps({
  newsCategory: Object,
  newsSubCategories: Array,
  newsStories: Array,
  newsCategoryId: Number,
})

const {
  latestStories,
  newsStories: storeNewsStories,
  currentPage,
  totalPages,
  selectedSubCategory,
  selectedSubCategoryName,
  filteredNewsStories,
  filterStoriesBySubCategory,
  resetFilter,
  updateSelectedCategory,
  initializeLatestStories,
  fetchNewsStories,
} = newsStore

onMounted(() => {
  newsStore.reset()
  initializeLatestStories(page.newsStories, props.newsCategoryId, null)
})

onBeforeUnmount(() => {
  newsStore.reset()
})



const handleSelectChange = (event) => {
  const subCategoryId = Number(event.target.value)
  const selectedOption = props.newsSubCategories.find(subCategory => subCategory.id === subCategoryId)
  const subCategoryName = selectedOption ? selectedOption.name : ''
  const subCategoryDescription = selectedOption ? selectedOption.description : ''
  filterStoriesBySubCategory(subCategoryId, subCategoryName, subCategoryDescription)
  scrollToTop()
}


const handleSubCategoryClick = (subCategoryId, subCategoryName, subCategoryDescription) => {
  filterStoriesBySubCategory(subCategoryId, subCategoryName, subCategoryDescription)
  scrollToTop()
}

const loadMoreStories = () => {
  if (currentPage < totalPages) {
    fetchNewsStories(newsStore.selectedCategory, selectedSubCategory, currentPage + 1)
  }
}

const computedClasses = (subCategory) => {
  const isActive = newsStore.selectedSubCategoryId === subCategory.id
  return isActive
      ? 'bg-indigo-400 tracking-wide text-white p-4 rounded-lg shadow-md cursor-pointer block border-b-2 border-indigo-400 font-medium leading-5 focus:outline-none transition'
      : 'bg-gray-800 tracking-wide text-gray-300 p-4 rounded-lg shadow-md cursor-pointer block border-b-2 border-transparent font-medium leading-5 hover:text-white hover:bg-indigo-400 hover:border-indigo-400 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition'
}

// Function to handle scrolling
const scrollToTop = () => {
  nextTick(() => {
    const topDiv = document.getElementById('topDiv')
    if (topDiv) {
      // Smooth scroll to the 'topDiv' element
      topDiv.scrollIntoView({ behavior: 'smooth' })
    } else {
      // Fallback: smooth scroll to the top of the page
      window.scrollTo({ top: 0, behavior: 'smooth' })
    }
  })
}

</script>

<style scoped>
.news-card:hover {
  transform: translateY(-5px);
  transition: transform 0.2s;
  background-color: #f3f4f6; /* Light gray background on hover */
  cursor: pointer; /* Add cursor pointer on hover */
}

.news-card {
  transition: transform 0.2s, background-color 0.2s;
  position: relative; /* Ensure the position is relative for absolute positioning inside */
  padding-bottom: 2rem; /* Add padding to the bottom to ensure space for the date */
}

.news-card img {
  max-height: 9rem; /* Adjusted max height to 36 to leave room for the date */
  transition: transform 0.2s;
}

.news-card:hover img {
  transform: scale(1.05); /* Slightly enlarge the image on hover */
}

.min-w-full {
  min-width: 100%;
}
</style>


