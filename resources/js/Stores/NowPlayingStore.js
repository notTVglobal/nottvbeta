import { defineStore } from 'pinia'
import axios from 'axios'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'

const initialState = () => ({
    isLive: false   ,
    viewerCountIsVisible: false,
    activeMedia: {
        type: null, // mediaType: 'show', 'movie', 'video', 'externalVideo', 'channel'
        details: {}, // The detailed object of the playing media
    },
    // Channels might require additional state to manage their playlist
    activeChannel: null, // Details of the currently playing channel, if applicable
})

export const useNowPlayingStore = defineStore('nowPlayingStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
        },
        // Action to set the currently playing media
        setActiveMedia(mediaType, mediaDetails) {
            this.activeMedia.type = mediaType
            this.activeMedia.details = mediaDetails
        },
        // Special handling for channels
        setActiveChannel(channelDetails) {
            this.activeChannel = channelDetails
            // Potentially set the active media based on the channel's current play
        },
        // Utility or helper functions to process and play the media
        playMediaFromChannel(channelId, mediaId) {
            // Fetch channel details, determine the currently playing item
            // and update activeMedia accordingly
        },
        // Fetch and set active media for direct plays not from a channel
        fetchAndSetActiveMedia(mediaType, mediaId) {
            // API call to fetch media details by type and ID
            // Then set as active media
        },
        // Setting Active Media for On-demand Plays: This can be done through
        // a direct method call within your components, passing the necessary details from page.props
        playOnDemandMedia(mediaType, mediaDetails) {
            this.setActiveMedia(mediaType, mediaDetails)
        },
        // Utilize the existing API endpoint to retrieve channels list
        // and possibly details about the current play within a selected channel
        async fetchChannels() {
            try {
                const response = await axios.get('/api/channels_list')
                // Process and store channels list
            } catch (error) {
                console.error('Failed to fetch channels:', error)
                // In a production environment, you might want to elaborate on these,
                // such as providing user feedback or defaulting to specific content when fetching fails.
            }
        },
        async fetchFirstPlayData() {
            const videoPlayerStore = useVideoPlayerStore()
            try {
                const response = await axios.get('/first-play-data')
                videoPlayerStore.setMistServerUri(response.data.mist_server_uri)
                this.setActiveMedia('video', { // Assuming 'video' as a default type
                    source: response.data.first_play_video_source,
                    sourceType: response.data.first_play_video_source_type,
                    primaryName: response.data.first_play_video_name,
                    channelId: response.data.first_play_channel_id,
                })
            } catch (error) {
                console.error('Failed to fetch firstPlay data:', error)
                // Handle error or set default data
            }
        },
        async setFirstPlay() {
            // Initial attempt to set firstPlay using static imported data
            this.setActiveMedia('test', { // Assuming 'video' as a default type
                source: firstPlayData.first_play_video_source,
                sourceType: firstPlayData.first_play_video_source_type,
                name: firstPlayData.first_play_video_name,
                channelId: firstPlayData.first_play_channel_id,
            });

            try {
                // Attempt to fetch dynamic firstPlay data from an API
                const response = await axios.get('/api/first-play');
                const { mediaType, mediaDetails } = response.data;
                this.setActiveMedia(mediaType, mediaDetails);
            } catch (error) {
                console.error('Failed to fetch firstPlay media:', error);
                // Optionally, handle the error more gracefully
                // For example, you might keep the static data set initially,
                // or set another default here if the static data isn't suitable as a fallback
            }
        },
        async setLastPlay() {
            // Fetch lastPlay details, similar to setFirstPlay but might include user-specific data
            try {
                const lastPlayData = localStorage.getItem('lastPlayData')
                if (lastPlayData) {
                    const {mediaType, mediaDetails} = JSON.parse(lastPlayData)
                    this.setActiveMedia(mediaType, mediaDetails)
                } else {
                    // No lastPlay data found, fallback to firstPlay or default
                    await this.setFirstPlay()
                }
            } catch (error) {
                console.error('Error setting lastPlay:', error)
                // Handle error or set default content
                // In a production environment, you might want to elaborate on these,
                // such as providing user feedback or defaulting to specific content when fetching fails.
            }
        },
        updateLastPlay() {
            // Update lastPlay data, typically called when changing media or on app exit
            const lastPlayData = {
                mediaType: this.activeMedia.type,
                mediaDetails: this.activeMedia.details,
            }
            localStorage.setItem('lastPlayData', JSON.stringify(lastPlayData))
        },
    },
    getters: {
        // Truncate or format descriptions for display purposes.
        // Given the character limit for movie descriptions or show episode summaries
        formattedDescription: (state) => {
            // Assuming `description` is a direct attribute of `activeMedia.details`
            const desc = state.activeMedia.details.description || ''
            return desc.length > 300 ? `${desc.substring(0, 300)}...` : desc
        },
        // Check the type of the currently playing media in components:
        isActiveMediaOfType: (state) => (mediaType) => {
            return state.activeMedia.type === mediaType
        },
        // A simple list of creators' names for display
        creatorsList: (state) => {
            // Assuming creators are stored in `activeMedia.details.creators`
            return (state.activeMedia.details.creators || []).map(creator => creator.name).join(', ')
        },
        // Retrieve the appropriate thumbnail image for the currently playing media,
        // considering different structures for shows, movies, etc.
        activeMediaThumbnail: (state) => {
            // Example assuming a consistent image structure across media types
            return state.activeMedia.details.image?.cdn_endpoint + state.activeMedia.details.image?.folder + state.activeMedia.details.image?.name || 'default_thumbnail.jpg'
        },
        // Additional getters for new functionalities can be added here
    },
})
