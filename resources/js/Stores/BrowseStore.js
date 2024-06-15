import { defineStore } from 'pinia'

const initialState = () => ({
    teams: [],
    currentPage: 1,
    lastPage: null,
    isLoading: false,
    filters: {
        search: '',
    },
})

export const useBrowseStore = defineStore('browseStore', {
    state: initialState,
    actions: {
        reset() {
            Object.assign(this, initialState())
        },
        async fetchTeams(page = 1) {
            this.isLoading = true
            try {
                const response = await axios.get('/browse/fetch-teams', {
                    params: { page, search: this.filters.search },
                })
                const { data, meta } = response.data
                this.teams = page === 1 ? data : [...this.teams, ...data]
                this.currentPage = meta.current_page
                this.lastPage = meta.last_page
            } catch (error) {
                console.error('Error fetching teams:', error)
            } finally {
                this.isLoading = false
            }
        },
        clearSearch() {
            this.filters.search = ''
        }
    },
})
