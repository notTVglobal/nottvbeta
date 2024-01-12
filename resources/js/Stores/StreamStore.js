import { defineStore } from "pinia";

export const useStreamStore = defineStore('streamStore', {
    state: () => ({
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
    }),

    actions: {
        //
    },

    getters: {
        //
    }
});
