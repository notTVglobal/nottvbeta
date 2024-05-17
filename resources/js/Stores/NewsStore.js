import { defineStore } from 'pinia'

const initialState = () => ({
    newsStory: [],
    newsArticleIdTiptop: '',
    newsArticleTitleTiptop: '',
    newsArticleContentTiptop: '',
    content_json: {},
    news_category_id: null,
    news_category_sub_id: null,
    categories: [],
    subcategories: [],
    city_id: 0,
    province_id: 0,
    federal_electoral_district_id: 0,
    subnational_electoral_district_id: 0,
    type: '',
    displayText: '',
    country: [],
    selectedCategory: [], // initially no selected category
    selectedSubcategory: [],
    selectedLocation: [], // the selected location
    locationSearchItems: [], // array of locations for the dropdown
    citySelectDropdownVisible: false, // visibility of the dropdown
    focusedIndex: 0, // for managing focused item in the dropdown
    searchQuery: '',
    filters: [],
    search: '',
    formErrors: [],
    isLoadingCategoryCityData: false,
    newsPersons: [],

    // Computed property for displaying
})

export const useNewsStore = defineStore('newsStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
            // await this.setSelectedLocation()
        },
        // load NewsStory props into NewsStore
        loadNewsStory(newsStory) {
            this.newsStory = newsStory
        },
        updateNewsStoryData(newsStory, relatedData) {
            this.newsStory = newsStory
            this.newsArticleIdTiptop = newsStory.id
            this.newsArticleTitleTiptop = newsStory.title
            this.newsArticleContentTiptop = JSON.parse(newsStory.content_json)
            this.content_json = JSON.parse(newsStory.content_json)
            this.news_category_id = newsStory.news_category_id
            this.news_category_sub_id = newsStory.news_category_sub_id
            this.city_id = newsStory.city_id
            this.province_id = newsStory.province_id
            this.federal_electoral_district_id = newsStory.federal_electoral_district_id
            this.subnational_electoral_district_id = newsStory.subnational_electoral_district_id
            this.type = newsStory.type
            // Set related data that isn't part of the newsStory object
            this.country = relatedData.country
            this.categories = relatedData.categories
            // Any additional related data setup can go here
        },
        // Action to set the selected Category
        setSelectedCategory() {
            let matchingCategory = this.categories.find(category => category.id === this.news_category_id)
            this.selectedCategory = matchingCategory || null
            if (this.news_category_sub_id) {
                this.getSubcategories() // After setting the category, get the subcategory
                this.getSelectedSubcategory() // Call after subcategories are populated
            }
            // Check if the selected category is 3 (Local News)
            if (this.news_category_id === 3) {
                this.setSelectedLocation() // Set the Location for Local News
            } else {
                this.resetLocationIds() // Reset location-related IDs for other categories
            }
        },
        // Action to get the Subcategories
        getSubcategories() {

            // Find the matching category using selectedCategory.id
            const matchingCategory = this.categories.find(category => category.id === this.selectedCategory.id)

            if (matchingCategory) {
                // Set the subcategories from the matched category
                this.subcategories = matchingCategory.news_category_subs
            } else {
                // Handle the case where no matching category is found
                this.subcategories = [] // Set subcategories to an empty array
                console.log('No matching category found')
            }
        },

        getSelectedSubcategory() {
            // console.log('start getting selected subcategory')

            // Find the subcategory that matches the news_category_sub_id
            const matchingSubcategory = this.subcategories.find(subcategory => subcategory.id === this.news_category_sub_id)

            if (matchingSubcategory) {
                // Set the selectedSubcategory to the found subcategory
                this.selectedSubcategory = matchingSubcategory
                // console.log('selected subcategory updated:', this.selectedSubcategory)
            } else {
                // Handle the case where no matching subcategory is found
                this.selectedSubcategory = null
                // console.log('No matching subcategory found')
            }
        },

        async fetchLocationsForSearch() {
            try {
                const response = await fetch('/api/news-locations')
                if (response.ok) {
                    this.locationSearchItems = await response.json()
                } else {
                    // Handle HTTP error responses (e.g., 404, 500)
                    console.error('Failed to fetch locations:', response.statusText)
                }
                this.isLoadingCategoryCityData = false
            } catch (error) {
                // Handle errors that occur during the fetch operation
                console.error('Error fetching locations:', error)
                this.isLoadingCategoryCityData = false
            }
        },

        // Action to set the selected Location
        setSelectedLocation() {
            let matchingLocation = null

            // Simplified conditional logic
            if (this.city_id) {
                matchingLocation = this.locationSearchItems.find(location => location.city_id === this.city_id)
            } else if (this.province_id) {
                matchingLocation = this.locationSearchItems.find(location => location.province_id === this.province_id && !location.city_id)
            } else if (this.federal_electoral_district_id) {
                matchingLocation = this.locationSearchItems.find(location => location.federal_electoral_district_id === this.federal_electoral_district_id)
            } else if (this.subnational_electoral_district_id) {
                matchingLocation = this.locationSearchItems.find(location => location.subnational_electoral_district.id === this.subnational_electoral_district_id)
            }

            // Handling null case
            if (matchingLocation) {
                this.selectedLocation = matchingLocation

                // Set displayText based on type
                switch (this.type) {
                    case 'city':
                        this.displayText = 'City'
                        this.type = 'city'
                        break
                    case 'town':
                        this.displayText = 'Town'
                        this.type = 'town'
                        break
                    case 'province':
                        this.displayText = 'Province'
                        this.type = 'province'
                        break
                    case 'territory':
                        this.displayText = 'Territory'
                        this.type = 'territory'
                        break
                    case 'federalElectoralDistrict':
                        this.displayText = 'Federal Electoral District'
                        this.type = 'federalElectoralDistrict'
                        break
                    case 'subnationalElectoralDistrict':
                        this.displayText = 'Subnational Electoral District'
                        this.type = 'subnationalElectoralDistrict'
                        break
                    default:
                        this.displayText = ''
                        break
                }
            } else {
                // Handle the case where no matching location is found
                // this.selectedLocation = null
                // this.selectedType = null
                // this.city_id = null // or keep the existing value?
                // this.type = null
                // this.displayText = ''
            }
        },
        async updateNewsStoryAndSetLocation(newsStory, relatedData) {
            // console.log("News story type:", newsStory.type); // Or relatedData.type, depending on your structure
            await this.fetchLocationsForSearch(); // Load location items first
            // console.log("Locations fetched");
            //
            // // Example: Set this.type based on newsStory or relatedData if applicable
            // this.type = newsStory.type || relatedData.type; // Adjust based on your data structure
            // console.log("Type set to:", this.type);

            this.setSelectedLocation(); // Then set the selected location
            // console.log("Selected location set");
            // Finally, update the news story data
            this.updateNewsStoryData(newsStory, relatedData);
        },
// Action to set the selected Location
        updateSelectedLocation(location) {
            if (!location) {
                // Handle the case where no location is passed
                this.selectedLocation = null
                this.displayText = ''
                this.resetLocationIds(null)
                return
            }

            // Update the selectedLocation based on the type of location
            this.selectedLocation = location
            this.displayText = this.getDisplayTextForType(location.type)

            // Update the relevant IDs based on the type
            this.city_id = null
            this.province_id = null
            this.federal_electoral_district_id = null
            this.subnational_electoral_district_id = null

            switch (location.type) {
                case 'city':
                case 'town':
                    this.city_id = location.city_id
                    // Assuming province_id is also part of city data
                    this.province_id = location.province_id || null
                    break
                case 'province':
                case 'territory':
                    this.province_id = location.province_id
                    break
                case 'federalElectoralDistrict':
                    this.federal_electoral_district_id = location.federal_electoral_district_id
                    break
                case 'subnationalElectoralDistrict':
                    this.subnational_electoral_district_id = location.subnational_electoral_district_id
                    break
                // Add more cases as needed for other types
            }
        },

// Helper function to get display text based on type
        getDisplayTextForType(type) {
            switch (type) {
                case 'city':
                    this.type = 'city'
                    return 'City'
                case 'town':
                    this.type = 'town'
                    return 'Town'
                case 'province':
                    this.type = 'province'
                    return 'Province'
                case 'territory':
                    this.type = 'territory'
                    return 'Territory'
                case 'federalElectoralDistrict':
                    this.type = 'federalElectoralDistrict'
                    return 'Federal Electoral District'
                case 'subnationalElectoralDistrict':
                    this.type = 'subnationalElectoralDistrict'
                    return 'Subnational Electoral District'
                default:
                    return ''
            }
        },

        // Setter action for searchInput
        setSearchInput(value) {
            if (this.selectedLocation?.name !== value) {
                this.selectedLocation = null
            }
            this.search = value
        },

        // Helper function to reset location IDs
        resetLocationIds(matchingLocation) {
            this.city_id = null
            this.province_id = null
            this.federal_electoral_district_id = null
            this.subnational_electoral_district_id = null

            if (matchingLocation) {
                switch (matchingLocation.type) {
                    case 'city':
                        this.city_id = matchingLocation.city_id
                        this.province_id = matchingLocation.province_id // Assuming province_id is also part of city data
                        break
                    case 'province':
                    case 'territory':
                        this.province_id = matchingLocation.province_id
                        break
                    case 'federalElectoralDistrict':
                        this.federal_electoral_district_id = matchingLocation.federal_electoral_district_id
                        break
                    case 'subnationalElectoralDistrict':
                        this.subnational_electoral_district_id = matchingLocation.subnational_electoral_district_id
                        break
                    // ... handle other types if needed
                }
            }
        },
        updateSearch(query) {
            this.search = query;
        },
        async fetchNewsPersons() {
            try {
                const response = await fetch('/api/news-persons');
                this.newsPersons = await response.json();
            } catch (error) {
                console.error('Failed to fetch news persons:', error);
            }
        },
    },

    getters: {
        filteredLocationSearchItems: (state) => {
            if (!state.search) return state.locationSearchItems;
            return state.locationSearchItems.filter(item =>
                item.name.toLowerCase().includes(state.search.toLowerCase())
            );
        },
        searchInput: (state) => {
            if (state.selectedLocation) {
                if (state.selectedLocation.type === 'city' || state.selectedLocation.type === 'town') {
                    return `${state.selectedLocation.name}, ${state.selectedLocation.province_name}`
                }
                return state.selectedLocation.name
            }
            return state.search
        },
        locationType(state) {
            if (!state.selectedLocation) {
                state.type = ''
                state.displayText = ''
                return 'location type is not set';
            }

            // Assuming `selectedLocation` has a property to indicate its type
            // Adjust the logic based on how you determine the type
            switch (state.selectedLocation.type) {
                case 'city':
                    state.type = 'city';
                    state.displayText = 'City';
                    break;
                case 'town':
                    state.type = 'town';
                    state.displayText = 'Town';
                    break;
                case 'province':
                    state.displayText = 'Province'
                    state.type = 'province'
                    break
                case 'territory':
                    state.displayText = 'Territory'
                    state.type = 'territory'
                    break
                case 'federalElectoralDistrict':
                    state.displayText = 'Federal Electoral District'
                    state.type = 'federalElectoralDistrict'
                    break
                case 'subnationalElectoralDistrict':
                    state.displayText = 'Subnational Electoral District'
                    state.type = 'subnationalElectoralDistrict'
                    break
                default:
                    state.displayText = ''
                    break
            }

            return 'location type is set';
        },
        // filteredNewsPersons: (state) => {
        //     if (!state.searchQuery) return state.newsPersons;
        //     return state.newsPersons.filter(person =>
        //         person.user.name.toLowerCase().includes(state.searchQuery.toLowerCase())
        //     );
        // },
    },

})

