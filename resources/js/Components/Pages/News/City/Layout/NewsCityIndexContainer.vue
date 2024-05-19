<template>
  <div class="pt-8">
    <h1 class="text-2xl font-bold">Cities</h1>
    <ul>
      <li v-for="newsCity in newsCities" :key="newsCity.id">
        <Link :href="`/news/city/${newsCity.slug}`">{{ newsCity.name }}</Link>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { defineProps } from 'vue'

const props = defineProps({
  newsCities: Array,
})
</script>
