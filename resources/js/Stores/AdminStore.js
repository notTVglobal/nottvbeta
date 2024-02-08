import { defineStore } from 'pinia';

const initialState = () => ({
    items: [],
    selectedChannel: null, // The currently active item
    currentType: '', // 'externalSource', 'channelPlaylist', 'mistStream'
    searchTerm: '',
    type: '',
    modalHeader: ''
})

export const useAdminStore = defineStore('adminStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
        },
        setSelectedChannel(item) {
            this.selectedChannel = item;
        },
        setCurrentType(type) {
            this.currentType = type;
            this.modalHeader = type + ' list'
        },
        async searchItems(type, searchTerm = '') {
            this.currentType = type;
            this.searchTerm = searchTerm;
            // Assuming you have a function to perform the fetch; adjust accordingly
            const response = await fetch(`/admin/channels/search/${type}?search=${searchTerm}`);
            const data = await response.json();
            this.items = data.items;
        },
        async fetchItems(type) {
            this.currentType = type;
            if (this.currentType === 'mistStream') {
                // retrieve all mistStreams paginated.
                // Can Vue search through the items?
                const response = await fetch(`/mistStreams`);
                const data = await response.json();
                console.log('fetch response: ' + data)
                this.items = data;
            } else if (this.currentType === 'externalSource') {
                // retrieve all externalSources
                const response = await fetch(`/externalSources`);
                const data = await response.json();
                console.log('fetch response: ' + data)
                this.items = data;
            } else if (this.currentType === 'channelPlaylist') {
                // retrieve all channelPlaylists
                const response = await fetch(`/channelPlaylists`);
                const data = await response.json();
                console.log('fetch response: ' + data)
                this.items = data;
            }
            // Assuming you have a function to perform the fetch; adjust accordingly
            const response = await fetch(`/admin/channels/search/${type}?search=${searchTerm}`);
            const data = await response.json();
            this.items = data.items;
        },
        updateActiveItemId(id) {
            this.activeItemId = id;
        },
        // Add more actions as needed
    },

    // Getters (if needed)
    getters: {
        filteredItems: (state) => state.items, // Placeholder, adjust if you need actual filtering
        currentItem: (state) => state.items.find((item) => item.id === state.activeItem),
        // setHeader: (state) => state.modalHeader = state.currentType + ' list',
    }
});
