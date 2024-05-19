<template>
  <div class="pt-8">
    <h1 class="text-2xl font-bold">{{ newsCity.name }}</h1>
    <div v-if="newsStories?.data?.length">
      <ul>
        <li v-for="newsStory in newsStories?.data" :key="newsStory.id">
          <Link :href="`/news/story/${newsStory.slug}`">{{ newsStory.title }}</Link>
          <p>{{ newsStory.content }}</p>
        </li>
      </ul>
<!--      <Pagination :links="newsStories.links" />-->
    </div>
    <div v-else>
      <p>No news stories found for this city.</p>
    </div>
  </div>
</template>

<script setup>
import { defineProps, ref, watch } from 'vue'
// import Pagination from '@/Components/Paginators/Pagination.vue'
import throttle from 'lodash/throttle'
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
  newsCity: Object,
  newsStories: Object,
})

</script>
