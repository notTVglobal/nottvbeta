<template>
  <nav aria-label="breadcrumb" class="w-[calc(60vw)] py-4 px-10 2xl:px-96">
    <ol class="flex list-none">
      <li v-for="(crumb, index) in breadcrumbs" :key="index" class="flex items-center">
        <span v-if="index === breadcrumbs.length - 1" class="text-gray-500">
          {{ crumb.text }}
        </span>
        <Link v-else :href="crumb.to" class="text-blue-300 hover:text-blue-500">
          {{ crumb.text }}
        </Link>
        <span v-if="index < breadcrumbs.length - 1" class="mx-2">></span>
      </li>
    </ol>
  </nav>
</template>

<script setup>
import { defineProps } from 'vue'

const props = defineProps({
  breadcrumbs: {
    type: Array,
    required: true,
    default: () => []
  }
})
</script>
