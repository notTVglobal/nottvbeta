import { defineStore } from "pinia";

export let useChatStore = defineStore('chatBox', {
    state() {
        return {
            class: 'hidden'
        };
    },

    actions: {
        chatToggle() {
            if (this.class = 'hidden', this.class = ''); else (this.class = 'hidden');
        }}})
