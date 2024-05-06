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
    isLoadingDestinations: false,
    loadingDestinationId: null,
    processingRecordingChange: false,
    isProcessingDisableAllAutoPushes: false,
    playerIsReloading: false,
    pushDestinationFormSubmitProcessing: false,
    mistServerUri: null,
    previousStreamStatus: null,
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
                // console.error('Failed to fetch shows:', error)
                // Handle error appropriately
            }
        },


        // temporarily commenting this out
        async generateStreamKey() {
            if (!this.selectedShowId) {
                // console.error('No show selected')
                throw new Error('No show selected') // Throw an error to be caught by the caller
            }

            try {
                const response = await axios.post(`/go-live/shows/${this.selectedShowId}/stream-key`)
                // console.log('Stream key generated:', response.data)
                // this.streamKey = response.data.stream_key // Assuming the response includes the stream key
                // console.log('this new stream key: ' + this.streamKey)
                // console.log('response data stream key: ' + response.data.stream_key)
                // Update goLiveStore.selectedShow.mist_stream_wildcard_id with the returned stream key
                if (this.selectedShowId && response.data.stream_key) {
                    this.selectedShow.mist_stream_wildcard = response.data
                    this.selectedShow.mist_stream_wildcard_id = response.data.stream_key
                }
                return response.data // Return the response data
            } catch (error) {
                // console.error('Error generating stream key:', error.response ? error.response.data : error)
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
        async startRecording() {
            // console.log('1 start recording in Go Live Store')
            const notificationStore = useNotificationStore()
            const showSlug = this.selectedShow.slug
            // console.log(`Starting recording for show ${showSlug}`)
            this.processingRecordingChange = true
            try {
                // console.log('2a post to /mist-stream/start-recording/')
                const response = await axios.post('/mist-stream/start-recording/'+showSlug, {
                    stream_name: this.streamKey,
                })
                // console.log('5 and we\'re back in the GoLiveStore')
                // console.log('Recording started successfully:', response.data)
                // console.log('Stream Name:', this.streamKey)
                const {message, status} = response.data
                // Use the status from the response for the notification
                notificationStore.setToastNotification(message, status)
                // Update the recording status
                this.isRecording = true
            } catch (error) {
                // console.error('Error starting push:', error)
                notificationStore.setToastNotification('Failed to start recording.', 'error')
                // Handle the error appropriately in your UI
            } finally {
                this.processingRecordingChange = false // Stop processing spinner regardless of outcome
            }
        },
        async stopRecording() {
            const notificationStore = useNotificationStore()
            const showSlug = this.selectedShow.slug
            // console.log(`Stopping recording for show ${showSlug}`)
            this.processingRecordingChange = true
            // console.log(`Stopping recording for show ${showSlug}`)
            try {
                const response = await axios.post('/mist-stream/stop-recording/'+showSlug, {
                    stream_name: this.streamKey,
                })
                // console.log('Recording stopped successfully:', response.data)
                const {message, status} = response.data
                notificationStore.setToastNotification(message, status)
                // Update the recording status
                this.isRecording = false
            } catch (error) {
                // console.error('Error stopping recording:', error)
                notificationStore.setToastNotification('Failed to stop push.', 'error')
            } finally {
                this.processingRecordingChange = false // Stop processing spinner regardless of outcome
            }
        },
        // async fetchStreamInfo(streamName) {
        //     try {
        //         // const response = await fetch(`http://mist.nottv.io:8080/json_${encodedStreamName}.js`); // Replace with your URL
        //         const response = await fetch(`/fetch-stream-info/${streamName}`) // Replace with your URL
        //         if (!response.ok) throw new Error('Failed to fetch')
        //         this.streamInfo = await response.json() // Store the data in serverInfo
        //     } catch (error) {
        //         console.error('Error fetching server info:', error)
        //     }
        // },
        async fetchStreamInfo() {
            const notificationStore = useNotificationStore()
            // console.log('start fetch stream info...')
            try {
                if (!this.mistServerUri) {
                    await this.fetchMistServerUri()
                }
                // Then, use the URI in your next request
                const response = await axios.post('/fetch-stream-info', {
                    streamName: this.streamKey,
                    mistServerUri: this.mistServerUri, // Pass it as a string
                })
                // console.log('response returned...')
                // console.log(response.data);
                this.streamInfo = response.data.streamInfo || []
                // Assuming a successful response might look like {"message": "Stream is online", "status": "success"}
                let message, status

                // Check if the response contains an error field
                if (response.data.streamInfo.error) {
                    message = response.data.streamInfo.error
                    status = 'error' // Assuming 'error' as a fallback status
                    // console.log('error returned...')
                } else if (response.data.message && response.data.success) {
                    // If it's a success message with a status
                    message = response.data.message
                    status = 'info'
                    // console.log('success returned...')

                } else {
                    // Fallback for unexpected response structure
                    message = 'Received unexpected response from server.'
                    status = 'info' // Default to 'info' or another appropriate fallback status
                    // console.log('unexpected response returned...')
                }

                // Use the status from the response for the notification
                // notificationStore.setToastNotification(message, status)
            } catch (error) {
                // console.error(error);
                notificationStore.setToastNotification('Failed to fetch stream info.', 'error')
                // console.log('catch error...')
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
                // console.error('Error fetching RTMP URI:', error)
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
        async fetchMistServerUri() {
            const videoAuxPlayerStore = useVideoAuxPlayerStore();
            let uriResponse = await axios.get('/mist-server/uri')
            this.mistServerUri = uriResponse.data
            videoAuxPlayerStore.setMistServerUri(this.mistServerUri)
        },
        async reloadPlayer() {
            const videoAuxPlayerStore = useVideoAuxPlayerStore()
            const notificationStore = useNotificationStore()
            this.playerIsReloading = true // Start loading
            try {
                // First, fetch the URI

                // let uriResponse = await axios.get('/mist-server/uri');
                // this.mistServerUri = uriResponse.data; // Accessing the data prop// erty
                // // Create an object and set the 'name' property
                const mistStream = {
                    name: this.streamKey,
                }
                // // Now, use this URI in your next request
                // const response = await axios.post('/fetch-stream-info', {
                //     streamName: this.streamKey,
                //     mistServerUri: this.mistServerUri,
                // });
                // Now, you can pass the 'mistStream' object to the method
                await videoAuxPlayerStore.loadMistStreamVideo(mistStream, true).then(
                    // await this.fetchStreamInfo(),
                )
            } catch (error) {
                // console.error(error);
                // notificationStore.setGeneralServiceNotification('Failed to reload player', error)
                notificationStore.setToastNotification('Failed to reload player.', 'error')
            } finally {
                this.playerIsReloading = false // Stop loading regardless of outcome
            }

        },
        async fetchPushDestinations() {
            const notificationStore = useNotificationStore()
            this.isLoadingDestinations = true // Start loading
            try {
                const response = await axios.post('/go-live/fetch-push-destinations', {
                    showId: this.selectedShowId,
                    streamName: this.streamKey,
                    backgroundFetch: false,
                })
                // console.log(response.data);
                this.destinations = response.data.destinations || []
                this.isRecording = response.data.recording || false
                console.log('response about recording: ' + response.data.recording)
                // Extract message and status from the response
                const {message, status} = response.data
                // Use the status from the response fo
                // r the notification
                // notificationStore.setToastNotification(message, status)
            } catch (error) {
                // console.error(error);
                // notificationStore.setGeneralServiceNotification('Failed to reload player', error)
                notificationStore.setToastNotification('Failed to fetch push destinations.', 'error')
            } finally {
                this.isLoadingDestinations = false // Stop loading regardless of outcome
            }
        },
        async backgroundFetchPushDestinations() {
            // Background Fetch doesn't use Toast Notifications,
            // Keep the fetch silent in the background.

            this.isLoadingDestinations = true // Start loading
            try {
                const response = await axios.post('/go-live/fetch-push-destinations/', {
                    showId: this.selectedShowId,
                    streamName: this.streamKey,
                    backgroundFetch: true,
                })
                this.destinations = response.data.destinations || []
                this.isRecording = response.data.recording || false
            } catch (error) {
                // console.error(error);
                // notificationStore.setGeneralServiceNotification('Error Fetching Push Destinations', error)
                notificationStore.setToastNotification('Failed to fetch push destinations.', 'error')
            } finally {
                this.isLoadingDestinations = false // Stop loading regardless of outcome
            }
        },
        async deleteDestination(destinationId) {
            // Confirm deletion with the user before proceeding
            if (confirm(`Are you sure you want to delete the destination with ID: ${destinationId}?`)) {
                try {
                    // Perform the delete operation
                    await axios.delete('/mist-stream-push-destinations/' + destinationId)
                    // Optionally, remove the item from your local state to update the UI
                    this.destinations = this.destinations.filter(destination => destination.id !== destinationId)
                } catch (error) {
                    notificationStore.setGeneralServiceNotification('Error deleting destination', error)
                    // console.error(`Error deleting destination with ID: ${destinationId}`, error)
                }
            }
        },
        async startPush(destinationId, fullPushUri) {
            const notificationStore = useNotificationStore()
            this.loadingDestinationId = destinationId
            // console.log(`Starting push for destination ${destinationId}`)
            try {
                const response = await axios.post('/mist-stream/start-push', {
                    destination_id: destinationId,
                    full_push_uri: fullPushUri,
                    stream_name: this.streamKey,
                })
                // console.log('Push started successfully:', response.data)
                // console.log('Destination ID:', destinationId)
                // console.log('Full Push URI:', fullPushUri)
                // console.log('Stream Name:', this.streamKey)
                const {message, status} = response.data
                // Use the status from the response for the notification
                notificationStore.setToastNotification(message, status)
                // Update the component's state to reflect the change
                const index = this.destinations.findIndex(destination => destination.id === destinationId)
                if (index !== -1) {
                    this.destinations[index].push_is_started = 1
                }
            } catch (error) {
                // console.error('Error starting push:', error)
                notificationStore.setToastNotification('Failed to start push.', 'error')
                // Handle the error appropriately in your UI
            } finally {
                this.loadingDestinationId = null // Stop loading regardless of outcome
            }
        },
        async stopPush(destinationId) {
            const notificationStore = useNotificationStore()
            this.loadingDestinationId = destinationId
            // console.log(`Stopping push for destination ${destinationId}`)
            try {
                const response = await axios.post('/mist-stream/stop-push', {destination_id: destinationId})
                // console.log('Push stopped successfully:', response.data)
                const {message, status} = response.data
                notificationStore.setToastNotification(message, status)
                // Update the component's state to reflect the change
            } catch (error) {
                // console.error('Error stopping push:', error)
                notificationStore.setToastNotification('Failed to stop push.', 'error')
            } finally {
                this.loadingDestinationId = null // Stop loading regardless of outcome
                const index = this.destinations.findIndex(destination => destination.id === destinationId)
                if (index !== -1) {
                    this.destinations[index].push_is_started = 0
                }
            }
        },
        async enableAutoPush(destinationId) {
            this.loadingDestinationId = destinationId
            const notificationStore = useNotificationStore()
            try {
                const response = await axios.post('/mist-stream/push-auto-add/' + destinationId)
                // console.log('Auto push enabled successfully:', response.data)
                const {message, status} = response.data
                notificationStore.setToastNotification(message, status)
                const index = this.destinations.findIndex(destination => destination.id === destinationId)
                if (index !== -1) {
                    this.destinations[index].has_auto_push = 1
                }
            } catch (error) {
                // console.error('Error enabling auto push:', error)
                notificationStore.setToastNotification('Failed to enable auto push.', 'error')
            } finally {
                this.loadingDestinationId = null // Stop loading regardless of outcome
            }
        },
        async disableAutoPush(destinationId) {
            const notificationStore = useNotificationStore()
            this.loadingDestinationId = destinationId
            try {
                const response = await axios.post('/mist-stream/push-auto-remove/' + destinationId)
                // console.log('Auto push removed successfully:', response.data)
                const {message, status} = response.data
                notificationStore.setToastNotification(message, status)
                const index = this.destinations.findIndex(destination => destination.id === destinationId)
                if (index !== -1) {
                    this.destinations[index].has_auto_push = 0
                }
            } catch (error) {
                // console.error('Error disabling auto push:', error)
                notificationStore.setToastNotification('Failed to disable auto push.', 'error')
            } finally {
                this.loadingDestinationId = null // Stop loading regardless of outcome
            }
        },
        async disableAllAutoPushes() {
            const notificationStore = useNotificationStore()
            this.isProcessingDisableAllAutoPushes = true
            try {
                const response = await axios.post('/mist-stream/remove-all-auto-pushes-for-stream', {'streamName': this.streamKey})
                // console.log('Auto push disabled successfully:', response.data)
                const {message, status} = response.data
                notificationStore.setToastNotification(message, status)
                await this.fetchPushDestinations()
            } catch (error) {
                // console.error('Error disabling auto push:', error)
                notificationStore.setToastNotification('Failed to disable auto push.', 'error')
            } finally {
                this.isProcessingDisableAllAutoPushes = false // Stop loading regardless of outcome
                await this.fetchPushDestinations()
            }
        },
    },
    getters: {
        selectedShow: (state) => {
            const show = state.shows.find(show => show.id === state.selectedShowId) || null
            state.streamKey = show?.mist_stream_wildcard?.name || 'Fallback value if undefined'
            state.wildcardId = show?.mist_stream_wildcard?.id || 'Fallback value if undefined'
            state.isRecording = show?.mist_stream_wildcard?.is_recording || false
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
            return state.destinations.filter(destination => destination.push_is_started)
        },
        sourceName: (state) => {
            if (state.selectedShow?.mist_stream_wildcard?.name) {
                return state.selectedShow.mist_stream_wildcard.name
            } else if (state.episode?.mist_stream_wildcard?.name) {
                return state.episode.mist_stream_wildcard.name
            }
            return null // No source available
        },
        streamOffline: (state) => {
            // Check if the 'error' key exists and if its value is 'Stream is offline'
            // If streamInfo is null or undefined, it defaults to an empty object {}
            return (state.streamInfo?.error ?? '') === 'Stream is offline';
        },
    },


})
