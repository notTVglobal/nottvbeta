<template>
  <div class="flex flex-col py-8 max-w-7xl justify-center items-center gap-8">
    <h1 class="text-2xl font-bold">Categories</h1>
    <div class="flex flex-row flex-wrap justify-center gap-6 mx-auto">
      <div
          v-for="newsCategory in newsCategories"
          :key="newsCategory.id"
          @click.prevent="appSettingStore.btnRedirect(`/news/category/${newsCategory.slug}`)"
          class="category-card w-60 break-words hover:cursor-pointer hover:text-blue-500 relative group"
      >
        <div class="w-60 h-60 bg-gray-200 rounded-xl flex items-center justify-center">
          <span class="text-xl font-bold">{{ newsCategory.name }}</span>
        </div>
        <!-- Overlay div for dimming effect -->
        <div class="absolute inset-0 bg-black bg-opacity-0 transition-opacity duration-300 rounded-xl group-hover:bg-opacity-50"></div>
        <div class="absolute inset-0 flex items-center justify-center opacity-50 group-hover:opacity-100">
          <span class="text-white text-xl font-bold">{{ newsCategory.name }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { defineProps } from 'vue'

const appSettingStore = useAppSettingStore()

const props = defineProps({
  newsCategories: Array,
})
</script>

<style scoped>
.category-card .w-60 {
  transition: transform 0.3s ease-in-out;
}

.category-card:hover .w-60 {
  transform: scale(1.05); /* Scales up the card to 105% of its original size on hover */
}

.category-card .h-60 {
  transition: transform 0.3s ease-in-out;
}

.category-card:hover .h-60 {
  transform: scale(1.05); /* Scales up the card to 105% of its original size on hover */
}
</style>
