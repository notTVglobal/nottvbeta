<template>

  <Head title="Create News Post"/>

  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <div class="flex flex-row justify-between mt-6">
        <h2 class="text-2xl font-semibold leading-tight">
          Create News Story
        </h2>
        <div class="flex justify-end space-x-2">

        </div>
      </div>

      <CategoryCitySelector
          :locationSearch="locationSearch"
          :filters="filters"
          :searchPath="`/newsStory/create`"
      />

      <div class="p-6 border-b border-gray-200">
        <form @submit.prevent="submit">
          <div class="mb-6">
            <label
                for="Title"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
            >Title</label
            >
            <input
                type="text"
                v-model="newsStore.newsArticleTitleTiptop"
                name="title"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                placeholder=""
            />
            <div
                v-if="errors.title"
                class="text-sm text-red-600"
            >
              {{ errors.title }}
            </div>
          </div>
          <div class="mb-6">
            <label
                for="content_json"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
            >Content</label>
            <tiptap v-if="appSettingStore.currentPage === 'newsCreate'"/>
            <div
                v-if="errors.body"
                class="text-sm text-red-600"
            >
              {{ errors.body }}
            </div>
          </div>
          <div class=" flex justify-start">
            <button
                type="submit"
                class="h-fit text-white bg-blue-700  focus:outline-none  font-medium rounded-lg text-sm px-5 py-2.5 "
                :disabled="processing"
                :class="{ 'opacity-25': processing }"
            >
              Submit
            </button>
            <CancelButton/>
            <JetValidationErrors class="ml-4"/>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'
import { onBeforeMount, onMounted, watch } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useNewsStore } from '@/Stores/NewsStore'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
// import TabbableTextarea from "@/Components/Global/TextEditor/TabbableTextarea"
import Tiptap from '@/Components/Global/TextEditor/TiptapNewsStoryCreate'
// import Tiptap from "@/Components/Global/TextEditor/TiptapNewsStoryEdit"
import CategoryCitySelector from '@/Components/Pages/News/CategoryCitySelector.vue'
import Message from '@/Components/Global/Modals/Messages'
import CancelButton from '@/Components/Global/Buttons/CancelButton.vue'

usePageSetup('newsCreate')

const appSettingStore = useAppSettingStore()
const newsStore = useNewsStore()

let props = defineProps({
  country: Object,
  categories: Object,
  locationSearch: Object,
  filters: Object,
  can: Object,
  errors: Object,
  processing: false,
})

const errors = props.errors; // This will contain the error messages

onMounted(() => {
  // Load props into the store
  newsStore.country = props.country
  newsStore.categories = props.categories
  newsStore.filters = props.filters
  newsStore.locationSearch = props.locationSearch
})

// Watch for changes in relevant store states
watch(() => [newsStore.news_category_id, newsStore.news_category_sub_id], () => {
  newsStore.setSelectedCategory()
  // You can also watch for other relevant states if they affect setSelectedCategory
})
//
// const handleCategoryUpdate = (newCategory) => {
//   form.news_category_id = newCategory ? newCategory.id : null
// }
//
// const handleSubcategoryUpdate = (newSubcategory) => {
//   form.news_category_sub_id = newSubcategory ? newSubcategory.id : null
// }
//
// const handleCityUpdate = (newCity) => {
//   if (newCity) {
//     form.city_id = newCity.city_id
//     form.province_id = newCity.id
//     form.federal_electoral_district_id = newCity.federal_electoral_district_id
//     form.type = newCity.type
//   } else {
//     form.city_id = null
//     form.province_id = null
//     form.federal_electoral_district_id = null
//     form.type = null
//   }
// }

// const handleSearch = (value) => {
//   Inertia.get('/newsStory/create', {search: value}, {
//     preserveState: true,
//     replace: true,
//   })
// }

// let submit = () => {
//   form.body = newsStore.newsArticleContentTiptop
//   form.post(route('newsStory.store'))
// }

const submit = () => {
  props.processing = true
  const data = {
    title: newsStore.newsArticleTitleTiptop,
    body: newsStore.newsArticleContentTiptop,
    // content_json: '{}',
    news_category_id: newsStore.selectedCategory.id,
    news_category_sub_id: newsStore.selectedSubcategory.id,
    city_id: newsStore.selectedLocation.city_id,
    province_id: newsStore.selectedLocation.province_id,
    federal_electoral_district_id: newsStore.selectedLocation.federal_electoral_district_id,
    subnational_electoral_district_id: newsStore.selectedLocation.subnational_electoral_district_id,
    type: newsStore.selectedLocation.type,
    // ... include other relevant properties from the newsStore
  }
  console.log('data submitted.')
  console.log(data)
  Inertia.post(route('newsStory.store'), data)
}


onBeforeMount(() => {
  newsStore.newsArticleContentTiptop = ''
})

</script>
