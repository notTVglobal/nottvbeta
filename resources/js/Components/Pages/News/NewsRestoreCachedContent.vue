<template>
  <div>
    <div v-if="newsStore.showEditor">
      <NewsWriterComponent/>
    </div>
  </div>
</template>
<script setup>
import { defineAsyncComponent, onMounted, watch } from 'vue'
import { useNewsStore } from '@/Stores/NewsStore'
import { useNotificationStore } from '@/Stores/NotificationStore'

const NewsWriterComponent = defineAsyncComponent({
  loader: () => import('@/Components/Pages/News/NewsWriterComponent.vue'),
  loadingComponent: {template: '<p>Loading...</p>'},
  errorComponent: {template: '<p>Error loading component</p>'},
})

const newsStore = useNewsStore()
const notificationStore = useNotificationStore()
// this component prompts the user if there is cached content
// to make a decision about restoring the cached content or not.

// Watch for confirmation result
watch(() => notificationStore.confirmation, (newValue) => {
  if (newValue !== null) {
    if (newValue) {
      // User confirmed, load cached content
      newsStore.initializeNewsStore(newsStore.cachedContent)
    } else {
      // User cancelled, load initial content
    }
    notificationStore.clearConfirmNotification()
    newsStore.showEditor = true
  }
})

onMounted(async () => {
  // Check for cached content and prompt the user
  if (newsStore.cachedContent) {
    notificationStore.setConfirmNotification('Confirmation Needed', 'You have unsaved changes from your previous session. Do you want to load them?')
  } else {
    // No cached content, load initial content
    newsStore.showEditor = true
  }
})
</script>