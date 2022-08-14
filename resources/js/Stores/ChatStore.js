import { defineStore } from "pinia";

export let useChatStore = defineStore('chat', {
    state() {
        return {
            show: Boolean,
            class: ''
        };
    },

    actions: {
        show() {
            show = true
        },
        hide() {
            show = false
        },
        makeBig() {
            this.class = 'chatBig';
        },
        makeSmall() {
            this.class = 'chatSmall';
        }
    }


})
