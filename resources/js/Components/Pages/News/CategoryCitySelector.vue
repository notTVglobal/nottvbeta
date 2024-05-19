<template>
  <div class="flex flex-col py-4 px-6">

    <div v-if="newsStore.isLoadingCategoryCityData" class="mt-8 p-10 bg-gray-200 rounded-lg">
      <span class="loading loading-spinner text-info"></span>
    </div>

    <!-- The category select -->
    <CategorySelector/>

    <!-- The location select -->
    <CitySelector/>

    <!-- The sub-category select -->
    <SubcategorySelector/>

    <div v-if="newsStore.errors.news_category_id" class="text-sm text-red-600">
      {{ newsStore.errors.news_category_id }}
    </div>

  </div>
</template>

<script setup>
import { computed, onMounted, watch } from 'vue'
import { useNewsStore } from '@/Stores/NewsStore'
import CitySelector from '@/Components/Pages/News/CitySelector.vue'
import CategorySelector from '@/Components/Pages/News/CategorySelector.vue'
import SubcategorySelector from '@/Components/Pages/News/SubcategorySelector.vue'

const newsStore = useNewsStore()

// Initialize category and subcategory on mount
onMounted(async () => {
  await newsStore.fetchCategories()
  await newsStore.fetchCitiesForSearch()
})

</script>

<style scoped>
.select-container {
  max-width: 100%; /* Adjust the maximum width as needed */
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}
</style>
