<template>
  <div class="flex flex-col py-8 max-w-7xl justify-center items-center gap-8 mx-auto">
    <h1 class="text-4xl font-bold">Movie Categories</h1>
    <div class="flex flex-row flex-wrap justify-center gap-6 mx-auto">
      <div
          v-for="category in categories"
          :key="category.id"
          @click.prevent="appSettingStore.btnRedirect(`/movies/${category.slug}`)"
          class="category-card w-60 break-words hover:cursor-pointer hover:text-blue-500 relative group"
      >
        <div class="w-60 h-60 bg-gray-200 text-black rounded-xl flex items-center justify-center">
          <span class="text-xl font-bold">{{ category.name }}</span>
        </div>
        <!-- Overlay div for dimming effect -->
        <div class="absolute inset-0 bg-black bg-opacity-0 transition-opacity duration-300 rounded-xl group-hover:bg-opacity-50"></div>
        <div class="absolute inset-0 flex items-center justify-center opacity-50 group-hover:opacity-100">
          <span class="text-white text-xl font-bold">{{ category.name }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { defineProps } from 'vue'
import { usePageSetup } from '@/Utilities/PageSetup'

const appSettingStore = useAppSettingStore()

usePageSetup(`movies.categories.index`);

const props = defineProps({
  categories: Array,
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
