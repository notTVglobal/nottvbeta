// Import necessary functions from Pinia
import { defineStore } from 'pinia';

// Define the store
export const useSearchStore = defineStore('searchStore', {
    // Define the initial state of the store
    state: () => ({
        searchResults: [], // Array to hold the search results
        showResults: false,
        isLoading: false, // Boolean to track if a search request is ongoing
        errorMessage: '', // String to store any error messages from search requests
        searchQuery: '', // The query string used for the search
        currentModel: 'teams', // The model currently being searched
        currentSlug: '', // The slug of the current model item being searched
        searchType: 'showEpisodes', // Default search type, can be adjusted as needed
    }),
    actions: {
        // Perform a search based on a model and slug
        async performSearch(model, slug, type) {
            this.currentModel = model; // Update the model being searched
            this.currentSlug = slug; // Update the slug for the search
            this.searchType = type;
            this.isLoading = true; // Indicate that a search is starting
            this.searchResults = []; // Clear previous search results
            this.errorMessage = ''; // Clear any previous error messages

            try {
                // Construct the dynamic endpoint based on the model and slug
                const endpoint = `/api/search/${model}/${slug}`; // Ensure endpoint is correct
                console.log('Constructed endpoint:', endpoint);

                // Perform the API request using axios
                const response = await axios.get(endpoint, {
                    params: { // Use params to send query data
                        query: this.searchQuery,
                        type: type // Include the type of search
                    }
                });

                // Store the search results on successful fetch
                console.log('API response:', response.data);
                this.searchResults = response.data;
            } catch (error) {
                console.log('Error during search API call:', error.message);
                // Handle any errors that occur during the API request
                this.errorMessage = 'Failed to fetch results: ' + (error.response?.data?.message || error.message);
            } finally {
                // Set isLoading to false once the API request is complete, regardless of success or failure
                this.isLoading = false;
                console.log('Search completed:', { isLoading: this.isLoading, resultsFound: this.searchResults.length });
            }
        }
    }
});
