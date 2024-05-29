<template>
  <div class="flex flex-col justify-left w-fit space-y-1 space-x-1">
    <div class="flex flex-row justify-left w-full">
      <NewsStoryStatusButton :newsStory="newsStory" :newsStoryStatuses="newsStoryStatuses" :can="can"/>
    </div>
    <div class="flex flex-row justify-left w-full">
      <button
          v-if="newsStory.can.editNewsStory"
          @click="appSettingStore.btnRedirect(`/newsStory/${newsStory.slug}/edit`)"
          class="px-2 py-1 h-fit text-white text-sm bg-blue-600 hover:bg-blue-500 rounded-lg"
      >Edit
      </button>
      <button
          class="px-2 py-1 ml-1 h-fit text-white text-sm font-semibold bg-red-600 hover:bg-red-500 rounded-lg"
          @click="destroy(newsStory.slug)"
          v-if="newsStory.can.deleteNewsStory && (newsStory.status.id === 1 || newsStory.status.id === 2)"
      >
        <font-awesome-icon icon="fa-trash-can"/>
      </button>
    </div>

    <span v-if="!props.can.publishNewsStory && !newsStory.published_at && newsStory.status.id === 3"
          class="mr-6 text-gray-500 italic"> not yet published</span>
    <span v-if="newsStory.published_at" class="mr-6 text-gray-800 dark:text-white font-semibold">{{
        userStore.formatDateTimeWithYearFromUtcToUserTimezone(newsStory.published_at)
      }}</span>
  </div>
</template>
<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import NewsStoryStatusButton from '@/Components/Pages/News/NewsStoryStatusButton.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()

const props = defineProps({
  newsStory: Object,
  newsStoryStatuses: Object,
  can: Object,
})

function destroy(slug) {
  if (confirm('Are you sure you want to Delete')) {
    form.delete(route('newsStory.destroy', slug))

  }
}

</script>