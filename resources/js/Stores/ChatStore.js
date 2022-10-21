import { defineStore } from "pinia";

export let useChatStore = defineStore('chat', {
    state() {
        return {
            showChat: Boolean,
            class: '',
            messages: [],
            message: ''
        };
    },

    actions: {
        toggleChatOn() {
            this.showChat = true
        },
        toggleChatOff() {
            this.showChat = false
        },
        makeBig() {
            this.class = 'chatBig';
        },
        makeSmall() {
            this.class = 'chatSmall';
        },
        chatHidden() {
            this.class = 'chatHidden';
            this.showChat = false;
        }
    }

})
