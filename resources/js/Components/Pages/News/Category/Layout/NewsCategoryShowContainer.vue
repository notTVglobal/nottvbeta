<template>
  <div class="pt-8">
    <h1 class="text-2xl font-bold">{{ newsCategory.name }}</h1>
    <div v-if="newsStories?.data?.length">
      <ul>
        <li v-for="newsStory in newsStories?.data" :key="newsStory.id">
          <Link :href="`/news/story/${newsStory.slug}`">{{ newsStory.title }}</Link>
          <p>{{ newsStory.content }}</p>
        </li>
      </ul>
      <Pagination :data="newsStories.data"/>
    </div>
    <div v-else>
      <p>No news stories found for this category.</p>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue'

import Pagination from '@/Components/Global/Paginators/Pagination.vue'

const props = defineProps({
  newsCategory: Object,
  newsStories: Object,
})

</script>
