import { defineStore } from "pinia";

export let useWelcomeStore = defineStore('welcomeStore', {
    state() {
        return {
            showLogin: Boolean,
            showRegister: Boolean,
            showOverlay: Boolean,
        };
    },

    actions: {

    }
})
