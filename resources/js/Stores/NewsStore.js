import { defineStore } from "pinia";



export let useNewsStore = defineStore('news', {
    state() {
        return {
            newsArticleIdTiptop: '',
            newsArticleTitleTiptop: '',
            newsArticleContentTiptop: '',
        };
    },

    actions: {
        //
    },

})
