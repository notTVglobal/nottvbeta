<template>

  <Head title="Edit News Post"/>

  <div class="place-self-center flex flex-col gap-y-3">
    <div v-if="newsStore.isLoading" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">
      <span class="loading loading-spinner text-info"></span>
      <span class="ml-4">Loading... please wait.</span>
      </div>
    <div v-else id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>
      <JetValidationErrors class="ml-4"/>

      <NewsEditHeader :can="can" />

      <div class="p-6 border-b border-gray-200">

        <div class="flex flex-wrap justify-between gap-3">
          <NewsSelectPersonContainer :can="can" class="flex-grow"/>
          <ChangeNewsImage />
        </div>

        <NewsCategoryCityContainer />

        <div class="py-4 px-6 mb-6 bg-white shadow rounded-lg">

          <!-- Prompt the user to restore cached content -->
          <!-- TipTapNewsEditor is inside here, in NewsEditComponent -->
          <NewsRestoreCachedContent />

          <div class="flex justify-end items-center">
            <transition name="fade">
              <div v-if="newsStore.showSaveMessage" class="save-message text-xs mr-2 text-green-500">
                Content cached
              </div>
            </transition>
            <button
                @click="newsStore.submit"
                class="text-white bg-blue-700 hover:bg-blue-500 focus:outline-none font-medium rounded-lg text-sm px-5 py-2"
                :disabled="newsStore.processing"
                :class="{ 'opacity-25': newsStore.processing }"
            >
              Save
            </button>
            <JetValidationErrors class="ml-4"/>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineAsyncComponent, onMounted, onUnmounted } from 'vue'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useNewsStore } from '@/Stores/NewsStore'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import Message from '@/Components/Global/Modals/Messages'
import 'vue-select/dist/vue-select.css';
import NewsEditHeader from '@/Components/Pages/News/NewsEditHeader.vue'
import NewsRestoreCachedContent from '@/Components/Pages/News/NewsRestoreCachedContent.vue'
import ChangeNewsImage from '@/Components/Pages/News/ChangeNewsImage.vue'
const NewsCategoryCityContainer = defineAsyncComponent({
  loader: () => import('@/Components/Pages/News/NewsCategoryCityContainer.vue'),
  loadingComponent: { template: '<p>Loading...</p>' },
  errorComponent: { template: '<p>Error loading component</p>' },
})
const NewsSelectPersonContainer = defineAsyncComponent({
  loader: () => import('@/Components/Pages/News/NewsSelectPersonContainer.vue'),
  loadingComponent: { template: '<p>Loading...</p>' },
  errorComponent: { template: '<p>Error loading component</p>' },
})

usePageSetup('newsEdit')

const appSettingStore = useAppSettingStore()
const newsStore = useNewsStore()

const props = defineProps({
  newsStory: Object,
  cachedContent: Object,
  can: Object,
  errors: Object,
})

newsStore.errors = props.errors; // This will contain the error messages

onMounted(() => {
  newsStore.initializeNewsStore(props.newsStory)
  if (props.cachedContent) {
    newsStore.cachedContent = props.cachedContent
  }
  newsStore.isLoading = false
})

onUnmounted(() => {
  newsStore.reset();
})
</script>
<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter, .fade-leave-to {
  opacity: 0;
}
</style>