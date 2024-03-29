import { defineStore } from 'pinia'
import { useNotificationStore } from '@/Stores/NotificationStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useVideoAuxPlayerStore } from '@/Stores/VideoAuxPlayerStore'
import videojs from 'video.js'

const initialState = () => ({
    displayEpisodeGoLiveComponent: false,
    isEpisode: null,
    episode: null,
    preSelectedShowId: null,
    selectedShowId: null,
    // selectedShow: null, this uses the getter... set it up in the component as a computed property
    shows: [], // Assuming you might want to store shows here too
    streamKey: '', // Optional: Store the generated stream key if needed
    wildcardId: '',
    isLive: false,
    isRecording: false,
    streamInfo: null,
    rtmpUri: null,
    destinations: [], // New state for holding destinations
})

export const useGoLiveStore = defineStore('goLiveStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
        },
        async fetchShows() {
            try {
                const response = await axios.get('/go-live/shows') // Adjust the URL as needed
                this.shows = response.data
            } catch (error) {
                console.error('Failed to fetch shows:', error)
                // Handle error appropriately
            }
        },


        // temporarily commenting this out
        async generateStreamKey() {
            if (!this.selectedShowId) {
                console.error('No show selected')
                throw new Error('No show selected') // Throw an error to be caught by the caller
            }

            try {
                const response = await axios.post(`/go-live/shows/${this.selectedShowId}/stream-key`)
                console.log('Stream key generated:', response.data)
                this.streamKey = response.data.stream_key // Assuming the response includes the stream key
                console.log('this new stream key: ' + this.streamKey)
                console.log('response data stream key: ' + response.data.stream_key)
                // Update goLiveStore.selectedShow.mist_stream_wildcard_id with the returned stream key
                if (this.selectedShowId && response.data.stream_key) {
                    this.selectedShow.mist_stream_wildcard = response.data
                    this.selectedShow.mist_stream_wildcard_id = response.data.stream_key
                }
                return response.data // Return the response data
            } catch (error) {
                console.error('Error generating stream key:', error.response ? error.response.data : error)
                throw error // Re-throw the error to be caught by the component
            }
        },

        // Other actions...
        updateAndGetStreamKey() {
            const show = this.shows.find(show => show.id === this.selectedShowId) || null
            const streamKey = show?.mist_stream_wildcard?.name || ' < RELOAD THIS PAGE TO GET YOUR STREAM KEY > '

            // Perform the state update
            this.streamKey = streamKey

            // Return the stream key for immediate use
            return streamKey
        },



        // async generateStreamKey() {
        //     if (!this.selectedShowId) {
        //         console.error("No show selected");
        //         throw new Error("No show selected");
        //     }
        //
        //     try {
        //         const response = await axios.post(`/go-live/shows/${this.selectedShowId}/stream-key`);
        //         console.log("Stream key generated:", response.data);
        //
        //         // Find the index of the selected show in the shows array
        //         const selectedIndex = this.shows.findIndex(show => show.id === this.selectedShowId);
        //         if (selectedIndex !== -1) {
        //             // Update the selected show with the new stream key information
        //             this.shows[selectedIndex].mist_stream_wildcard = response.data;
        //             this.shows[selectedIndex].mist_stream_wildcard_id = response.data.stream_key;
        //         }
        //         return response.data;
        //     } catch (error) {
        //         console.error("Error generating stream key:", error.response ? error.response.data : error);
        //         throw error;
        //     }
        // },


        // setPreSelectedShowId(id) {
        //     this.preSelectedShowId = id;
        //     console.log('preSelectedShowId updated to:', this.preSelectedShowId);
        // },
        // Action to update selectedShowId and selectedShow based on selectedShowId
        setSelectedShowId(showId) {
            this.selectedShowId = showId
            this.updateSelectedShow() // Call the method to update selectedShow based on new selectedShowId
        },
        // Method to find and update selectedShow based on selectedShowId
        updateSelectedShow() {
            this.selectedShow = this.shows.find(show => show.id === this.selectedShowId) || null
        },
        goLive() {
            this.isLive = true
        },
        stopLive() {
            this.isLive = false
        },
        startRecording() {
            this.isRecording = true
        },
        stopRecording() {
            this.isRecording = false
        },
        async fetchStreamInfo(streamName) {
            try {
                // const response = await fetch(`http://mist.nottv.io:8080/json_${encodedStreamName}.js`); // Replace with your URL
                const response = await fetch(`/fetch-stream-info/${streamName}`) // Replace with your URL
                if (!response.ok) throw new Error('Failed to fetch')
                this.streamInfo = await response.json() // Store the data in serverInfo
            } catch (error) {
                console.error('Error fetching server info:', error)
            }
        },
        async fetchRtmpUri() {
            try {
                const response = await fetch(`/fetch-rtmp-uri`)
                if (!response.ok) throw new Error('Failed to fetch')

                // Parse the plain text body of the response
                // Store the RTMP URI string in a Vue data property or similar
                this.rtmpUri = await response.text()
            } catch (error) {
                console.error('Error fetching RTMP URI:', error)
            }
        },
        clearStreamInfo() {
            this.streamInfo = null
        },
        toggleDisplayEpisodeGoLiveComponent(episode) {
            // const videoPlayerAuxStore = useVideoAuxPlayerStore()
            // videoPlayerAuxStore.reset()
            this.displayEpisodeGoLiveComponent = !this.displayEpisodeGoLiveComponent
            this.isEpisode = !!this.displayEpisodeGoLiveComponent
            this.episode = episode
            this.selectedShowId = this?.episode?.show_id

        },
        updateEpisode(episode) {
            this.episode = episode
        },
        // async reloadPlayer() {
        //     const videoPlayerStore = useVideoPlayerStore; // Accessing another store
        //
        //     let source = null;
        //     if (this.selectedShow?.mist_stream_wildcard?.name) {
        //         source = this.selectedShow?.mist_stream_wildcard?.name;
        //         await this.fetchStreamInfo(this.selectedShow?.mist_stream_wildcard?.name);
        //     } else if (this.episode?.mist_stream_wildcard?.name) {
        //         source = this.episode?.mist_stream_wildcard?.name;
        //         await this.fetchStreamInfo(this.episode?.mist_stream_wildcard?.name);
        //     }
        //
        //     let sourceUrl = videoPlayerStore.mistServerUri + 'hls/' + source + '/index.m3u8';
        //     console.log('source url: ' + sourceUrl);
        //     let sourceType = 'application/vnd.apple.mpegurl';
        //
        //     let videoJs = videojs('aux-player');
        //     videoJs.src({'src': sourceUrl, 'type': sourceType});
        //     // You might have other logic here as needed
        //
        //     console.log('reload player');
        // },

        // async reloadPlayer () {
        //     const videoPlayerStore = useVideoPlayerStore
        //     const videoAuxPlayerStore = useVideoAuxPlayerStore
        //     let source = null
        //     if (this?.selectedShow?.mist_stream_wildcard?.name) {
        //         source = this?.selectedShow?.mist_stream_wildcard?.name
        //         await this.fetchStreamInfo(this?.selectedShow?.mist_stream_wildcard?.name)
        //     } else if (this?.episode?.mist_stream_wildcard?.name) {
        //         source = this?.episode?.mist_stream_wildcard?.name
        //         await this.fetchStreamInfo(this?.episode?.mist_stream_wildcard?.name)
        //     }
        //     let sourceUrl = videoPlayerStore.mistServerUri + 'hls/' + source + '/index.m3u8'
        //     console.log('source url: ' + sourceUrl)
        //     let sourceType = 'application/vnd.apple.mpegurl'
        //     let videoAuxJs = videojs('aux-player')
        //     videoAuxJs.src({'src': sourceUrl, 'type': sourceType})
        //     // videoAuxPlayerStore.loadNewLiveSource(source, sourceType)
        //     console.log('reload player')
        // },


        async reloadPlayer() {
            const videoAuxPlayerStore = useVideoAuxPlayerStore();
            // Create an object and set the 'name' property
            const mistStream = {
                name: this.sourceName
            };
            await videoAuxPlayerStore.getMistServerUri()
            // Now, you can pass the 'mistStream' object to the method
            videoAuxPlayerStore.loadMistStreamVideo(mistStream, true);

            console.log('source: ' + mistStream.name)
        },

        async fetchPushDestinations() {
            const notificationStore = useNotificationStore();
            try {
                const response = await axios.post('/go-live/fetch-push-destinations/'+this.selectedShowId);
                console.log(response.data);
                this.destinations = response.data.destinations || [];
                this.isRecording = response.data.isRecording || false;
                // Extract message and status from the response
                const { message, status } = response.data;
                // Use the status from the response for the notification
                notificationStore.setToastNotification(message, status);
            } catch (error) {
                console.error(error);
                notificationStore.setToastNotification('Failed to fetch push destinations.', 'error');
            }
        },
        async deleteDestination (destinationId) {
            // Confirm deletion with the user before proceeding
            if (confirm(`Are you sure you want to delete the destination with ID: ${destinationId}?`)) {
                try {
                    // Perform the delete operation
                    await axios.delete('/mist-stream-push-destinations/'+destinationId)
                    // Optionally, remove the item from your local state to update the UI
                    this.destinations = this.destinations.filter(destination => destination.id !== destinationId)
                } catch (error) {
                    console.error(`Error deleting destination with ID: ${destinationId}`, error)
                }
            }
        },
        async startPush(destinationId) {
            const notificationStore = useNotificationStore();
            // console.log(`Starting push for destination ${destinationId}`)
            try {
                const response = await axios.post('/mist-stream/start-push/'+destinationId)
                console.log('Push started successfully:', response.data)
                const { message, status } = response.data;
                // Use the status from the response for the notification
                notificationStore.setToastNotification(message, status);
                // Update the component's state to reflect the change
                const index = this.destinations.findIndex(destination => destination.id === destinationId)
                if (index !== -1) {
                    this.destinations[index].push_is_started = 1
                }
            } catch (error) {
                console.error('Error starting push:', error)
                notificationStore.setToastNotification('Failed to start push.', 'error');
                // Handle the error appropriately in your UI
            }
        },
        async stopPush(destinationId) {
            const notificationStore = useNotificationStore();
            // console.log(`Stopping push for destination ${destinationId}`)
            try {
                const response = await axios.post('/mist-stream/stop-push/'+destinationId)
                console.log('Push stopped successfully:', response.data)
                const { message, status } = response.data;
                notificationStore.setToastNotification(message, status);
                // Update the component's state to reflect the change
                const index = this.destinations.findIndex(destination => destination.id === destinationId)
                if (index !== -1) {
                    this.destinations[index].push_is_started = 0
                }
            } catch (error) {
                console.error('Error stopping push:', error)
                notificationStore.setToastNotification('Failed to stop push.', 'error');
            }
        },
        async enableAutoPush(destinationId) {
            const notificationStore = useNotificationStore();
            try {
                const response = await axios.post('/mist-stream/push-auto-add/'+destinationId)
                console.log('Auto push enabled successfully:', response.data)
                const { message, status } = response.data;
                notificationStore.setToastNotification(message, status);
                const index = this.destinations.findIndex(destination => destination.id === destinationId)
                if (index !== -1) {
                    this.destinations[index].has_auto_push = 1
                }
            } catch (error) {
                console.error('Error enabling auto push:', error)
                notificationStore.setToastNotification('Failed to enable auto push.', 'error');
            }
        },
        async disableAutoPush(destinationId) {
            const notificationStore = useNotificationStore();
            try {
                const response = await axios.post('/mist-stream/push-auto-remove/'+destinationId)
                // console.log('Auto push enabled successfully:', response.data)
                const { message, status } = response.data;
                notificationStore.setToastNotification(message, status);
                const index = this.destinations.findIndex(destination => destination.id === destinationId)
                if (index !== -1) {
                    this.destinations[index].has_auto_push = 0
                }
            } catch (error) {
                console.error('Error enabling auto push:', error)
                notificationStore.setToastNotification('Failed to enable auto push.', 'error');
            }
        },
        async disableAllAutoPushes(streamKey) {
            const notificationStore = useNotificationStore();
            try {
                const response = await axios.post('/mist-stream/remove-all-auto-pushes-for-stream', {'streamKey':streamKey})
                console.log('Auto push disabled successfully:', response.data)
                const { message, status } = response.data;
                notificationStore.setToastNotification(message, status);
                await this.fetchPushDestinations()
            } catch (error) {
                console.error('Error disabling auto push:', error)
                notificationStore.setToastNotification('Failed to disable auto push.', 'error');
            }
        },
    },
    getters: {
        selectedShow: (state) => {
            const show = state.shows.find(show => show.id === state.selectedShowId) || null
            state.streamKey = show?.mist_stream_wildcard?.name || 'Fallback value if undefined'
            state.wildcardId = show?.mist_stream_wildcard?.id || 'Fallback value if undefined'
            return show
        },
        fullRtmpUri: (state) => {
            return state.rtmpUri ? state.rtmpUri + 'live/' : ''
        },
        fullUrl: (state) => {
            // Directly construct fullRtmpUri from state
            const fullRtmpUri = state.rtmpUri ? state.rtmpUri + 'live/' : ''

            // Directly find the show based on selectedShowId to avoid using getters
            // const show = state.shows.find(show => show.id === state.selectedShowId) || null;
            // const streamKey = show?.mist_stream_wildcard?.name || 'Fallback value if undefined';

            // Return the concatenated URL
            return `${fullRtmpUri}${state.streamKey}`
        },
        // Example getter that might filter destinations based on some criteria
        activeDestinations: (state) => {
            return state.destinations.filter(destination => destination.push_is_started);
        },
        sourceName: (state) => {
            if (state.selectedShow?.mist_stream_wildcard?.name) {
                return state.selectedShow.mist_stream_wildcard.name;
            } else if (state.episode?.mist_stream_wildcard?.name) {
                return state.episode.mist_stream_wildcard.name;
            }
            return null; // No source available
        },
    },


})
