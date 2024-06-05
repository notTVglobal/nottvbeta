<template>
  <div class="w-full flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="sm:flex sm:items-center sm:gap-4 w-full">
      <div class="flex items-center justify-center sm:hidden min-w-[8rem] max-w-fit px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap align-middle border-b-2">
        <button @click="appSettingStore.btnRedirect(`/news/story/${newsStory.slug}`)">
          <SingleImage :image="newsStory.image" alt="news cover" class="rounded-full h-20 w-20 object-cover" />
        </button>
      </div>
      <div class="sm:table-cell hidden min-w-[8rem] max-w-fit px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap align-middle border-b-2">
        <button @click="appSettingStore.btnRedirect(`/news/story/${newsStory.slug}`)">
          <SingleImage :image="newsStory.image" alt="news cover" class="rounded-full h-20 w-20 object-cover" />
        </button>
      </div>
      <div class="px-6 py-4 w-full font-medium text-gray-900 dark:text-gray-50 whitespace-normal align-middle flex-1">
        <div @click="appSettingStore.btnRedirect(`/news/story/${newsStory.slug}`)"
             class="hover:cursor-pointer w-full break-words break-all text-lg font-semibold text-blue-800 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-200">
          {{ newsStory.title }}
        </div>
        <div>By {{ newsStory.newsPerson && newsStory.newsPerson.name ? newsStory.newsPerson.name : '' }}</div>

        <div class="flex flex-col pt-2 text-sm">
          <div v-if="newsStory.category?.id" class="font-medium text-orange-800">
            {{ newsStory.category.name }}
            <span v-if="newsStory.subCategory?.id"><span class="text-black"> | </span>{{ newsStory.subCategory.name }}</span>
          </div>

          <NewsStoryItemLocation :newsStory="newsStory"/>

          <NewsStoryItemDates :newsStory="newsStory" class="block 2xl:hidden mt-2" />
          <NewsStoryActionButtons :newsStory="newsStory" :can="can" class="block xl:hidden mt-4" />
        </div>

        <div>
          <button
              v-if="newsStory.can.publishNewsStory && !newsStory.published_at && newsStory.status.id === 3"
              @click="requestPublish"
              class="bg-green-600 hover:bg-green-500 text-white mt-1 mx-2 px-4 py-2 h-fit rounded disabled:bg-gray-400"
          >
            Publish
          </button>
        </div>
      </div>
    </div>
    <div class="hidden 2xl:flex items-center px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap align-middle">
      <NewsStoryItemDates :newsStory="newsStory" />
    </div>
    <div class="hidden xl:flex items-center px-6 py-4 align-right justify-end space-x-2">
      <NewsStoryActionButtons :newsStory="newsStory" :can="can" />
    </div>
  </div>
</template>

<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import NewsStoryItemDates from '@/Components/Pages/Newsroom/Elements/NewsStoryItemDates.vue'
import NewsStoryActionButtons from '@/Components/Pages/Newsroom/Elements/NewsStoryActionButtons.vue'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'
import NewsStoryItemLocation from '@/Components/Pages/Newsroom/Elements/NewsStoryItemLocation.vue'

const appSettingStore = useAppSettingStore()

const props = defineProps({
  newsStory: Object,
  can: Object,
})

const emit = defineEmits(['request-publish']);

const requestPublish = () => {
  emit('request-publish', props.newsStory);
};
</script>
