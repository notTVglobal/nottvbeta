import { defineStore } from "pinia";

export let useShowStore = defineStore('show', {
    state: () => ({
        id: 0,
        name: '',
        description: '',
        poster: '',
        episodes: []
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
