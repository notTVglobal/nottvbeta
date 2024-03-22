<template>

  <Head title="Edit News Post"/>

  <div class="place-self-center flex flex-col gap-y-3">
    <div v-if="isLoading" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">
      <span class="loading loading-spinner text-info"></span>
      <span class="ml-4">Loading... please wait.</span>
      </div>
    <div v-else id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

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
          <div class="">
            <button
                @click.prevent="submit"
                class="text-white bg-blue-700 hover:bg-blue-500 focus:outline-none  font-medium rounded-lg text-sm px-5 py-2.5 "
                :disabled="processing"
                :class="{ 'opacity-25': processing }"
            >
              Save
            </button>
          </div>
          <div>
            <CancelButton/>
          </div>

        </div>
        <JetValidationErrors class="ml-4"/>
      </div>

      <div class="flex flex-col py-4 px-6">
        <div class="font-semibold text-xs uppercase mb-2">Select News Person as author</div>
        <v-select
            :options="newsStore.newsPersons.map(person => ({ label: person.user.name, value: person.id }))"
            v-model="selectedNewsPerson"
            @change="onSelect"
            placeholder="Select News Person"
            class="py-2"
        ></v-select>
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
          <div class=" flex justify-end">
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
import { computed, nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useNewsStore } from '@/Stores/NewsStore'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import TipTapEditor from '@/Components/Global/TextEditor/TipTapEditor'
import CategoryCitySelector from '@/Components/Pages/News/CategoryCitySelector.vue'
import Message from '@/Components/Global/Modals/Messages'
import CancelButton from '@/Components/Global/Buttons/CancelButton.vue'
import 'vue-select/dist/vue-select.css'; // Import the CSS
import vSelect from 'vue-select';

usePageSetup('newsEdit')

const appSettingStore = useAppSettingStore()
const newsStore = useNewsStore()
const isLoading = ref(true);

const selectedNewsPerson = ref(null);

const props = defineProps({
  newsStory: Object,
  country: Object,
  categories: Object,
  locationSearch: Object,
  filters: Object,
  can: Object,
  errors: Object,
})

const processing = ref(false)
const errors = props.errors; // This will contain the error messages

onMounted(async () => {
  // Start with isLoading true
  isLoading.value = true;

  // Reset store state or perform any initial setup
  newsStore.reset();

  // Fetch all required data. Assume yourStore.fetchNewsPersons() is a duplication and remove it.
  await Promise.all([
    newsStore.fetchNewsPersons(), // Assume this fetches and populates newsStore.newsPersons
    // yourStore.fetchAnotherPieceOfData(), // Example: Uncomment and adjust according to your actual data needs
    // Other data fetching promises can be added here
  ]);

  // Data post-processing after all fetches
  const newsPerson = newsStore.newsPersons.find(person => person.id === props.newsStory.news_person.id);
  if (newsPerson) {
    selectedNewsPerson.value = { label: newsPerson.user.name, value: newsPerson.id };
  }

  // Additional sequential logic that depends on the fetched data
  await newsStore.updateNewsStoryAndSetLocation(props.newsStory, {
    country: props.country,
    categories: props.categories,
    // other related data
  });

  // Indicate loading of specific parts if needed
  newsStore.isLoadingCategoryCityData = false; // Adjust based on actual logic; ensure this is set correctly before/after relevant operations

  // Ensure component is updated after all state changes
  await nextTick();

  // Once all data is loaded and post-processed, set isLoading to false
  isLoading.value = false;
});


const onSelect = (newValue) => {
  console.log('Selected value:', newValue);
  console.log(selectedNewsPerson.value.value)

  // Additional logic here
};
// This method is optional, based on whether you need to perform additional actions on selection
// const onSelectionChange = (value) => {
//   console.log('Selected news person:', value);
//   // Perform additional actions as needed
// };


// onMounted(async() => {
//   // Consolidate related data into an object for clarity
//   const relatedData = {
//     country: props.country,
//     categories: props.categories,
//   };
//   await newsStore.fetchLocationsForSearch()
//   // Now that locationSearchItems are loaded, set the selected location
//   newsStore.setSelectedLocation();
//   newsStore.updateNewsStoryData(props.newsStory, relatedData);
// })

// Watch for changes in relevant store states
watch(() => [newsStore.news_category_id, newsStore.news_category_sub_id], () => {
  newsStore.setSelectedCategory()
  newsStore.getSubcategories()
  // You can also watch for other relevant states if they affect setSelectedCategory
})

const submit = () => {
  processing.value = true
  const newsPersonId = selectedNewsPerson.value ? selectedNewsPerson.value.value : null;
  // Check if selectedSubcategory.id is null, and if so, set it to a default value or handle it as needed
  // const subcategoryId = newsStore.selectedSubcategory ? newsStore.selectedSubcategory.id : null;

  const data = {
    id: newsStore.newsStory.id,
    title: newsStore.newsArticleTitleTiptop,
    status: props.newsStory.status.id,
    // body: JSON.stringify(newsStore.content_json),
    content_json: JSON.stringify(newsStore.newsArticleContentTiptop),
    news_category_id: newsStore.news_category_id,
    news_category_sub_id: newsStore.news_category_sub_id, // Use the value after handling null
    city_id: newsStore.city_id,
    province_id: newsStore.province_id,
    federal_electoral_district_id: newsStore.federal_electoral_district_id,
    subnational_electoral_district_id: newsStore.subnational_electoral_district_id,
    type: newsStore.selectedLocation.type,
    news_person_id: newsPersonId, // Use the selected news person ID
  }

  Inertia.patch(route('newsStory.update', newsStore.newsStory.slug), data, {
    onError: (errors) => {
      console.error(errors);
      processing.value = false
    }
  })
}

onBeforeUnmount(() => {
  newsStore.isLoadingCategoryCityData = false
})
</script>
