import { defineStore } from "pinia";
import {default as Echo} from "laravel-echo";
import { ref } from "vue";
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore";



export let useChatStore = defineStore('chat', {
    state() {
        return {
            showChat: Boolean,
            class: '',
            messages: ref([]),
            message: [],
            echo: [],
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
        },
        getNewMessages() {
            this.echo = new Echo({
                broadcaster: 'pusher',
                key: process.env.MIX_PUSHER_APP_KEY,
                cluster: process.env.MIX_PUSHER_APP_CLUSTER,
                encrypted: true
            });
            let videoPlayer = useVideoPlayerStore();
            this.echo.private('chat.1')
                .listen('.message.new', e => {
                    console.log('PINIA NEW MESSAGE.');
                    console.log(e.chatMessage);
                    // this.messages.value = e.chatMessage;
                    axios.get('/chat/channel/' + videoPlayer.currentChannel.id + '/messages')
                        .then( response => {
                            this.messages = response.data;
                            // chatStore.messages = response.data;
                        })
                        .catch(error => {
                            console.log(error);
                        })
                });
        }
    },

})
