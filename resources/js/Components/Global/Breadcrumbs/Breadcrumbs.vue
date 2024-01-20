<template>
  <div aria-label="breadcrumb" class="w-[calc(60vw)] py-4 px-10 2xl:px-96">
    <ol class="flex list-none">
      <li v-for="(crumb, index) in breadcrumbs" :key="index" class="flex items-center min-w-fit break-words">
        <span v-if="index === breadcrumbs.length - 1" :class="classType === 'dark' ? 'text-gray-500' : 'text-gray-500'">
          {{ crumb.text }}
        </span>
        <Link v-else :href="crumb.to" :class="classType === 'dark' ? 'text-blue-800 hover:text-blue-600' : 'text-blue-300 hover:text-blue-500'">
          {{ crumb.text }}
        </Link>
        <span v-if="index < breadcrumbs.length - 1" class="mx-2">></span>
      </li>
    </ol>
  </div>
</template>

<script setup>
import { defineProps } from 'vue'

const props = defineProps({
  breadcrumbs: {
    type: Array,
    required: true,
    default: () => []
  },
  classType: {
    type: String,
    default: 'light'
  }
})
</script>
