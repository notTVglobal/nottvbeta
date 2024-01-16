import { defineStore } from "pinia";

const initialState = () => ({
    newsArticleIdTiptop: '',
    newsArticleTitleTiptop: '',
    newsArticleContentTiptop: [],
})

export const useNewsStore = defineStore('newsStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState());
        },
        //
    },

})
