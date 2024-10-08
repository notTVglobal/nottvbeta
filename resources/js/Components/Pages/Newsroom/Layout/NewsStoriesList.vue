<template>
  <div class="w-full overflow-x-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 border-b border-gray-200">
      <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <div class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
          <div class="w-full">
            <div
                v-for="(newsStory, index) in newsStories.data"
                :key="newsStory.id"
                :class="{'bg-gray-50 dark:bg-gray-700': index % 2 === 0, 'bg-white dark:bg-gray-800': index % 2 !== 0}"
                class="w-full"
            >
              <NewsStoryItem
                  :newsStory="newsStory"
                  :can="can"
                  @request-publish="openConfirmPublishDialog"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Paginator -->
    <Pagination :data="newsStories.meta" class=""/>
    <ConfirmPublishNewsDialog
        :newsStory="selectedNewsStory"
        @confirm="publishNewsStory"
        @cancelConfirmation="closeConfirmDialog"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useNewsStore } from '@/Stores/NewsStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import Pagination from '@/Components/Global/Paginators/Pagination'
import ConfirmPublishNewsDialog from '@/Components/Global/Modals/ConfirmPublishNewsDialog.vue'
import NewsStoryItem from '@/Components/Pages/Newsroom/Elements/NewsStoryItem.vue'

const newsStore = useNewsStore()
const appSettingStore = useAppSettingStore()

const props = defineProps({
  newsStories: Object,
  can: Object,
})

const showConfirmDialog = ref(false);
const selectedNewsStory = ref(null);

const openConfirmPublishDialog = (newsStory) => {
  selectedNewsStory.value = newsStory;
  appSettingStore.showConfirmationDialog = true;
};

const closeConfirmDialog = () => {
  appSettingStore.showConfirmationDialog = false;
  selectedNewsStory.value = null;
};

const publishNewsStory = () => {
  if (selectedNewsStory.value) {
    newsStore.publish(selectedNewsStory.value);
    closeConfirmDialog();
  }
};
</script>
