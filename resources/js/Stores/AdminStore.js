import { defineStore } from 'pinia'

const initialState = () => ({
    channels: [], // For Admin Channels page
    items: [], // for Admin Channels DynamicModal... ExternalSource, ChannelPlaylist, MistStream
    activeItemId: null,
    selectedChannel: null, // The currently active item
    currentType: '', // 'externalSource', 'channelPlaylist', 'mistStream'
    searchTerm: '',
    type: '',
    modalHeader: '',
    currentPage: 1,
    itemsPerPage: 10,
    currentChannelsPage: 1,
    itemsPerChannelsPage: 7,
    sourceSelector: {
        source: null,
        sourceType: null
    }
})

export const useAdminStore = defineStore('adminStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
        },

        loadChannels(channels) {
            this.channels = channels
        },
        fetchChannels() {
            const response = axios.get('/api/channels_list')
                .then(response => {
                    this.channels = response.data
                })
                .catch(error => {
                    console.log(error)
                })
        },
        setSelectedChannel(item) {
            this.selectedChannel = item
        },
        setCurrentType(type) {
            this.currentType = type
            this.modalHeader = type + ' list'
        },
        clearSelectedChannelAndItems() {
            this.selectedChannel = null
            this.items = []
            this.activeItemId = null
            this.currentType = ''
            this.modalHeader = ''
            this.currentPage = 1
            this.itemsPerPage = 10
            this.searchTerm = ''
        },
        // async searchItems(type, searchTerm = '') {
        //     this.currentType = type;
        //     this.searchTerm = searchTerm;
        //     // Assuming you have a function to perform the fetch; adjust accordingly
        //     const response = await fetch(`/admin/channels/search/${type}?search=${searchTerm}`);
        //     const data = await response.json();
        //     this.items = data.items;
        // },
        async fetchItems(type) {
            this.currentType = type
            if (this.currentType === 'mistStream') {
                // retrieve all mistStreams paginated.
                // Can Vue search through the items?
                const response = await fetch(`/mistStreams`)
                this.items = await response.json()
                if (this.selectedChannel.mist_stream_id) {
                    this.activeItemId = this.selectedChannel.mist_stream_id
                }
            } else if (this.currentType === 'externalSource') {
                // retrieve all externalSources
                const response = await fetch(`/externalSources`)
                this.items = await response.json()
                if (this.selectedChannel.channel_external_source_id) {
                    this.activeItemId = this.selectedChannel.channel_external_source_id
                }
            } else if (this.currentType === 'channelPlaylist') {
                // retrieve all channelPlaylists
                const response = await fetch(`/channelPlaylists`)
                this.items = await response.json()
                if (this.selectedChannel.channel_playlist_id) {
                    this.activeItemId = this.selectedChannel.channel_playlist_id
                }
            }
            // Assuming you have a function to perform the fetch; adjust accordingly
            // const response = await fetch(`/admin/channels/search/${type}?search=${searchTerm}`);
            // const data = await response.json();
            // this.items = data.items;
        },
        async updateActiveItemId(id) {
            this.activeItemId = id
            if (this.currentType === 'mistStream') {
                await this.setMistStream(this.selectedChannel, id)
            } else if (this.currentType === 'channelPlaylist') {
                await this.setChannelPlaylist(this.selectedChannel, id)
            } else if (this.currentType === 'externalSource') {
                await this.setExternalSource(this.selectedChannel, id)
            }
        },
        // Add more actions as needed
        // this next one should probably go in AdminChannelStore
        async setPlaybackPriorityType(channel, priorityType) {
            const dataToSend = {setPriorityType: priorityType}
            try {
                const response = await axios.post(`/admin/channels/${channel.id}/setPlaybackPriorityType`, dataToSend)
                if (response.data.success) {
                    this.fetchChannels()
                    // Find the index of the updated channel in the channels array
                    const index = this.channels.findIndex(c => c.id === channel.id)
                    if (index !== -1) {
                        // Update the channel in the channels array with the updated data
                        this.channels[index] = response.data.channel
                    }
                }
            } catch (error) {
                console.error(error)
            }
        },
        async setMistStream(channel, mistStreamId) {
            const dataToSend = {mistStreamId: mistStreamId}
            try {
                const response = await axios.post(`/admin/channels/${channel.id}/setMistStream`, dataToSend)
                if (response.data.success) {
                    this.fetchChannels()
                    // Find the index of the updated channel in the channels array
                    const index = this.channels.findIndex(c => c.id === channel.id)
                    if (index !== -1) {
                        // Update the channel in the channels array with the updated data
                        this.channels[index] = response.data.channel
                    }
                }
            } catch (error) {
                console.error(error)
            }
        },
        async setChannelPlaylist(channel, channelPlaylistId) {
            const dataToSend = {channelPlaylistId: channelPlaylistId}
            try {
                const response = await axios.post(`/admin/channels/${channel.id}/setChannelPlaylist`, dataToSend)
                if (response.data.success) {
                    this.fetchChannels()
                    // Find the index of the updated channel in the channels array
                    const index = this.channels.findIndex(c => c.id === channel.id)
                    if (index !== -1) {
                        // Update the channel in the channels array with the updated data
                        this.channels[index] = response.data.channel
                    }
                }
            } catch (error) {
                console.error(error)
            }
        },
        async setExternalSource(channel, externalSourceId) {
            const dataToSend = {mistStreamId: externalSourceId}
            try {
                const response = await axios.post(`/admin/channels/${channel.id}/setExternalSource`, dataToSend)
                if (response.data.success) {
                    this.fetchChannels()
                    // Find the index of the updated channel in the channels array
                    const index = this.channels.findIndex(c => c.id === channel.id)
                    if (index !== -1) {
                        // Update the channel in the channels array with the updated data
                        this.channels[index] = response.data.channel
                    }
                }
            } catch (error) {
                console.error(error)
            }
        },
        nextPage() {
            const totalPages = Math.ceil(this.items.length / this.itemsPerPage)
            if (this.currentPage < totalPages) this.currentPage++
        },
        prevPage() {
            if (this.currentPage > 1) this.currentPage--
        },
        setPage(page) {
            this.currentPage = page
        },
        nextChannelsPage() {
            const totalPages = Math.ceil(this.channels.length / this.itemsPerChannelsPage)
            if (this.currentChannelsPage < totalPages) this.currentChannelsPage++
        },
        prevChannelsPage() {
            if (this.currentChannelsPage > 1) this.currentChannelsPage--
        },
        setChannelsPage(page) {
            this.currentChannelsPage = page
        },
    },

    // Getters (if needed)
    getters: {
        filteredItems: (state) => state.items, // Placeholder, adjust if you need actual filtering
        currentItem: (state) => state.items.find((item) => item.id === state.activeItem),
        // setHeader: (state) => state.modalHeader = state.currentType + ' list',
        paginatedItems: (state) => {
            const start = (state.currentPage - 1) * state.itemsPerPage
            const end = start + state.itemsPerPage
            return state.items.slice(start, end)
        },
        paginatedChannels: (state) => {
            const start = (state.currentChannelsPage - 1) * state.itemsPerChannelsPage
            const end = start + state.itemsPerChannelsPage
            return state.channels.slice(start, end)
        },
        totalPages(state) {
            return Math.ceil(state.items.length / state.itemsPerPage)
        },
        totalChannelsPages(state) {
            return Math.ceil(state.channels.length / state.itemsPerChannelsPage)
        },
    },
})
