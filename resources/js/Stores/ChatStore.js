import { defineStore } from "pinia";
import { default as Echo } from "laravel-echo";
import { ref } from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useUserStore } from "@/Stores/UserStore";
import videojs from "video.js";

// const appSettingStore = useAppSettingStore();

const initialState = () => ({
    showChat: false,
    class: '',
    oldMessages: [],
    newMessages: [],
    message: '',
    input: '',
    inputTooLong: false,
    echo: [],
    currentChannel: [],
})

export const useChatStore = defineStore('chatStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
        },
        toggleChatOn() {
            this.showChat = true
        },
        toggleChatOff() {
            this.showChat = false
        },
        toggleChat() {
            this.showChat = !this.showChat;
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
        turnPipChatModeOn() {
            // turn chat mode on
            const videoPlayerStore = useVideoPlayerStore();
            const appSettingStore = useAppSettingStore();
            const userStore = useUserStore();

            if (userStore.isMobile) {
                let videoJs = videojs('main-player')
                videoJs.controls(false)
                // appSettingStore.ott = false
                videoPlayerStore.ottButtons = false

                if (!appSettingStore.fullPage) {
                    appSettingStore.hidePage = true;
                }
                appSettingStore.pipChatMode = true;

                appSettingStore.setPageBgColor(appSettingStore.pipBgColor);
                appSettingStore.setChatMessageBgColor(appSettingStore.pipChatMessageBgColor);

                videoPlayerStore.class = 'pipVideoClassTopRight'
                videoPlayerStore.videoContainerClass = 'pipVideoContainerTopRight'

                // if(appSettingStore.fullPage) {
                //     videoPlayerStore.class = 'pipVideoClassFullPage'
                //     videoPlayerStore.videoContainerClass = 'pipVideoContainerFullPage'
                // } else {
                //     videoPlayerStore.class = 'pipVideoClassTopRight'
                //     videoPlayerStore.videoContainerClass = 'pipVideoContainerTopRight'
                // }

            }
        },
        turnPipChatModeOff() {
            const appSettingStore = useAppSettingStore();
            const videoPlayerStore = useVideoPlayerStore();

            appSettingStore.pipChatMode = false;
            appSettingStore.hidePage = false;
            appSettingStore.setPageBgColor(appSettingStore.primaryBgColor);
            appSettingStore.setChatMessageBgColor(appSettingStore.primaryChatMessageBgColor);

            if (appSettingStore.fullPage) {
                videoPlayerStore.makeVideoFullPage();
            } else {
                videoPlayerStore.makeVideoTopRight();
            }
        },
        // turnPipChatModeOffToFullPage() {
        //     // turn chat mode off (FullPage)
        //     const videoPlayerStore = useVideoPlayerStore();
        //
        //     if (userStore.isMobile) {
        //         videoPlayerStore.makeVideoFullPage();
        //         this.turnPipChatModeOff();
        //     }
        // },
        // turnPipChatModeOffToTopRight() {
        //     // turn chat mode off (TopRight)
        //     const videoPlayerStore = useVideoPlayerStore();
        //
        //     if (userStore.isMobile) {
        //         videoPlayerStore.makeVideoTopRight();
        //         this.turnPipChatModeOff();
        //     }
        // },




    },

    // getters: {
    //     getNewMessages() {
    //         this.echo = new Echo({
    //             broadcaster: 'pusher',
    //             key: process.env.MIX_PUSHER_APP_KEY,
    //             cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    //             encrypted: true,
    //             forceTLS: true
    //         });
    //         const videoPlayer = useVideoPlayerStore();
    //     this.echo.private('chat.1')
    //         .listen('.message.new', e => {
    //             console.log('PINIA NEW MESSAGE.');
    //             console.log(e.chatMessage);
    //             // this.messages.value = e.chatMessage;
    //             axios.get('/chat/channel/' + videoPlayer.currentChannel.id + '/messages')
    //                 .then( response => {
    //                     this.messages = response.data;
    //                     // chatStore.messages = response.data;
    //                 })
    //                 .catch(error => {
    //                     console.log(error);
    //                 })
    //         });
    //     }
    // }

})
