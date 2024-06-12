<template>
  <div :class="userStore.isSmallScreen ? '' : 'flex flex-wrap items-start w-full'">
    <div class="flex items-center space-x-2 mb-2">
      <SingleImage :image="item?.content?.image" :class="`w-10 h-10 object-contain`" />
      <div>
        <div class="text-sm font-semibold">#{{ index + 1 }} - {{ item?.content?.name }}</div>
        <div class="text-xs text-gray-500">{{ item.duration_minutes }} minutes &middot; Priority: {{ item.priority }}</div>
      </div>
    </div>
    <div class="flex items-center space-x-2 mb-2 w-full">
      <SingleImage :image="item?.content?.team?.image" :class="`w-5 h-5 object-contain`" />
      <div class="text-sm">{{ item?.content?.team?.name }}</div>
    </div>
    <div class="text-sm mb-2 w-full">
      <div><span class="text-xs uppercase font-semibold">Start time: </span>{{ formatDateTime(item.start_dateTime) }}</div>
      <div><span class="text-xs uppercase font-semibold">End time: </span>{{ formatDateTime(item.end_dateTime) }}</div>
    </div>
    <div class="w-full mt-2 flex justify-between items-center">
      <span class="badge badge-outline" :class="getBadgeClass(item.type)">{{ item.type }}</span>
      <div>
        <button @click="$emit('removeItem', item.id)" class="btn btn-sm btn-danger">Remove</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useUserStore } from '@/Stores/UserStore'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'

const userStore = useUserStore()

const props = defineProps({
  item: Object,
  index: Number,
});

const emit = defineEmits(['removeItem']);

// Function to get badge class based on type
const getBadgeClass = (type) => {
  switch (type) {
    case 'movie':
      return 'text-red-500'
    case 'show':
      return 'text-blue-500'
    case 'showEpisode':
      return 'text-green-500'
    case 'otherContent':
      return 'text-purple-500'
    case 'newsStory':
      return 'text-yellow-500'
    default:
      return 'text-gray-500'
  }
}
</script>
