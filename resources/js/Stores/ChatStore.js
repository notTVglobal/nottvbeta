import { defineStore } from "pinia";

export let useChatStore = defineStore('chat', {
    state() {
        return {
            show: Boolean
        };
    },

    actions: {
        show() {
            show = true
        },
        hide() {
            show = false
        }
    }
})
