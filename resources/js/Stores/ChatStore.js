import { defineStore } from "pinia";

export let useChatStore = defineStore('chat', {
    state() {
        return {
            showChat: Boolean,
            class: ''
        };
    },

    actions: {
        toggleShowChatOn() {
            this.showChat = true
        },
        toggleShowChatOff() {
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
