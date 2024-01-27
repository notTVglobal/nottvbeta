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
      <!--      <CategoryCitySelector-->
      <!--          :newsStory="newsStory"-->
      <!--          :country="country"-->
      <!--          :categories="categories"-->
      <!--          :locationSearch="locationSearch"-->
      <!--          :filters="filters"-->
      <!--          :form-errors="form.errors"-->
      <!--          @searchChanged="handleSearch"-->
      <!--          @update:selectedCategory="handleCategoryUpdate"-->
      <!--          @update:selectedSubcategory="handleSubcategoryUpdate"-->
      <!--          @update:selectedLocation="handleCityUpdate"-->
      <!--      />-->

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
            <tiptap v-if="appSettingStore.currentPage === 'newsEdit'" />
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
import { usePage } from '@inertiajs/inertia-vue3';
import { computed, onBeforeUnmount, onMounted, watch } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useNewsStore } from '@/Stores/NewsStore'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
// import TabbableTextarea from "@/Components/Global/TextEditor/TabbableTextarea.vue"
import Tiptap from '@/Components/Global/TextEditor/TiptapNewsStoryEdit'
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



//
//
// newsStore.setSelectedCategory()
onMounted(() => {
  // Load props into the store
  newsStore.newsArticleIdTiptop = props.newsStory.id
  newsStore.newsArticleTitleTiptop = props.newsStory.title
  newsStore.newsArticleContentTiptop = props.newsStory.content
  // newsStore.content_json = props.newsStory.content_json
  newsStore.news_category_id = props.newsStory.news_category_id
  newsStore.news_category_sub_id = props.newsStory.news_category_sub_id
  newsStore.city_id = props.newsStory.city_id
  newsStore.province_id = props.newsStory.province_id
  newsStore.federal_electoral_district_id = props.newsStory.federal_electoral_district_id
  newsStore.subnational_electoral_district_id = props.newsStory.subnational_electoral_district_id
  newsStore.type = props.newsStory.type
  newsStore.country = props.country
  newsStore.categories = props.categories
  // subcategories are retrieved from the newsStore
  newsStore.filters = props.filters
  // search needs to be fixed
  // displayText is processed in the newsStore
  // formErrors need to be tested
  // newsStore.newsStory = props.newsStory   // newsStory can be loaded through a function or done like we are here.
  // selected Category is processed in the newsStore
  // selected Subcategory is processed in the newsStore
  // selected Location is processed in the newsStore
  newsStore.locationSearch = props.locationSearch
  // citySelectDropdownVisible defaults to false
  // focusedIndex defaults to 0
  // searchQuery defaults to null

  newsStore.loadNewsStory(props.newsStory)

  // Initial call to setSelectedCategory
  // newsStore.setSelectedCategory();
})

// Watch for changes in relevant store states
watch(() => [newsStore.news_category_id, newsStore.news_category_sub_id], () => {
  newsStore.setSelectedCategory()
  // You can also watch for other relevant states if they affect setSelectedCategory
})

// const form = useForm({
//   content: newsStore.newsArticleContentTiptop,
//   content_json: newsStore.content_json,
// })
// Define a computed property that derives its value from the Pinia store's state
// const selectedCategory = computed(() => newsStore.selectedCategory);


// const setSelectedCategory = () => {
//   // set the newsStore.selectedCategory where
//   // newsStory.news_category_id = the
//   // category id in the
//   // newsStore.categories array
//
//   // set the newsStore.selectedCategory
//   // to the newsStore.category
//   //
// }

// form.body = newsStore.newsArticleContentTiptop
// form.content = newsStore.newsArticleContentTiptop;

// note: as of March 2023 the form submission cannot use "content" as
// a form field name. It conflicts with the HTMLRequest content item.
// Our news post content is called content in the database, but it is
// now called body in our form submission back to Laravel.
//
// const form = useForm({
//   id: newsStore.newsStory.id,
//   title: newsStore.newsArticleTitleTiptop,
//   body: newsStore.newsArticleContentTiptop,
//   // content: newsStore.newsArticleContentTiptop,
//   content_json: '{}',
//   news_category_id: newsStore.selectedCategory.id,
//   news_category_sub_id: newsStore.selectedSubcategory.id,
//   city_id: newsStore.selectedLocation.city_id,
//   province_id: newsStore.selectedLocation.province_id,
//   federal_electoral_district_id: newsStore.selectedLocation.federal_electoral_district_id,
//   type: newsStore.selectedLocation.type,
// })

// const submit = () => {
//   console.log(form)
//   form.patch(route('newsStory.update', newsStore.newsStory.slug));
// }

// Watch for changes in newsStore and update form fields accordingly
// watch(() => newsStore.newsStory, (newNewsStory) => {
//   Object.keys(newNewsStory).forEach(key => {
//     if (form[key] !== undefined) {
//       form[key] = newNewsStory[key];
//     }
//   });
// }, { deep: true });

// const submit = () => {
//   console.log('Form data at submission:', form);
//   form.patch(route('newsStory.update', newsStore.newsStory.slug))
//       .then(response => {
//         // Handle successful response
//       })
//       .catch(error => {
//         // Handle error
//       });
// }

const submit = () => {
  props.processing = true
  const data = {
    id: newsStore.newsStory.id,
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
  Inertia.patch(route('newsStory.update', newsStore.newsStory.slug), data)
}

// form.id = newsStore.newsStory.id
// form.title = newsStore.newsArticleTitleTiptop
// form.body = newsStore.newsArticleContentTiptop
// form.news_category_id = newsStore.selectedCategory.id
// form.news_category_sub_id = newsStore.selectedSubcategory.id
// form.patch(route('newsStory.update', newsStore.newsStory))
// Use the route helper to generate the URL, passing the newsStory id
// const updateUrl = route('newsStory.update', { newsStory: newsStore.newsStory.id });

// Now, make the PATCH request to the updateUrl with formData
// form.patch(updateUrl, formData);


// form.city_id = newsStore.city_id
// form.province_id = newsStore.province_id
// form.federal_electoral_district_id = newsStore.federal_electoral_district_id
// form.type = newsStore.type



onBeforeUnmount(() => {
  newsStore.newsArticleContentTiptop = ''
  newsStore.reset()
})


// If the event emits just the category ID
// const handleCategoryUpdate = (categoryId) => {
//   form.news_category_id = categoryId;
//   console.log('NEWWW:' + form.news_category_id)
// };

// const handleSubcategoryUpdate = (newSubcategory) => {
//   form.news_category_sub_id = newSubcategory ? newSubcategory.id : null
// }
// If the event emits just the category_sub ID
// const handleSubcategoryUpdate = (categorySubId) => {
//   form.news_category_sub_id = categorySubId;
// };

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
//   Inertia.get(`/newsStory/${props.newsStory.slug}/edit`, {search: value}, {
//     preserveState: true,
//     replace: true,
//   })
// }

</script>
