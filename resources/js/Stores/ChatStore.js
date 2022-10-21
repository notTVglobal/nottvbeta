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

            // window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            //
            // window.axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
            // window.axios.defaults.headers.common.crossDomain = true;
            // let token = document.head.querySelector('meta[name="csrf-token"]');
            //
            // if (token) {
            //     window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
            // } else {
            //     console.error('CSRF token not found.');
            // }

            // tec21: This code works on the development system, but not on the staging server.
            // this.echo.private('chat.1')
            //     .listen('.message.new', e => {
            //         console.log('PINIA NEW MESSAGE.');
            //         console.log(e.chatMessage);
            //         // this.messages.value = e.chatMessage;
            //         axios.get('/chat/channel/' + videoPlayer.currentChannel.id + '/messages')
            //             .then( response => {
            //                 this.messages = response.data;
            //                 // chatStore.messages = response.data;
            //             })
            //             .catch(error => {
            //                 console.log(error);
            //             })
            //     });


    },

    getters: {
        getNewMessages() {
            this.echo = new Echo({
                broadcaster: 'pusher',
                key: process.env.MIX_PUSHER_APP_KEY,
                cluster: process.env.MIX_PUSHER_APP_CLUSTER,
                encrypted: true,
                forceTLS: true
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
    }

})
