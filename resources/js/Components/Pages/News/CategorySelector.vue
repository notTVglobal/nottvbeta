<template>
  <div v-if="!newsStore.isLoadingCategoryCityData" class="bg-gray-200 rounded-lg">
    <div class="px-4 py-4 font-semibold text-xs uppercase">Category</div>
    <div class="px-4 pb-4">
      <label for="categorySelect" class="text-sm font-medium text-gray-900 dark:text-gray-300 pr-4">Select Category:</label>
      <select required
              v-model="selectedCategoryId"
              id="categorySelect"
              class="rounded text-black bg-white dark:text-gray-50 dark:bg-gray-800"
              @change="onCategoryChange"
      >
        <option :value="null">Choose a category</option>
        <option v-for="category in newsStore.categories" :key="category.id" :value="category.id">
          {{ category.name }}
        </option>
      </select>
    </div>
    <div v-if="selectedCategory" class="flex flex-col justify-left px-4 py-4">
      <div class="font-semibold text-xs uppercase text-indigo-900">{{ selectedCategory.name }}</div>
      <div class="px-6 py-4 text-indigo-800">{{ selectedCategory.description }}</div>
    </div>
  </div>
</template>

<script setup>
import { useNewsStore } from '@/Stores/NewsStore'
import { computed, watch } from 'vue'

const newsStore = useNewsStore()

// Computed property for selected category ID
const selectedCategoryId = computed({
  get() {
    return newsStore.category?.id || null
  },
  set(value) {
    newsStore.category = newsStore.categories.find(category => category.id === value) || { id: null, name: '', description: '', subCategories: [] }
    newsStore.subCategory = { id: null, name: '', description: '' }
  },
})

// Computed property for selected category
const selectedCategory = computed(() => {
  return newsStore.categories.find(category => category.id === selectedCategoryId.value) || { subCategories: [] }
})


// Function to handle category change
const onCategoryChange = () => {
  const selectedCategory = newsStore.categories.find(category => category.id === selectedCategoryId.value)
  newsStore.category = selectedCategory || { id: null, name: '', description: '', subCategories: [] }
  if (newsStore.category.id !== 3) {
    newsStore.city = {}
    newsStore.province = {}
    newsStore.federalElectoralDistrict = {}
    newsStore.subnationalElectoralDistrict = {}
  }
}

// Watcher to update newsStore when category selection changes
watch(selectedCategoryId, (newValue) => {
  const selectedCategory = newsStore.categories.find(category => category.id === newValue)
  if (selectedCategory) {
    newsStore.category = selectedCategory
    newsStore.subCategory = {id: null, name: '', description: ''}
  }
})
</script>