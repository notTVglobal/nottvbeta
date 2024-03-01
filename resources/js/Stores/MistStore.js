import { defineStore } from 'pinia';

const initialState = () => ({
    apiRequest: [],
    challenge: [],
    status: [],
    apiResponse: [],
    apiActiveStreams: [],
    mistUsername: [],
    mistPassword: [],
    mistStatus: Boolean,
    mistDisplayPushForm: Boolean,
    mistDisplay: String,
    mistNewHashedPassword: [],
    mistStreamPushDestinations: [],
});

export const useMistStore = defineStore('mistStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState());
        },
        // add additional actions here
        async getMistStreamPushDestinations(wildcardId) {
            // Assuming `goLiveStore.selectedShow.mist_stream_wildcard.id` holds the wildcard ID
            // const wildcardId = goLiveStore?.selectedShow?.mist_stream_wildcard?.id
            if (wildcardId) {
                try {
                    // Append the wildcard ID as a query parameter
                    const response = await axios.get(`/mist-stream-push-destinations?wildcardId=${wildcardId}`)
                    this.mistStreamPushDestinations.value = response.data // Update the reactive variable
                } catch (error) {
                    console.error('Failed to fetch push destinations:', error)
                }
            } else {
                console.error('No wildcard ID found')
            }
        },
        async getMistStreamPushAutoList(wildcardId) {
            // Assuming `goLiveStore.selectedShow.mist_stream_wildcard.id` holds the wildcard ID
            // const wildcardId = goLiveStore?.selectedShow?.mist_stream_wildcard?.id
            if (wildcardId) {
                try {
                    // Append the wildcard ID as a query parameter
                    const response = await axios.post(`/mist-stream/get-push-auto-list?wildcardId=${wildcardId}`)
                    this.mistStreamPushDestinations = response.data // Update the reactive variable
                } catch (error) {
                    console.error('Failed to fetch push auto list:', error)
                }
            } else {
                console.error('No wildcard ID found')
            }
        },
        async getMistStreamPushList(wildcardId) {
            // Assuming `goLiveStore.selectedShow.mist_stream_wildcard.id` holds the wildcard ID
            // const wildcardId = goLiveStore?.selectedShow?.mist_stream_wildcard?.id
            if (wildcardId) {
                try {
                    // Append the wildcard ID as a query parameter
                    const response = await axios.post(`/mist-stream/get-push-list?wildcardId=${wildcardId}`)
                    this.mistStreamPushDestinations = response.data // Update the reactive variable
                } catch (error) {
                    console.error('Failed to fetch push list:', error)
                }
            } else {
                console.error('No wildcard ID found')
            }
        },
        async startPush(destinationId) {
            console.log(`Starting push for destination ${destinationId}`)
            const data = {destinationId}

            try {
                const response = await axios.post('/mist-stream/start-push', data, {
                    headers: {
                        'Content-Type': 'application/json',
                    },
                })

                console.log('Push started successfully:', response.data)
                // Update the component's state to reflect the change
                const index = this.mistStreamPushDestinations.findIndex(destination => destination.id === destinationId)
                if (index !== -1) {
                    this.mistStreamPushDestinations[index].push_is_started = 1
                }
            } catch (error) {
                console.error('Error starting push:', error)
                // Handle the error appropriately in your UI
            }
        },
        async stopPush(destinationId) {
            console.log(`Stopping push for destination ${destinationId}`)
            const data = {destinationId}

            try {
                const response = await axios.post('/mist-stream/stop-push', data, {
                    headers: {
                        'Content-Type': 'application/json',
                    },
                })

                console.log('Push stopped successfully:', response.data)
                // Update the component's state to reflect the change
                const index = this.mistStreamPushDestinations.findIndex(destination => destination.id === destinationId)
                if (index !== -1) {
                    this.mistStreamPushDestinations[index].push_is_started = 0
                }
            } catch (error) {
                console.error('Error stopping push:', error)
                // Handle the error appropriately in your UI
            }
        },
        async enableAutoPush(destinationId) {
            const data = {
                destinationId,
            }

            try {
                const response = await axios.post('/mist-stream/push-auto-add', data, {
                    headers: {
                        'Content-Type': 'application/json',
                    },
                })
                console.log('Auto push enabled successfully:', response.data)
                const index = this.mistStreamPushDestinations.findIndex(destination => destination.id === destinationId)
                if (index !== -1) {
                    this.mistStreamPushDestinations[index].has_auto_push = 1
                }
            } catch (error) {
                console.error('Error enabling auto push:', error)
            }
        },
        async disableAutoPush(destinationId) {
            console.log(`Disabling auto push for destination ${destinationId}`)
            const data = {
                destinationId,
            }

            try {
                const response = await axios.post('/mist-stream/push-auto-remove', data, {
                    headers: {
                        'Content-Type': 'application/json',
                    },
                })

                console.log('Auto push disabled successfully:', response.data)
                const index = this.mistStreamPushDestinations.findIndex(destination => destination.id === destinationId)
                if (index !== -1) {
                    this.mistStreamPushDestinations[index].has_auto_push = 0
                }
            } catch (error) {
                console.error('Error disabling auto push:', error)
            }
        },
        async deleteDestination (destinationId) {
            // Confirm deletion with the user before proceeding
            if (confirm(`Are you sure you want to delete the destination with ID: ${destinationId}?`)) {
                try {
                    // Perform the delete operation
                    await axios.delete(`/mist-stream-push-destinations/${destinationId}`)
                    console.log(`Deleted destination with ID: ${destinationId}`)
                    // Optionally, remove the item from your local state to update the UI
                    this.mistStreamPushDestinations = this.mistStreamPushDestinations.filter(destination => destination.id !== destinationId)
                } catch (error) {
                    console.error(`Error deleting destination with ID: ${destinationId}`, error)
                }
            }
        }
    }
});
