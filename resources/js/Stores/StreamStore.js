import { defineStore } from "pinia";

export let useStreamStore = defineStore('streamStore', {
    state: () => ({
        isLive: [],
        currentChannel: [],
        name: '',
        description: '',
        posterName: [],
        posterId: [0],
        episodes: [],
        team_id: 'team id',
    }),

    actions: {
        //
    },

    getters: {
        //
    }
});
