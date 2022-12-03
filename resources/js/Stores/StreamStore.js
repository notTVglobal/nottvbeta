import { defineStore } from "pinia";

export let useStreamStore = defineStore('streamStore', {
    state: () => ({
        isLive: Boolean,
        currentChannel: [],
        name: '',
        description: '',
        posterName: [],
        posterId: [0],
        posterUrl: '',
        episodes: [],
        team_id: 'team id',
        teamName: '',
    }),

    actions: {
        //
    },

    getters: {
        //
    }
});
