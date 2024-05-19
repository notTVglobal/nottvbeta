<template>
  <div v-if="!newsStore.isLoadingCategoryCityData" class="mt-8 bg-gray-200 rounded-lg">
    <div class="px-4 py-4 font-semibold text-xs uppercase">Sub-category</div>
    <div class="flex flex-wrap py-4 px-4">
      <div class="flex flex-col">
        <label for="subcategorySelect" class="text-sm font-medium text-gray-900 dark:text-gray-300 pr-4">Select Subcategory:</label>
        <span class="italic text-sm font-thin text-gray-900 dark:text-gray-300">(optional)</span>
      </div>
      <div class="flex flex-wrap">
        <select v-model="selectedSubcategoryId" @change="onSubcategoryChange" id="subcategorySelect" class="rounded">
          <option :value="null">Choose a sub-category</option> <!-- Add a blank option -->
          <option v-for="subcategory in selectedCategory.subCategories" :key="subcategory.id" :value="subcategory.id">
            {{ subcategory.name }}
          </option>
        </select>
      </div>
    </div>

    <div v-if="selectedSubcategory" class="flex-col justify-left py-4 px-4">
      <div class="font-semibold text-xs uppercase text-indigo-900">{{ selectedSubcategory.name }}</div>
      <div class="px-6 py-4 text-indigo-800">{{ selectedSubcategory.description }}</div>
    </div>
  </div>
</template>
<script setup>
import { useNewsStore } from '@/Stores/NewsStore'
import { computed, watch } from 'vue'
const newsStore = useNewsStore()

const selectedCategory = computed(() => {
  return newsStore.categories.find(category => category.id === newsStore.category?.id) || { subCategories: [] }
})

// Computed property for selected subcategory ID
const selectedSubcategoryId = computed({
  get() {
    return newsStore.subCategory?.id || null
  },
  set(value) {
    newsStore.subCategory = selectedCategory.value.subCategories.find(subcategory => subcategory.id === value) || {
      id: null,
      name: '',
      description: '',
    }
  },
})

// Computed property for selected subcategory
const selectedSubcategory = computed(() => {
  return selectedCategory.value.subCategories.find(subcategory => subcategory.id === selectedSubcategoryId.value) || null
})


// Function to handle subcategory change
const onSubcategoryChange = () => {
  const selectedSubcategory = selectedCategory.value.subCategories.find(subcategory => subcategory.id === selectedSubcategoryId.value)
  newsStore.subCategory = selectedSubcategory || {id: null, name: '', description: ''}
}

// Watcher to update newsStore when subcategory selection changes
watch(selectedSubcategoryId, (newValue) => {
  const selectedSubcategory = selectedCategory.value.subCategories.find(subcategory => subcategory.id === newValue)
  if (selectedSubcategory) {
    newsStore.subCategory = selectedSubcategory
  }
})

</script>