<template>
  <div>
    <div class="">
                              <span :class="{'text-gray-500':newsStory.published_at, 'italic':newsStory.published_at}">
                              <span class="text-xs uppercase">Created on</span>
                              {{
                                  userStore.formatDateTimeWithYearFromUtcToUserTimezone(newsStory.created_at)
                                }} {{ userStore.timezoneAbbreviation }}</span>
    </div>
    <div class="">
                              <span :class="{'text-gray-500':newsStory.published_at, 'italic':newsStory.published_at}">
                              <span class="text-xs uppercase">Last updated</span>
                              {{
                                  userStore.formatDateTimeWithYearFromUtcToUserTimezone(newsStory.updated_at)
                                }} {{ userStore.timezoneAbbreviation }}</span>
    </div>
  </div>
</template>
<script setup>
import { useUserStore } from '@/Stores/UserStore'

const userStore = useUserStore()

const props = defineProps({
  newsStory: Object,
})
</script>