<template>
  <div v-if="newsStore.category && newsStore.category.id === 3" class="mt-8 text-gray-900 rounded-lg">
    <div v-if="newsStore.isLoadingCategoryCityData" class="bg-gray-200 rounded-lg p-10">
      <span class="loading loading-spinner text-info"></span>
    </div>
    <div v-if="!newsStore.isLoadingCategoryCityData">
      <div class="px-4 py-4 font-semibold text-xs uppercase">Location</div>
      <div class="flex flex-col pb-4 px-6">
        <div class="text-sm font-semibold text-gray-900 dark:text-gray-300 py-1">Please select the city, town, province
          or electoral district:
        </div>
        <div class="w-full mr-4 relative">

          <input
              v-model="searchInputModel"
              type="search"
              class="w-full rounded-lg mt-2"
              placeholder="Search..."
              @focus="newsStore.citySelectDropdownVisible = true"
              @blur="handleInputBlur"
              @keydown="handleKeydown"
          />
          <span v-if="newsStore.displayText" class="relative xl:absolute inset-y-0 right-0 flex items-center pr-4">
              <span class="text-gray-500">{{ newsStore.displayText }}</span>
            </span>

          <ul v-if="newsStore.citySelectDropdownVisible && newsStore.citySearchItems.length > 0"
              class="absolute z-10 w-full text-gray-900 bg-white dark:text-gray-100 dark:bg-gray-800 mt-1 max-h-60 rounded-lg shadow-lg overflow-auto border border-gray-200">
            <li v-for="(location, index) in newsStore.filteredCitySearchItems"
                :key="`${location.type}-${location.id}`"
                class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 bg-white dark:text-gray-100 dark:bg-gray-800"
                :class="{'bg-gray-200 dark:bg-gray-600': index === newsStore.focusedIndex.value, 'dropdown-item': true, [`dropdown-item-${index}`]: true}"
                @click="inspectLocation(location)"
            >
              <span v-if="location.type === 'city' || location.type === 'town'">{{ location.name }}, <span
                  class="text-gray-600 dark:text-gray-400">{{ location.province.name }}</span></span>
              <span v-if="location.type === 'province'">{{ location.name }} &nbsp;&nbsp;<span
                  class="uppercase text-xs font-semibold text-gray-600 dark:text-gray-400">Province</span></span>
              <span v-if="location.type === 'territory'">{{ location.name }} &nbsp;&nbsp;<span
                  class="uppercase text-xs font-semibold text-gray-600 dark:text-gray-400">Territory</span></span>
              <span v-if="location.type === 'federalElectoralDistrict'">{{ location.name }} &nbsp;&nbsp;<span
                  class="uppercase text-xs font-semibold text-gray-600 dark:text-gray-400">Federal Electoral District</span></span>
              <span v-if="location.type === 'subnationalElectoralDistrict'">{{ location.name }}</span>
            </li>
          </ul>
          <div v-if="newsStore.errors.type" class="text-sm text-red-600">
            {{ newsStore.errors.type }}
          </div>
        </div>
      </div>
    </div>
    <div v-if="newsStore.isLoadingCategoryCityData" class="p-10">
      <span class="loading loading-spinner text-info"></span>
    </div>
  </div>
</template>
<script setup>
import { useNewsStore } from '@/Stores/NewsStore'
import { computed } from 'vue'

const newsStore = useNewsStore()

const searchInputModel = computed({
  get() {
    return newsStore.searchInput // Use the getter from the store
  },
  set(value) {
    newsStore.setSearchInput(value) // Use the setter action from the store
  },
})

const inspectLocation = (location) => {
  newsStore.updateSelectedLocation(location)
}


const handleInputBlur = () => {
  // Delay hiding dropdown to allow click event on dropdown item to be processed
  setTimeout(() => {
    newsStore.citySelectDropdownVisible = false
  }, 200)
}

const handleKeydown = (event) => {
  if (event.key === 'Enter') {
    // Prevent default action of the Enter key
    event.preventDefault()

    if (newsStore.citySearch.length > 0 && newsStore.focusedIndex.value !== null) {
      const selectedLocation = newsStore.citySearch[newsStore.focusedIndex.value]
      if (selectedLocation) {
        newsStore.selectedLocation = selectedLocation // Set the selected location
        newsStore.updateSelectedLocation(selectedLocation) // Update the location in the store
        newsStore.citySelectDropdownVisible = false // Close the dropdown
      }
    }
  }
  // Handle other keydown events if necessary
}
</script>