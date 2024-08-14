<template>
  <div v-if="hasPages" class="flex flex-wrap justify-center my-4 space-x-4 space-y-2">
    <a v-if="links.prev"
       :href="links.prev"
       @click.prevent="changePage(links.prev)"
       class="px-4 py-3 text-sm leading-4 h-fit rounded bg-gray-200 hover:bg-gray-300 dark:bg-gray-800 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-50 focus:text-indigo-500 hover:shadow">
      Previous
    </a>

    <a v-if="links.next"
       :href="links.next"
       @click.prevent="changePage(links.next)"
       class="px-4 py-3 text-sm leading-4 h-fit rounded bg-gray-200 hover:bg-gray-300 dark:bg-gray-800 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-50 focus:text-indigo-500 hover:shadow">
      Next
    </a>
  </div>
</template>

<script setup>
import { computed } from 'vue'

// Define props with default values to avoid undefined errors
const props = defineProps({
  links: {
    type: Object,
    default: () => ({}) // Empty object by default
  },
  current_page: {
    type: Number,
    default: 1 // Default to page 1
  },
  last_page: {
    type: Number,
    default: 1 // Default to 1, which means only one page
  },
})

// Compute whether pagination is necessary
const hasPages = computed(() => props.last_page > 1)

const emit = defineEmits(['page-change'])

const changePage = (url) => {
  if (url) {
    const page = new URL(url).searchParams.get('members')
    emit('page-change', parseInt(page, 10))
  }
}
</script>
