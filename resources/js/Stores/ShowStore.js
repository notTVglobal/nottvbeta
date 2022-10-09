import { defineStore } from "pinia";

export let useShowStore = defineStore('showStore', {
    state: () => ({
        id: 0,
        name: '',
        description: '',
        posterName: '',
        posterId: 0,
        episodes: [],
        team_id: 'team id',
    }),

    actions: {
        async fill() {
            let r = await import('@/Json/show.json');
            this.$state = r.default;
        }
    },

    getters: {
        //
    }
});
