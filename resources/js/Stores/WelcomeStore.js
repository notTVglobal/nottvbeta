import { defineStore } from "pinia";
import {ref} from "vue";

export let useWelcomeStore = defineStore('welcomeStore', {
    state() {
        return {
            showLogin: Boolean,
            showRegister: Boolean,
            showOverlay: true,
        };
    },

    actions: {

    }
})
