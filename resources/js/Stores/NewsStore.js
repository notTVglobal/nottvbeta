import { defineStore } from "pinia";



export let useNewsStore = defineStore('newsStore', {
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
