<template>

  <Head title="Edit News Post"/>

  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <div class="flex flex-row justify-between mt-6">
        <h2 class="text-xl font-semibold leading-tight">
          Edit News Post
        </h2>
        <div class="flex justify-end space-x-2">
          <div>
            <button
                v-if="props.can.viewNewsroom"
                @click="appSettingStore.btnRedirect(`/newsroom`)"
                class="px-4 py-2 text-white bg-yellow-600 hover:bg-yellow-500 rounded-lg disabled:bg-gray-400"

            >Newsroom
            </button>
          </div>
          <div>
            <CancelButton/>
          </div>
        </div>
      </div>

      <CategoryCitySelector
          :newsStory="newsStory"
          :locationSearch="locationSearch"
          :filters="filters"
          :searchPath="`/newsStory/${newsStory.slug}/edit`"
      />

      <div class="p-6 border-b border-gray-200">
        <form @submit.prevent="submit">
          <div class="mb-6">
            <label
                for="Title"
                class="block mb-2 text-sm font-bold text-gray-900 dark:text-gray-300"
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
                for="slug"
                class="block mb-2 text-sm font-bold text-gray-900 dark:text-gray-300"
            >Content</label>
            <TipTapEditor v-if="appSettingStore.currentPage === 'newsEdit'" />
            <!--                        <tabbable-textarea-->
            <!--                            type="text"-->
            <!--                            v-model="form.content"-->
            <!--                            name="content"-->
            <!--                            id=""-->
            <!--                            rows=10-->
            <!--                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"-->
            <!--                        ></tabbable-textarea>-->

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
                class="text-white bg-blue-700 hover:bg-blue-500 focus:outline-none  font-medium rounded-lg text-sm px-5 py-2.5 "
                :disabled="processing"
                :class="{ 'opacity-25': processing }"
            >
              Save
            </button>
            <JetValidationErrors class="ml-4"/>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'
import { computed, onBeforeUnmount, onMounted, watch } from 'vue'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useNewsStore } from '@/Stores/NewsStore'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import TipTapEditor from '@/Components/Global/TextEditor/TipTapEditor'
import CategoryCitySelector from '@/Components/Pages/News/CategoryCitySelector.vue'
import Message from '@/Components/Global/Modals/Messages'
import CancelButton from '@/Components/Global/Buttons/CancelButton.vue'

usePageSetup('newsEdit')

const appSettingStore = useAppSettingStore()
const newsStore = useNewsStore()

const props = defineProps({
  newsStory: Object,
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
  newsStore.newsArticleIdTiptop = props.newsStory.id
  newsStore.newsArticleTitleTiptop = props.newsStory.title
  newsStore.newsArticleContentTiptop = JSON.parse(props.newsStory.content_json)
  newsStore.content_json = JSON.parse(props.newsStory.content_json)
  newsStore.news_category_id = props.newsStory.news_category_id
  newsStore.news_category_sub_id = props.newsStory.news_category_sub_id
  newsStore.city_id = props.newsStory.city_id
  newsStore.province_id = props.newsStory.province_id
  newsStore.federal_electoral_district_id = props.newsStory.federal_electoral_district_id
  newsStore.subnational_electoral_district_id = props.newsStory.subnational_electoral_district_id
  newsStore.type = props.newsStory.type
  newsStore.country = props.country
  newsStore.categories = props.categories
  newsStore.filters = props.filters
  newsStore.locationSearch = props.locationSearch
  newsStore.loadNewsStory(props.newsStory)
})

// Watch for changes in relevant store states
watch(() => [newsStore.news_category_id, newsStore.news_category_sub_id], () => {
  newsStore.setSelectedCategory()
  // You can also watch for other relevant states if they affect setSelectedCategory
})

const submit = () => {
  props.processing = true

  // Check if selectedSubcategory.id is null, and if so, set it to a default value or handle it as needed
  const subcategoryId = newsStore.selectedSubcategory ? newsStore.selectedSubcategory.id : null;

  const data = {
    id: newsStore.newsStory.id,
    title: newsStore.newsArticleTitleTiptop,
    // body: JSON.stringify(newsStore.content_json),
    content_json: JSON.stringify(newsStore.newsArticleContentTiptop),
    news_category_id: newsStore.selectedCategory.id,
    news_category_sub_id: subcategoryId, // Use the value after handling null
    city_id: newsStore.selectedLocation.city_id,
    province_id: newsStore.selectedLocation.province_id,
    federal_electoral_district_id: newsStore.selectedLocation.federal_electoral_district_id,
    subnational_electoral_district_id: newsStore.selectedLocation.subnational_electoral_district_id,
    type: newsStore.selectedLocation.type,
  }

  console.log('data submitted.')
  Inertia.patch(route('newsStory.update', newsStore.newsStory.slug), data)
}

onBeforeUnmount(() => {
  newsStore.reset()
})
</script>
