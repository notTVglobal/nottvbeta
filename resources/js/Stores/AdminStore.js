import { defineStore } from 'pinia'
import { useNotificationStore } from '@/Stores/NotificationStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'

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
    },
    firstPlaySettings: {
        useCustomVideo: false,
        customVideoSource: '',
        customVideoSourceType: '',
    },
    validationErrors: {},
    checkSendProcessing: false,
    activeStreams: [],
    fetchingActiveStreams: false,
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
        setCheckSendProcessing() {
          this.checkSendProcessing = true
        },
        unsetCheckSendProcessing() {
            this.checkSendProcessing = false
        },
        async fetchChannels() {
            await axios.get('/api/channels_list')
                .then(response => {
                    this.channels = response.data
                })
                .catch(error => {
                    console.log(error)
                })
        },
        async toggleChannelActiveStatus(channelId) {
            const notificationStore = useNotificationStore();
            try {
                const response = await axios.post('/admin/channels/' + channelId + '/toggleChannelActive');
                // Extract message and status from the response
                const { message, status } = response.data;
                // Use the status from the response for the notification
                notificationStore.setToastNotification(message, status);
            } catch (error) {
                console.error(error);
                notificationStore.setToastNotification('Failed to toggle channel status.', 'error');
            }
        },
        async addChannel(name) {
            const notificationStore = useNotificationStore();
            try {
                const response = await axios.post('/admin/channels/add', {'name': name});

                if (response.data.success) {
                    // Operation was a success
                    await this.fetchChannels();
                    notificationStore.setToastNotification(response.data.message, 'success');
                } else {
                    // Handle logical errors even when the HTTP response was OK
                    // Assuming 'status' and 'message' are part of the error response
                    const { message, status } = response.data;
                    let errorMessage = 'Failed to add channel due to a server error.';

                    if (status === 'error' && message && message.fallbackMessages) {
                        // Construct a more detailed error message based on validation feedback
                        const validationMessages = Object.values(message.fallbackMessages)
                            .map(msgs => msgs.join(' ')) // Join messages if there are multiple for one field
                            .join('; '); // Separate field messages with semicolons

                        errorMessage = validationMessages || errorMessage;
                    }

                    notificationStore.setToastNotification(errorMessage, 'error');
                }
            } catch (error) {
                console.error(error);
                notificationStore.setToastNotification('Failed to add channel due to a network or server error.', 'error');
            }
        },
        async updateChannel(channelId, newName) {
            const notificationStore = useNotificationStore();
            try {
                const response = await axios.post(`/admin/channels/${channelId}`, {'name': newName});

                if (response.data.success) {
                    // Operation was a success
                    await this.fetchChannels();
                    notificationStore.setToastNotification(response.data.message, 'success');
                } else {
                    // Handle logical errors even when the HTTP response was OK
                    // Assuming 'status' and 'message' are part of the error response
                    const { message, status } = response.data;
                    let errorMessage = 'Failed to add channel due to a server error.';

                    if (status === 'error' && message && message.fallbackMessages) {
                        // Construct a more detailed error message based on validation feedback
                        const validationMessages = Object.values(message.fallbackMessages)
                            .map(msgs => msgs.join(' ')) // Join messages if there are multiple for one field
                            .join('; '); // Separate field messages with semicolons

                        errorMessage = validationMessages || errorMessage;
                    }

                    notificationStore.setToastNotification(errorMessage, 'error');
                }
            } catch (error) {
                console.error(error);
                notificationStore.setToastNotification('Failed to add channel due to a network or server error.', 'error');
            }
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
            const totalModalPages = Math.ceil(this.items.length / this.itemsPerPage)
            if (this.currentPage < totalModalPages) this.currentPage++
        },
        prevPage() {
            if (this.currentPage > 1) this.currentPage--
        },
        setPage(page) {
            this.currentPage = page
        },
        nextChannelsPage() {
            const totalModalPages = Math.ceil(this.channels.length / this.itemsPerChannelsPage)
            if (this.currentChannelsPage < totalModalPages) this.currentChannelsPage++
        },
        prevChannelsPage() {
            if (this.currentChannelsPage > 1) this.currentChannelsPage--
        },
        setChannelsPage(page) {
            this.currentChannelsPage = page
        },
        ////// FIRST PLAY SETTINGS
        /////////////////////////// admin/update-first-play-settings
        async fetchFirstPlaySettings() {
            const notificationStore = useNotificationStore();
            try {
                const response = await axios.post(`/admin/fetch-first-play-settings`);
                if (response.data.success) {
                    // Operation was a success
                    this.firstPlaySettings = response.data.firstPlaySettings
                    notificationStore.setToastNotification(response.data.message, 'success', 1500);
                } else {
                    // Handle logical errors even when the HTTP response was OK
                    // Assuming 'status' and 'message' are part of the error response
                    const { message, status } = response.data;
                    let errorMessage = 'Failed to fetch First Play Settings due to a server error.';

                    if (status === 'error' && message && message.fallbackMessages) {
                        // Construct a more detailed error message based on validation feedback
                        const validationMessages = Object.values(message.fallbackMessages)
                            .map(msgs => msgs.join(' ')) // Join messages if there are multiple for one field
                            .join('; '); // Separate field messages with semicolons

                        errorMessage = validationMessages || errorMessage;
                    }

                    notificationStore.setToastNotification(errorMessage, 'error');
                }
            } catch (error) {
                console.error(error);
                notificationStore.setToastNotification('Failed to fetch First Play Settings due to a network or server error.', 'error');
            }
        },
        async updateFirstPlaySettings() {
            const notificationStore = useNotificationStore();

            try {
                const response = await axios.patch('/admin/update-first-play-settings', this.firstPlaySettings);

                if (response.data.success) {
                    // Operation was a success
                    this.firstPlaySettings = response.data.firstPlaySettings;
                    this.validationErrors = {}; // Clear any existing validation errors
                    notificationStore.setToastNotification(response.data.message, 'success', 1500);
                }
                else if (!response.data.success) {
                    // This block might not be necessary if your server correctly uses HTTP status codes for errors
                    this.validationErrors = response.data.errors || {};
                    notificationStore.setToastNotification(response.data.message, 'error');
                     // Exit early since we've handled the error case
                }
            } catch (error) {
                // Properly handle the error response
                if (error.response && error.response.data) {
                    // Server responded with a non-2xx status code
                    console.log("Error response data:", error.response.data);
                    this.validationErrors = error.response.data.errors || {};
                    const errorMessage = error.response.data.message || 'Failed to save First Play Settings due to a network or server error.';
                    notificationStore.setToastNotification(errorMessage, 'error');
                } else {
                    // The request was made but no response was received or other errors occurred
                    notificationStore.setToastNotification('Failed to save First Play Settings due to a network or server error.', 'error');
                }
            }
        },
        handleErrors(data) {
            const notificationStore = useNotificationStore();
            let errorMessage = 'Failed to save First Play Settings due to a server error.';

            if (data.status === 'error') {
                if (data.errors) {
                    // Directly use the validation errors from the server response
                    this.validationErrors = data.errors;
                    errorMessage = "Please check your input.";
                } else if (data.message) {
                    // Use the message provided by the server as the error message
                    errorMessage = data.message;
                }

                notificationStore.setToastNotification(errorMessage, 'error');
            }
        },
        async fetchActiveStreams() {
            const notificationStore = useNotificationStore();
            this.fetchingActiveStreams = true
            try {
                const response = await axios.post(`/admin/fetch-active-streams`);
                if (response.data.success) {
                    // Operation was a success
                    this.activeStreams = response.data.activeStreams
                    notificationStore.setToastNotification(response.data.message, 'success', 1500);
                    this.fetchingActiveStreams = false
                } else {
                    // Handle logical errors even when the HTTP response was OK
                    // Assuming 'status' and 'message' are part of the error response
                    const { message, status } = response.data;
                    let errorMessage = 'Failed to fetch active streams due to a server error.';

                    if (status === 'error' && message && message.fallbackMessages) {
                        // Construct a more detailed error message based on validation feedback
                        const validationMessages = Object.values(message.fallbackMessages)
                            .map(msgs => msgs.join(' ')) // Join messages if there are multiple for one field
                            .join('; '); // Separate field messages with semicolons

                        errorMessage = validationMessages || errorMessage;
                    }

                    notificationStore.setToastNotification(errorMessage, 'error');
                    this.fetchingActiveStreams = false
                }
            } catch (error) {
                console.error(error);
                notificationStore.setToastNotification('Failed to fetch Active Streams due to a network or server error.', 'error');
                this.fetchingActiveStreams = false
            }
        },
        async setActiveStreamAsFirstPlay(activeStream) {
            const videoPlayerStore = useVideoPlayerStore();
            if (activeStream === 'test') {
                this.firstPlaySettings.customVideoSource = videoPlayerStore.mistServerUri + 'hls/test/index.m3u8'
                this.firstPlaySettings.customVideoSourceType = 'application/x-mpegURL'
                this.firstPlaySettings.customVideoName = 'Test Stream'
                this.firstPlaySettings.customMediaType = 'firstPlay'
            } else {
                // axios.post save firstPlaySettings and broadcast an event.
                // this.
                // showName, streamName, streamMimeType
                this.firstPlaySettings.customVideoSource = videoPlayerStore.mistServerUri + 'hls/' + activeStream.streamName + '/index.m3u8'
                this.firstPlaySettings.customVideoSourceType = activeStream.streamMimeType
                this.firstPlaySettings.customVideoName = activeStream.showName
                this.firstPlaySettings.customMediaType = 'firstPlay'
            }
            await this.updateFirstPlaySettings()
            // broadcast
            // source
            // mediaType
            // type
            // name

        }
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
        totalModalPages(state) {
            return Math.ceil(state.items.length / state.itemsPerPage)
        },
        totalChannelsPages(state) {
            return Math.ceil(state.channels.length / state.itemsPerChannelsPage)
        },
        // Getter to count active channels
        activeChannelsCount: (state) => {
            // Use Array.prototype.filter to keep only items with 'active' true,
            // then use the length property to get the count
            return state.channels.filter(channel => channel.active).length;
        },
    },
})
