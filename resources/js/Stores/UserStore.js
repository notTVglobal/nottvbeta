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
        id: null,
        roleId: null,
        getUserDataCompleted: null,
        isAdmin: null,
        isCreator: null,
        isNewsPerson: null,
        isVip: null,
        isSubscriber: null,
        oldLoggedOutId: null,
        uploadPercentage: 0,
        scrollToTopCounter: 0,
        uploadMovieId: null,
        uploadShowEpisodeId: null,
        upgradeSelection: '',
        testNum: 0,
        url: null,
        key: 0,

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
            this.isMobile = screenWidth <= 926;
        },
        // checkIsSubscriber() {
        //     this.isSubscriber = !!(this.roleId === 2 || this.roleId === 3 || this.isAdmin);
        // },
        // checkIsVip() {
        //     this.userisVip = !!(this.roleId === 3 || this.isAdmin);
        // },
        // checkIsCreator() {
        //     this.isCreator = !!(this.roleId === 4 || this.isAdmin);
        // },
        // checkIsAdmin() {
        //     this.isAdmin =  !!(this.isAdmin);
        // },
    },

    getters: {
        uploadPercentageRounded(state) {
            if (this.uploadPercentage !== 0) {
                return Math.round(state.uploadPercentage * 10) / 10
            }
        },
    }
});
