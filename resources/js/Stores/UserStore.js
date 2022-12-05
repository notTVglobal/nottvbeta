import { defineStore } from "pinia";

export let useUserStore = defineStore('userStore', {
    state: () => ({
        showNavDropdown: Boolean,

    }),

    actions: {
        toggleNavDropdown() {
            this.showNavDropdown = ! this.showNavDropdown;

        }
    },

    getters: {
        //
    }
});
