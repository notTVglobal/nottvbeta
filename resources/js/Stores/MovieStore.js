import { defineStore } from "pinia";

export const useMovieStore = defineStore('movieStore', {
    state: () => ({
        id: 0,
        category_id: 0,
        category_sub_id: 0,
        category_description: '',
        sub_categories: [],
    }),

    actions: {
        //
    },

    getters: {
        //
    }
});
