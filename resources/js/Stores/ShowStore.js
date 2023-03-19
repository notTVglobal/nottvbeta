import { defineStore } from "pinia";

export let useShowStore = defineStore('showStore', {
    state: () => ({
        id: 0,
        name: '',
        category_id: 0,
        category_sub_id: 0,
        category_description: '',
        sub_categories: [],
        description: '',
        posterName: [],
        posterId: [0],
        episodes: [],
        team_id: 'team id',
        episodePoster: '',
        noteEdit: 0,
        note: '',
        saveNoteProcessing: Boolean,

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
