// Import necessary functions from Pinia
import { defineStore } from 'pinia';

// Define the store
export const useSearchStore = defineStore('searchStore', {
    // Define the initial state of the store
    state: () => ({
        searchResults: [], // Array to hold the search results
        itemsPerPage: 9,
        totalVisible: 9,  // Initially visible items
        showResults: false,
        isLoading: false, // Boolean to track if a search request is ongoing
        errorMessage: '', // String to store any error messages from search requests
        searchQuery: '', // The query string used for the search
        currentModel: '', // The model currently being searched
        currentModelId: '', // The slug of the current model item being searched
        currentModelSlug: '', // The slug of the current model item being searched
    }),
    actions: {
        // Perform a search based on a model and slug
        async performShowEpisodeSearch(modelType, id, slug) {
            this.currentModel = modelType; // Update the model being searched
            this.currentModelId = id; // Update the slug for the search
            this.currentModelSlug = slug; // Update the slug for the search
            this.isLoading = true; // Indicate that a search is starting
            this.searchResults = []; // Clear previous search results
            this.errorMessage = ''; // Clear any previous error messages

            try {
                // Construct the dynamic endpoint based on the model and slug
                const endpoint = `/api/search/${modelType}/${id}/episodes`; // Ensure endpoint is correct
                // console.log('Constructed endpoint:', endpoint);

                // Perform the API request using axios
                const response = await axios.get(endpoint, {
                    params: { // Use params to send query data
                        query: this.searchQuery,
                    }
                });

                // Store the search results on successful fetch
                // console.log('API response:', response.data);
                this.searchResults = response.data;
                this.pagination = response.data.pagination; // Assuming 'pagination' is part of the response
            } catch (error) {
                // console.log('Error during search API call:', error.message);
                // Handle any errors that occur during the API request
                this.errorMessage = 'Failed to fetch results: ' + (error.response?.data?.message || error.message);
            } finally {
                // Set isLoading to false once the API request is complete, regardless of success or failure
                this.isLoading = false;
                // console.log('Search completed:', {isLoading: this.isLoading, resultsFound: this.searchResults.length});
            }
        },
        loadMoreItems() {
            if (this.canLoadMore) {
                this.totalVisible += this.itemsPerPage;
            }
        },
    },
        getters: {
            // Return only the portion of results that should be currently visible
            visibleResults: (state) => {
                return state.searchResults.slice(0, state.totalVisible);
            },
            canLoadMore: (state) => {
                return state.totalVisible < state.searchResults.length;
            },
        }
});
