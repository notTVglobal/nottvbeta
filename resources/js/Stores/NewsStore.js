import { defineStore } from "pinia";



export const useNewsStore = defineStore('newsStore', {
    state() {
        return {
            newsArticleIdTiptop: '',
            newsArticleTitleTiptop: '',
            newsArticleContentTiptop: [],
        };
    },

    actions: {
        //
    },

})
