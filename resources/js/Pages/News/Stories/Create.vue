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

<!--      <CategoryCitySelector-->
<!--          :country="country"-->
<!--          :categories="categories"-->
<!--          :locationSearch="locationSearch"-->
<!--          :filters="filters"-->
<!--          :form-errors="form.errors"-->
<!--          @searchChanged="handleSearch"-->
<!--          @update:selectedCategory="handleCategoryUpdate"-->
<!--          @update:categoryId="categoryId => { form.news_category_id = categoryId; }"-->
<!--          @update:selectedSubcategory="handleSubcategoryUpdate"-->
<!--          @update:selectedLocation="handleCityUpdate"-->
<!--      />-->
<!--      -->
      <CategoryCitySelector
          :locationSearch="locationSearch"
          :filters="filters"
          :searchPath="`/newsStory/create`"
          @searchChanged="handleSearch"
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
                v-model="form.title"
                name="title"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                placeholder=""
            />
            <div
                v-if="form.errors.title"
                class="text-sm text-red-600"
            >
              {{ form.errors.title }}
            </div>
          </div>
          <div class="mb-6">
            <label
                for="content_json"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300"
            >Content</label>
            <tiptap v-if="appSettingStore.currentPage === 'newsCreate'"/>
            <div
                v-if="form.errors.body"
                class="text-sm text-red-600"
            >
              {{ form.errors.body }}
            </div>
          </div>
          <div class=" flex justify-start">
            <button
                type="submit"
                class="h-fit text-white bg-blue-700  focus:outline-none  font-medium rounded-lg text-sm px-5 py-2.5 "
                :disabled="form.processing"
                :class="{ 'opacity-25': form.processing }"
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
import { onBeforeMount } from 'vue'
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
})

let form = useForm({
  title: '',
  body: '',
  content_json: '{}',
  news_category_id: 0,
  news_category_sub_id: 0,
  city_id: 0,
  province_id: 0,
  federal_electoral_district_id: 0,
  type: '',
})

newsStore.categories = props.categories
newsStore.country = props.country
newsStore.locationSearch = props.locationSearch
newsStore.filters = props.filters

const handleCategoryUpdate = (newCategory) => {
  form.news_category_id = newCategory ? newCategory.id : null
}

const handleSubcategoryUpdate = (newSubcategory) => {
  form.news_category_sub_id = newSubcategory ? newSubcategory.id : null
}

const handleCityUpdate = (newCity) => {
  if (newCity) {
    form.city_id = newCity.city_id
    form.province_id = newCity.id
    form.federal_electoral_district_id = newCity.federal_electoral_district_id
    form.type = newCity.type
  } else {
    form.city_id = null
    form.province_id = null
    form.federal_electoral_district_id = null
    form.type = null
  }
}

const handleSearch = (value) => {
  Inertia.get('/newsStory/create', {search: value}, {
    preserveState: true,
    replace: true,
  })
}

let submit = () => {
  form.body = newsStore.newsArticleContentTiptop
  form.post(route('newsStory.store'))
}

onBeforeMount(() => {
  newsStore.newsArticleContentTiptop = ''
})

</script>
