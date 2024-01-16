import { defineStore } from "pinia";

const initialState = () => ({
    id: 0,
    category_id: 0,
    category_sub_id: 0,
    category_description: '',
    sub_categories: [],
})

export const useMovieStore = defineStore('movieStore', {
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
