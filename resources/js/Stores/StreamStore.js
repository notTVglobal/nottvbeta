import { defineStore } from "pinia";

const initialState = () => ({
    isLive: Boolean,
    currentChannel: String,
    name: '',
    description: '',
    posterName: [],
    posterId: [0],
    posterUrl: '',
    episodes: [],
    team_id: 'team id',
    teamName: '',
    streamName: '',
    rtmpDestination: '',
})

export const useStreamStore = defineStore('streamStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState());
        },
        //
    },

    getters: {
        //
    }
});
