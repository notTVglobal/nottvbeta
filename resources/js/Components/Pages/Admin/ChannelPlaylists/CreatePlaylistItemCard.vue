<template>
  <div :class="userStore.isSmallScreen ? '' : 'flex flex-wrap items-start justify-start w-full'">
    <div class="flex items-center space-x-2 mb-2">
      <SingleImage :image="item?.content?.image" :class="`w-10 h-10 object-contain`"/>
      <div>
        <div class="text-sm font-semibold">#{{ index + 1 }} - {{ item?.content?.name }}</div>
        <div class="text-xs text-gray-500">{{ item.duration_minutes }} minutes &middot; Priority: {{
            item.priority
          }}
        </div>
      </div>
    </div>
    <div class="flex items-center space-x-2 mb-2 w-full">
      <SingleImage :image="item?.content?.team?.image" :class="`w-5 h-5 object-contain`"/>
      <div class="text-sm">{{ item?.content?.team?.name }}</div>
    </div>
    <div class="text-sm mb-2 w-full">
      <div><span class="text-xs uppercase font-semibold">Start time: </span>{{ formatDate(item.start_dateTime) }}</div>
      <div><span class="text-xs uppercase font-semibold">End time: </span>{{ formatDate(item.end_dateTime) }}</div>
    </div>
    <div class="w-full mt-2 flex justify-between items-center">
      <div>
        <span class="badge badge-outline" :class="getBadgeClass(item.type)">{{ cleanTypeName(item.type) }}</span>
        <span v-if="item.is_scheduled" class="badge badge-outline text-black ml-2">Scheduled</span>
      </div>
      <div>
        <button @click.prevent="$emit('removeItem', item.id)" class="btn btn-xs btn-error">Remove</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useUserStore } from '@/Stores/UserStore'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'
import dayjs from 'dayjs'

const userStore = useUserStore()

const props = defineProps({
  item: Object,
  index: Number,
})

const emit = defineEmits(['removeItem'])

// Function to format the date and time
const formatDate = (date) => {
  return dayjs(date).format('MMM D, YYYY, hh:mm A')
}

// Function to clean the type name
const cleanTypeName = (type) => {
  // Remove the App\Models\ prefix if present
  const cleanType = type.replace(/^App\\Models\\/, '')

  // Add space before each uppercase letter
  const spacedType = cleanType.replace(/([a-z])([A-Z])/g, '$1 $2')

  // Capitalize the first letter if it starts with a lowercase letter
  return spacedType.charAt(0).toUpperCase() + spacedType.slice(1)
}

// Function to get badge class based on type
const getBadgeClass = (type) => {
  // Normalize the type without altering the original
  const normalizedType = type.toLowerCase().split('\\').pop()

  switch (normalizedType) {
    case 'movie':
      return 'text-red-500'
    case 'show':
      return 'text-blue-500'
    case 'showepisode':
      return 'text-green-500'
    case 'movietrailer':
      return 'text-orange-500'
    case 'othercontent':
      return 'text-purple-500'
    case 'newsstory':
      return 'text-yellow-500'
    default:
      return 'text-gray-500'
  }
}
</script>
