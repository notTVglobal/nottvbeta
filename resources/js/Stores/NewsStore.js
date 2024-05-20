import { defineStore } from 'pinia'
import { Inertia } from '@inertiajs/inertia'

const initialState = () => ({
    id: null,
    slug: '',
    title: '',
    content: '',
    newsPerson: [],
    category: {},
    subCategory: {},
    city: {},
    province: {},
    federalElectoralDistrict: {},
    subnationalElectoralDistrict: {},
    country: [],
    image: {},
    status: [],
    video: {},
    created_at: '',
    published_at: '',
    cachedContent: {},

    categories: [],
    subCategories: [],

    type: '',
    displayText: '',

    newsPersons: [],
    // selectedCategory: [], // initially no selected category
    // selectedSubcategory: [],
    // selectedLocation: [], // the selected location
    // selectedNewsPerson: [],

    citySearchItems: [], // array of locations for the dropdown

    showEditor: false,
    showNewsPersonSelector: false,
    showCategoryCitySelector: false,
    citySelectDropdownVisible: false, // visibility of the dropdown
    showSaveMessage: false,

    focusedIndex: 0, // for managing focused item in the dropdown
    searchQuery: '',
    filters: [],
    search: '',
    errors: [],

    newsStoryStatuses: {},
    newsStoryId: '',

    isLoadingCategoryCityData: false,
    isLoading: true,
    processing: false,

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
        initializeNewsStore(newsStory) {
            this.id = newsStory.id
            this.slug = newsStory.slug
            this.title = newsStory.title
            this.status = newsStory.status
            this.content = newsStory.content
            this.newsPerson = newsStory.newsPerson
            this.category = newsStory.category
            this.subCategory = newsStory.subCategory
            this.city = newsStory.city
            this.province = newsStory.province
            this.federalElectoralDistrict = newsStory.federalElectoralDistrict
            this.subnationalElectoralDistrict = newsStory.subnationalElectoralDistrict
            this.image = newsStory.image
            this.video = newsStory.video
            this.created_at = newsStory.created_at
            this.published_at = newsStory.published_at
            this.cachedContent = newsStory.cachedContent
        },
        toggleCategoryCitySelector() {
            this.showCategoryCitySelector = !this.showCategoryCitySelector
        },
        toggleNewsPersonSelector() {
            this.showNewsPersonSelector = !this.showNewsPersonSelector
        },
        // Action to set the selected Category
        setSelectedCategory() {
            let matchingCategory = this.categories.find(category => category.id === this.news_category_id)
            this.selectedCategory = matchingCategory || null
            if (this.news_category_sub_id) {
            }
            // Check if the selected category is 3 (Local News)
            if (this.news_category_id === 3) {
                this.setSelectedLocation() // Set the Location for Local News
            } else {
                this.resetLocationIds() // Reset location-related IDs for other categories
            }
        },
        async fetchCategories() {
            this.isLoadingCityData = true
            try {
                const response = await fetch('/api/news/categories')
                if (response.ok) {
                    this.categories = await response.json()
                } else {
                    console.error('Failed to fetch categories:', response.statusText)
                }
            } catch (error) {
                console.error('Error fetching categories:', error)
            } finally {
                this.isLoadingCityData = false
            }
        },
        async fetchCitiesForSearch() {
            try {
                const response = await fetch('/api/news/cities')
                if (response.ok) {
                    this.citySearchItems = await response.json()
                } else {
                    // Handle HTTP error responses (e.g., 404, 500)
                    console.error('Failed to fetch locations:', response.statusText)
                }
                this.isLoadingCityData = false
            } catch (error) {
                // Handle errors that occur during the fetch operation
                console.error('Error fetching locations:', error)
                this.isLoadingCityData = false
            }
        },
        // Action to set the selected Location
        setSelectedLocation() {
            // Reset all location types
            this.city = {}
            this.province = {}
            this.federalElectoralDistrict = {}
            this.subnationalElectoralDistrict = {}

            let matchingLocation = null

            // Simplified conditional logic
            if (this.city.id) {
                matchingLocation = this.citySearchItems.find(location => location.id === this.city.id && (location.type === 'city' || location.type === 'town'))
            } else if (this.province.id) {
                matchingLocation = this.citySearchItems.find(location => location.id === this.province.id && location.type === 'province')
            } else if (this.federalElectoralDistrict.id) {
                matchingLocation = this.citySearchItems.find(location => location.id === this.federalElectoralDistrict.id && location.type === 'federalElectoralDistrict')
            } else if (this.subnationalElectoralDistrict.id) {
                matchingLocation = this.citySearchItems.find(location => location.id === this.subnationalElectoralDistrict.id && location.type === 'subnationalElectoralDistrict')
            }

            // Handling null case
            if (matchingLocation) {
                // Set the appropriate location object
                switch (matchingLocation.type) {
                    case 'city':
                    case 'town':
                        this.city = {
                            id: matchingLocation.id,
                            name: matchingLocation.name,
                            type: matchingLocation.type,
                        }
                        this.province = {
                            id: matchingLocation.province.id,
                            name: matchingLocation.province.name,
                        }
                        this.displayText = `${matchingLocation.name}, ${matchingLocation.province.name}`
                        break
                    case 'province':
                        this.province = {
                            id: matchingLocation.id,
                            name: matchingLocation.name,
                            type: matchingLocation.type,
                        }
                        this.displayText = matchingLocation.name
                        break
                    case 'federalElectoralDistrict':
                        this.federalElectoralDistrict = {
                            id: matchingLocation.id,
                            name: matchingLocation.name,
                            type: matchingLocation.type,
                        }
                        this.displayText = matchingLocation.name
                        break
                    case 'subnationalElectoralDistrict':
                        this.subnationalElectoralDistrict = {
                            id: matchingLocation.id,
                            name: matchingLocation.name,
                            type: matchingLocation.type,
                        }
                        this.displayText = matchingLocation.name
                        break
                    default:
                        this.displayText = ''
                        break
                }
            } else {
                // Handle the case where no matching location is found
                this.displayText = ''
            }
        },
        async fetchCitiesAndCategories() {
            // console.log("News story type:", newsStory.type); // Or relatedData.type, depending on your structure
            await this.fetchCitiesForSearch() // Load location items first
            this.setSelectedLocation() // Then set the selected location
        },
        // Action to set the selected Location
        updateSelectedLocation(location) {
            if (!location) {
                // Handle the case where no location is passed
                this.city = {};
                this.province = {};
                this.federalElectoralDistrict = {};
                this.subnationalElectoralDistrict = {};
                this.displayText = '';
                return;
            }

            // Update the selectedLocation based on the type of location
            this.selectedLocation = location
            this.displayText = this.getDisplayTextForType(location.type)

            // Reset all location types
            this.city = {};
            this.province = {};
            this.federalElectoralDistrict = {};
            this.subnationalElectoralDistrict = {};

            // Update the selected location based on the type of location
            this.displayText = this.getDisplayTextForType(location.type);

            switch (location.type) {
                case 'city':
                case 'town':
                    this.city = {
                        id: location.id,
                        name: location.name,
                        province: location.province,
                    };
                    this.province = {
                        id: location.province.id,
                        name: location.province.name,
                    };
                    this.displayText = `${location.name}, ${location.province.name}`;
                    break;
                case 'province':
                case 'territory':
                    this.province = {
                        id: location.id,
                        name: location.name,
                        type: location.type,
                    };
                    this.displayText = location.name;
                    break;
                case 'federalElectoralDistrict':
                    this.federalElectoralDistrict = {
                        id: location.id,
                        name: location.name,
                        type: location.type,
                    };
                    this.displayText = location.name;
                    break;
                case 'subnationalElectoralDistrict':
                    this.subnationalElectoralDistrict = {
                        id: location.id,
                        name: location.name,
                        type: location.type,
                    };
                    this.displayText = location.name;
                    break;
                default:
                    this.displayText = '';
                    break;
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
            this.search = query
        },
        async fetchNewsPersons() {
            console.log('get news persons')
            try {
                const response = await fetch('/api/news/persons')
                this.newsPersons = await response.json()
            } catch (error) {
                console.error('Failed to fetch news persons:', error)
            }
        },
        setNewsPerson(newNewsPerson) {
            this.newsPerson = newNewsPerson
        },
        async submit() {
            this.processing = true

            const data = {
                id: this.id,
                title: this.title,
                status: this.status.id,
                content: this.content,
                news_category_id: this.category.id,
                news_category_sub_id: this.subCategory.id,
                city_id: this.city.id,
                province_id: this.province.id,
                federal_electoral_district_id: this.federalElectoralDistrict.id,
                subnational_electoral_district_id: this.subnationalElectoralDistrict.id,
                type: this.type,
                news_person_id: this.newsPerson.id,
            }

            try {
                if (this.id) {
                    await Inertia.patch(route('newsStory.update', this.slug), data, {
                        onError: (errors) => {
                            this.errors = errors
                            this.processing = false
                        },
                        onSuccess: () => {
                            this.processing = false
                        }
                    })
                } else {
                    await Inertia.post(route('newsStory.store'), data, {
                        onError: (errors) => {
                            this.errors = errors
                            this.processing = false
                        },
                        onSuccess: () => {
                            this.processing = false
                        }
                    })
                }
            } catch (error) {
                console.error('An unexpected error occurred:', error)
                this.processing = false
            }
        },
        changeNewsStoryStatus(statusId) {
            document.getElementById('newsStoryStatusChangeModal').close()
            Inertia.patch(route('news.story.changeStatus'), {
              newsStory_id: this.newsStoryId, // Assuming you have the ID available in `newsStory`
              new_status_id: statusId
            }, {
              onError: (errors) => {
                console.error('Error changing status:', errors);
              }
            });
        },
    },

    getters: {
        filteredCitySearchItems: (state) => {
            if (!state.search) return state.citySearchItems
            return state.citySearchItems.filter(item =>
                item.name.toLowerCase().includes(state.search.toLowerCase()),
            )
        },
        searchInput: (state) => {
            if (state.city.id) {
                return `${state.city.name}, ${state.province.name}`;
            } else if (state.province.id) {
                return state.province.name;
            } else if (state.federalElectoralDistrict.id) {
                return state.federalElectoralDistrict.name;
            } else if (state.subnationalElectoralDistrict.id) {
                return state.subnationalElectoralDistrict.name;
            }
            return state.search;
        },
        locationType(state) {
            if (!state.selectedLocation) {
                state.type = ''
                state.displayText = ''
                return 'location type is not set'
            }

            // Assuming `selectedLocation` has a property to indicate its type
            // Adjust the logic based on how you determine the type
            switch (state.selectedLocation.type) {
                case 'city':
                    state.type = 'city'
                    state.displayText = 'City'
                    break
                case 'town':
                    state.type = 'town'
                    state.displayText = 'Town'
                    break
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

            return 'location type is set'
        },
        // filteredNewsPersons: (state) => {
        //     if (!state.searchQuery) return state.newsPersons;
        //     return state.newsPersons.filter(person =>
        //         person.user.name.toLowerCase().includes(state.searchQuery.toLowerCase())
        //     );
        // },
    },

})

