import { defineStore } from 'pinia'

const initialState = () => ({
    displayEpisodeGoLiveComponent: false,
    isEpisode: null,
    episode: null,
    preSelectedShowId: null,
    selectedShowId: null,
    // selectedShow: null, this uses the getter... set it up in the component as a computed property
    shows: [], // Assuming you might want to store shows here too
    streamKey: '', // Optional: Store the generated stream key if needed
    isLive: false,
    isRecording: false,
    streamInfo: null,
    rtmpUri: null,
})

export const useGoLiveStore = defineStore('goLiveStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState());
        },
        async fetchShows() {
            try {
                const response = await axios.get('/go-live/shows'); // Adjust the URL as needed
                this.shows = response.data;
            } catch (error) {
                console.error('Failed to fetch shows:', error);
                // Handle error appropriately
            }
        },
        async generateStreamKey() {
            if (!this.selectedShowId) {
                console.error("No show selected");
                return; // Exit the function if no show is selected
            }

            try {
                const response = await axios.post(`/go-live/shows/${this.selectedShowId}/stream-key`);
                console.log("Stream key generated:", response.data);
                this.streamKey = response.data.streamKey; // Assuming the response includes the stream key
            } catch (error) {
                console.error("Error generating stream key:", error.response ? error.response.data : error);
            }
        },
        // setPreSelectedShowId(id) {
        //     this.preSelectedShowId = id;
        //     console.log('preSelectedShowId updated to:', this.preSelectedShowId);
        // },
        // Action to update selectedShowId and selectedShow based on selectedShowId
        setSelectedShowId(showId) {
            this.selectedShowId = showId;
            this.updateSelectedShow(); // Call the method to update selectedShow based on new selectedShowId
        },
        // Method to find and update selectedShow based on selectedShowId
        updateSelectedShow() {
            this.selectedShow = this.shows.find(show => show.id === this.selectedShowId) || null;
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
            const encodedStreamName = encodeURIComponent(streamName);
            try {
                // const response = await fetch(`http://mist.nottv.io:8080/json_${encodedStreamName}.js`); // Replace with your URL
                const response = await fetch(`/fetch-stream-info/${streamName}`); // Replace with your URL
                if (!response.ok) throw new Error('Failed to fetch');
                this.streamInfo = await response.json(); // Store the data in serverInfo
            } catch (error) {
                console.error('Error fetching server info:', error);
            }
        },
        async fetchRtmpUri() {
            try {
                const response = await fetch(`/fetch-rtmp-uri`);
                if (!response.ok) throw new Error('Failed to fetch');

                // Parse the plain text body of the response
                // Store the RTMP URI string in a Vue data property or similar
                this.rtmpUri = await response.text();
            } catch (error) {
                console.error('Error fetching RTMP URI:', error);
            }
        },
        clearStreamInfo() {
            this.streamInfo = null
        },
        toggleDisplayEpisodeGoLiveComponent(episode) {
            this.displayEpisodeGoLiveComponent = ! this.displayEpisodeGoLiveComponent;
            this.isEpisode = !!this.displayEpisodeGoLiveComponent;
            this.episode = episode
            this.selectedShowId = this.episode.show_id

        },
        updateEpisode(episode) {
            this.episode = episode;
        },
    },
    getters: {
        selectedShow: (state) => {
            return state.shows.find(show => show.id === state.selectedShowId) || null;
        },
    },

})
