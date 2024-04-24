<template>
  <div class="bg-gray-800 px-4 pt-2 pb-8 rounded-lg shadow-lg w-full mx-auto mb-8">

    <div class="max-w-md mx-auto">
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
    </div>
    <div class="relative max-w-md mx-auto">
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
      <div v-if="showResultsContainer" class="results-container mt-4 bg-gray-700 p-4 rounded-lg grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Use a component to render search results dynamically -->
        <component
            :is="currentResultComponent"
            v-for="result in searchStore.visibleResults"
            :key="result.id"
            :result="result"
        />
      </div>

    </transition>
    <!-- Sentinel element for Intersection Observer -->
    <div ref="sentinel" class="sentinel"></div>
    <!-- Optional: Display a loading indicator or "Load more" button -->
    <p v-if="searchStore.isLoading" class="text-center text-white">Loading...</p>

  </div>
</template>
<script setup>
import { computed, onMounted, onUnmounted, ref, watch } from 'vue'
import { useSearchStore } from '@/Stores/SearchStore'
import { debounce } from 'lodash'
import _ from 'lodash';
import TeamShowEpisodesResult from '@/Components/Global/Search/TeamShowEpisodesResult.vue'
import ShowShowEpisodesResult from '@/Components/Global/Search/ShowShowEpisodesResult.vue'
import DefaultSearchResult from '@/Components/Global/Search/DefaultSearchResult.vue'

const searchStore = useSearchStore()
const sentinel = ref(null);

const props = defineProps({
  modelType: String,
  modelId: String,
  modelSlug: String,
})

const observer = new IntersectionObserver(entries => {
  const entry = entries[0];
  if (entry.isIntersecting && searchStore.canLoadMore) {
    searchStore.loadMoreItems();
  }
}, {
  root: null,
  rootMargin: '0px',
  threshold: 1.0
});

onMounted(() => {
  if (sentinel.value) {
    observer.observe(sentinel.value);
  }
});

onUnmounted(() => {
  if (sentinel.value) {
    observer.unobserve(sentinel.value);
  }

// Computed property to determine which result component to use
  const currentResultComponent = computed(() => {
    // Map model keys to their respective components
    const componentMap = {
      team: TeamShowEpisodesResult,
      show: ShowShowEpisodesResult
    };

    // Retrieve the component based on the current model or default to DefaultSearchResult
    return componentMap[searchStore.currentModel] || DefaultSearchResult;
  });

// Debounce the search function
  const debounceSearch = debounce(() => {
    if (searchStore.searchQuery) {
      searchStore.performShowEpisodeSearch(props.modelType, props.modelId, props.modelSlug);
      searchStore.showResults = true;
    } else {
      searchStore.showResults = false; // Hide results when there is no query
    }
  }, 300);
})

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
.sentinel {
  height: 20px;  /* just enough height to trigger the observer */
}
</style>