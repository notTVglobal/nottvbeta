import { defineStore } from "pinia";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore";

export let useUserStore = defineStore('userStore', {
    state: () => ({
        isMobile: Boolean,
        showNavDropdown: Boolean,

    }),

    actions: {
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
        }
    },

    getters: {
        //
    }
});
