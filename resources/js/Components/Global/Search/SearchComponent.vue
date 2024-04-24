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
      <!-- Bind searchQuery with v-model to store's searchQuery -->
      <input v-model="searchStore.searchQuery"
             type="text"
             class="w-full p-2 pl-10 text-sm text-gray-800 placeholder-gray-400 bg-white rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500"
             placeholder="Search for episodes..."
             @input="debounceSearch" />
      <svg class="w-5 h-5 text-gray-400 absolute top-2.5 left-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
      </svg>
    </div>
    <transition name="slide-out">
      <!-- Show results conditionally -->
      <div v-if="showResultsContainer" class="results-container mt-2 bg-gray-700 p-4 rounded-lg">
        <!-- Use a component to render search results dynamically -->
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

const props = defineProps({
  currentModel: String,
  currentSlug: String,
  searchType: String,
})

// Computed property to determine which result component to use
const currentResultComponent = computed(() => {
  const { currentModel, searchType } = searchStore;
  const componentMap = {
    teams: {
      showEpisodes: TeamShowEpisodesResult,
    },
    shows: {
      showEpisodes: ShowShowEpisodesResult,
    }
  };

  return (componentMap[currentModel] && componentMap[currentModel][searchType]) || DefaultSearchResult;
});

// Debounce the search function
const debounceSearch = debounce(() => {
  if (searchStore.searchQuery) {
    searchStore.performSearch(props.currentModel, props.currentSlug, props.searchType);
    searchStore.showResults = true;
  } else {
    searchStore.showResults = false; // Hide results when there is no query
  }
}, 300);

const showResultsContainer = computed(() => {
  return searchStore.showResults && searchStore.searchQuery.length > 0;
});

// // Watch the searchQuery to trigger the search
// watch(searchQuery, () => {
//   debounceSearch();
// });

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