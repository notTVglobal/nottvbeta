<template>
  <div class="pt-8">
    <h1 class="text-2xl font-bold">Categories</h1>
    <ul>
      <li v-for="newsCategory in newsCategories" :key="newsCategory.id">
        <Link :href="`/news/category/${newsCategory.slug}`">{{ newsCategory.name }}</Link>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { defineProps } from 'vue'

const props = defineProps({
  newsCategories: Array,
})

</script>
