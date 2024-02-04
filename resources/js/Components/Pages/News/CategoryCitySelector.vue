<template>



  <!-- The category select -->
  <div class="flex flex-col py-4 px-6">

    <div class="bg-gray-200 rounded-lg">
      <div class="px-4 py-4 font-semibold text-xs uppercase">Category</div>
      <div class="px-4 pb-4">
        <label for="categorySelect" class="text-sm font-medium text-gray-900 dark:text-gray-300 pr-4">Select Category:</label>
        <select required
                v-model="newsStore.selectedCategory"
                id="categorySelect"
                class="rounded"
                @change="onCategoryChange"
        >
          <option :value="{ id: null }" >Choose a category</option>
          <option v-for="category in newsStore.categories" :key="category.id" :value="category">{{ category.name }}</option>
        </select>
      </div>
      <div v-if="newsStore.selectedCategory.name" class="flex flex-col justify-left px-4 py-4">
        <div class="font-semibold text-xs uppercase text-indigo-900">{{newsStore.selectedCategory.name}}</div>
        <div v-if="newsStore.selectedCategory" class="px-6 py-4 text-indigo-800">
          {{newsStore.selectedCategory.description}}
        </div>
      </div>
    </div>



    <!-- The location select -->
    <div v-if="newsStore.selectedCategory && newsStore.selectedCategory.id === 3" class="mt-8 bg-gray-200 rounded-lg">
      <div class="px-4 py-4 font-semibold text-xs uppercase">Location</div>
      <div class="flex flex-col pb-4 px-6">
        <div class="text-sm font-semibold text-gray-900 dark:text-gray-300 py-1">Please select the city, town, province or electoral district:</div>
        <div class="w-full mr-4 relative">

          <input
              v-model="searchInput"
              type="search"
              class="w-full rounded-lg mt-2"
              placeholder="Search..."
              @focus="newsStore.citySelectDropdownVisible = true"
              @blur="handleInputBlur"
              @keydown="handleKeydown"
          />
          <span v-if="newsStore.selectedLocation" class="relative xl:absolute inset-y-0 right-0 flex items-center pr-4">
                <!-- Styled display text -->
                <span class="text-gray-500">{{ newsStore.displayText }}</span>
              </span>

          <ul v-if="newsStore.citySelectDropdownVisible && locationSearch.length > 0" class="absolute z-10 w-full bg-white mt-1 max-h-60 rounded-lg shadow-lg overflow-auto border border-gray-200">
            <li v-for="(location, index) in newsStore.locationSearch"
                :key="location.id"
                  class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-gray-100"
                  :class="{'bg-gray-200': index === newsStore.focusedIndex.value, 'dropdown-item': true, [`dropdown-item-${index}`]: true}"
                  @click="inspectLocation(location)"
              >

                <span v-if="location.type === 'city' || location.type === 'town'">{{ location.name }}, <span class="text-gray-600">{{ location.province_name }}</span></span>
                <span v-if="location.type === 'province'">{{ location.name }} &nbsp;&nbsp;<span class="uppercase text-xs font-semibold text-gray-600">Province</span></span>
                <span v-if="location.type === 'territory'">{{ location.name }} &nbsp;&nbsp;<span class="uppercase text-xs font-semibold text-gray-600">Territory</span></span>
                <span v-if="location.type === 'federalElectoralDistrict'">{{ location.name }} &nbsp;&nbsp;<span class="uppercase text-xs font-semibold text-gray-600">Federal Electoral District</span></span>
                <span v-if="location.type === 'subnationalElectoralDistrict'">{{ location.name }}</span>

              </li>
            </ul>
            <div
                v-if="newsStore.formErrors.type"
                class="text-sm text-red-600"
            >
              {{ newsStore.formErrors.type }}
            </div>
          </div>
        </div>
    </div>






    <!-- The sub-category select -->
    <div class="mt-8 bg-gray-200 rounded-lg">
      <div class="px-4 py-4 font-semibold text-xs uppercase">Sub-category</div>
      <div class="flex flex-wrap py-4 px-4">
        <div class="flex flex-col">
          <label for="subcategorySelect" class="text-sm font-medium text-gray-900 dark:text-gray-300 pr-4">Select Subcategory:</label>
          <span class="italic text-sm font-thin text-gray-900 dark:text-gray-300">(optional)</span>
        </div>
        <div v-if="newsStore.selectedCategory" class="flex flex-wrap">
          <select v-model="newsStore.selectedSubcategory" id="subcategorySelect" class="rounded">
            <option :value="{ id: null }">Choose a sub-category</option> <!-- Add a blank option -->
            <option v-for="subcategory in newsStore.subcategories" :key="subcategory.id" :value="subcategory">{{ subcategory.name }}</option>
          </select>
        </div>
    </div>

    <div v-if="newsStore.selectedSubcategory" class="flex-col justify-left py-4 px-4">
      <div v-if="newsStore.selectedSubcategory" class="font-semibold text-xs uppercase text-indigo-900">{{newsStore.selectedSubcategory.name}}</div>
      <div v-if="newsStore.selectedSubcategory" class="px-6 py-4 text-indigo-800">
        {{newsStore.selectedSubcategory.description}}
      </div>
    </div>
    </div>

    <div
        v-if="newsStore.formErrors.news_category_id"
        class="text-sm text-red-600"
    >
      {{ newsStore.formErrors.news_category_id }}
    </div>

  </div>

</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'
import { usePage } from '@inertiajs/inertia-vue3';
import { watch, ref, computed, onMounted } from 'vue'
import { debounce, throttle } from 'lodash'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useNewsStore } from '@/Stores/NewsStore'

const appSettingStore = useAppSettingStore()
const newsStore = useNewsStore()

// Define props and other logic
let props = defineProps({
  newsStory: Object,
  country: Object,
  categories: Object,
  locationSearch: Object,
  filters: Object,
  errors: Object,
  searchPath: String,
});
// const { props } = usePage();

// Watch for changes in locationSearch prop and update the store
watch(() => props.locationSearch, (newLocationSearch) => {
  newsStore.locationSearch = newLocationSearch;
}, { deep: true });

// If you need to watch the filters as well
watch(() => props.filters, (newFilters) => {
  if (newFilters && newFilters.search !== undefined) {
    newsStore.search = newFilters.search;
  }
}, { deep: true });

onMounted(() => {
  // Initialize newsStore.search with the initial search value from props
  newsStore.search = props.filters.search;
});

const onCategoryChange = () => {
  newsStore.getSubcategories();
  newsStore.getSelectedSubcategory();
};

// const selectLocation = (location) => {
//   console.log('selectLocation called with:', location);
//   newsStore.updateSelectedLocation(location);
// };
const inspectLocation = (location) => {
  newsStore.updateSelectedLocation(location);
  console.log('Clicked location:', location);
};

function selectLocation(location) {
  newsStore.updateSelectedLocation(location);
}
// watch(newsStore.focusedIndex, (newValue) => {
//   if (newValue >= 0) {
//     const item = document.querySelector(`.dropdown-item-${newValue}`);
//     item?.scrollIntoView({ block: 'center' });
//   }
// });

const searchInput = computed({
  get() {
    // If there's a selected location, show its name; otherwise, show the search value
    const location = newsStore.selectedLocation;
    if (location) {
      if (location.type === 'city' || location.type === 'town') {
        return `${location.name}, ${location.province_name}`;
      }
      return location.name;
    }
    return newsStore.search;
  },
  set(value) {
    // Update the search value in the store when the user types
    newsStore.search = value;

    // Optionally, clear or update selectedLocation if necessary
    // For example, if you want to clear the selected location when the user types:
    if (newsStore.selectedLocation?.name !== value) {
      newsStore.selectedLocation = null;
    }
  }
});

watch(() => newsStore.search, throttle((value) => {
  let url;
  if (appSettingStore.currentPage === 'newsEdit') {
    url = `/newsStory/${newsStore.newsStory.slug}/edit`;
  } else if (appSettingStore.currentPage === 'newsCreate') {
    url = '/newsStory/create';
  }

  if (url) {
    Inertia.get(url, { search: value }, {
      preserveState: true,
      replace: true,
    });
  }
}, 300));

const showDropdown = () => {
  newsStore.citySelectDropdownVisible = true;
};

const handleInputBlur = () => {
  // Delay hiding dropdown to allow click event on dropdown item to be processed
  setTimeout(() => {
    newsStore.citySelectDropdownVisible = false;
  }, 200);
};

const handleKeydown = (event) => {
  if (event.key === 'Enter') {
    // Prevent default action of the Enter key
    event.preventDefault();

    if (newsStore.locationSearch.length > 0 && newsStore.focusedIndex.value !== null) {
      const selectedLocation = newsStore.locationSearch[newsStore.focusedIndex.value];
      if (selectedLocation) {
        newsStore.selectedLocation = selectedLocation; // Set the selected location
        newsStore.updateSelectedLocation(selectedLocation); // Update the location in the store
        newsStore.citySelectDropdownVisible = false; // Close the dropdown
      }
    }
  }
  // Handle other keydown events if necessary
};

</script>

<style scoped>
.select-container {
  max-width: 100%; /* Adjust the maximum width as needed */
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}
</style>
