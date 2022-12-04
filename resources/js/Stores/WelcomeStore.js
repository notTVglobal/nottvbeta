import { defineStore } from "pinia";

export let useWelcomeStore = defineStore('welcomeStore', {
    state() {
        return {
            showLogin: Boolean,
            showOverlay: Boolean,
        };
    },

    actions: {

    }
})
