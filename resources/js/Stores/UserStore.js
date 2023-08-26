import { defineStore } from "pinia";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore";

export let useUserStore = defineStore('userStore', {
    state: () => ({
        isMobile: Boolean,

        // remove isFullPage and showNav
        // put them in the videoPlayerStore
        // instead this is for performance
        // improvements.
        //
        // isFullPage: false,
        // showNav: true,

        showNavDropdown: Boolean,

        // move currentPage from VideoPlayerStore to here.
        currentPage: String,

        id: '',
        oldLoggedOutId: null,
        uploadPercentage: 0,
        scrollToTopCounter: 0,
        uploadMovieId: null,
        uploadShowEpisodeId: null,
        upgradeSelection: '',
        testNum: 0,

    }),

    actions: {
        updateStore() {
            this.testNum++;
        },
        substractStore() {
            this.testNum--;
        },
        toggleNavDropdown() {
            this.showNavDropdown = ! this.showNavDropdown;

        },
        closeNavDropdown() {
            this.showNavDropdown = false;
            useVideoPlayerStore().closeOtt()
        },
        checkIsMobile() {
            let screenWidth = screen.width
            if (screenWidth <= 926) {
                this.isMobile = true
            } else
                this.isMobile = false
        },

    },

    getters: {
        //
    }
});
