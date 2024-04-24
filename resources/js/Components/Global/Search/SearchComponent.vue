<template>
  <div class="bg-gray-800 p-4 rounded-lg shadow-lg max-w-md mx-auto mb-8">
    <!-- Slot for title -->
    <slot name="title">
      <!-- Default content if no slot content is provided -->
      <h2 class="text-white text-lg font-semibold mb-2">Search Episodes</h2>
    </slot>
    <!-- Slot for description -->
    <slot name="description">
      <!-- Default content if no slot content is provided -->
      <p class="text-gray-400 text-sm mb-4">Explore episodes by searching for titles, descriptions, dates, or keywords.</p>
    </slot>
    <div class="relative">
      <!-- Bind searchQuery with v-model -->
      <input v-model="searchQuery" type="text" class="w-full p-2 pl-10 text-sm text-gray-800 placeholder-gray-400 bg-white rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500" placeholder="Search for episodes...">
      <svg class="w-5 h-5 text-gray-400 absolute top-2.5 left-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
      </svg>
    </div>
    <transition name="slide-out">
      <!-- Updated to use the computed property for better control -->
      <div v-if="showResultsContainer" class="results-container mt-2 bg-gray-700 p-4 rounded-lg">
        <component
            :is="currentResultComponent"
            v-for="result in searchStore.searchResults"
            :key="result.id"
            :result="result"
        />
        <div class="pagination-placeholder text-gray-400">Pagination will go here</div>
      </div>
    </transition>

  </div>
</template>
<script setup>
import { computed, ref, watch } from 'vue'
import { useSearchStore } from '@/Stores/SearchStore'
import { debounce } from 'lodash'
import TeamShowEpisodesResult from '@/Components/Global/Search/TeamShowEpisodesResult.vue'
import ShowShowEpisodesResult from '@/Components/Global/Search/ShowShowEpisodesResult.vue'
import DefaultSearchResult from '@/Components/Global/Search/DefaultSearchResult.vue'

const searchStore = useSearchStore()

// Mapping of model and type to specific components
const componentMap = {
  teams: {
    showEpisodes: TeamShowEpisodesResult,
  },
  shows: {
    showEpisodes: ShowShowEpisodesResult,
  }
};

const currentResultComponent = computed(() => {
  const { currentModel, currentType } = searchStore;
  // Retrieve component based on model and type or fall back to a default component
  return (componentMap[currentModel] && componentMap[currentModel][currentType]) || DefaultSearchResult;
});

// Set up reactive reference for the search query
const searchQuery = ref('');

// const searchResults = ref([]);
// const showResults = ref(false);

// Debounce the search function to limit backend calls
const debounceSearch = debounce(async () => {
  if (searchQuery.value) {
    await searchStore.performSearch(searchStore.currentModel, searchStore.currentSlug, searchQuery.value);
    searchStore.showResults = true;
  } else {
    // Hide results when there is no query
    searchStore.showResults = false;
  }
}, 300);

const showResultsContainer = computed(() => {
  return searchStore.showResults && searchQuery.value.length > 0;
});

// Watch the searchQuery to trigger the search
watch(searchQuery, () => {
  debounceSearch();
});

// // Component watches the search store's searchResults to react to changes
// watch(() => searchStore.searchResults, (newResults) => {
//   if (newResults.length > 0) {
//     // Process or react to new results
//     console.log('New search results:', newResults);
//   }
// });
</script>

<style>
.slide-out-enter-active, .slide-out-leave-active {
  transition: transform 0.3s;
}
.slide-out-enter-from, .slide-out-leave-to {
  transform: translateY(100%);
}
</style>