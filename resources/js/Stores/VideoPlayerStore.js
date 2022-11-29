import { defineStore } from "pinia";
import { useChatStore } from "@/Stores/ChatStore";
import videojs from 'video.js';

export let useVideoPlayerStore = defineStore('videoPlayer', {
    state() {
        return {
            class: [],
            videoContainerClass: [],
            videoSourceIdSrc1: [],
            videoSourceIdSrc2: [],
            videoSourceIdSrc3: [],
            videoSourceTypeSrc1: [],
            videoSourceTypeSrc2: [],
            videoSourceTypeSrc3: [],
            key: [],
            videoName: [],
            videoSource: [],
            currentView: String,
            currentChannel: [],
            fullPage: Boolean,
            loggedIn: Boolean,
            muted: Boolean,
            paused: Boolean,
            apiRequest: [],
            challenge: [],
            status: [],
            apiResponse: [],
            apiActiveStreams: [],
            mistUsername: [],
            mistPassword: [],
            mistStatus: Boolean,
            mistDisplayPushForm: Boolean,
            mistDisplay: String,
            mistNewHashedPassword: [],
        }
    },

    actions: {
        loadNewSource() {
            let videoJs = videojs('main-player')
            videoJs.src(this.videoSource)
        },
        makeVideoFullPage() {
            this.videoContainerClass = 'videoContainerFullPage';
            this.class = 'videoFullPage';
            this.fullPage = true;
            useChatStore().makeBig();
            let videoJs = videojs('main-player')
            videoJs.play()
        },
        makeVideoTopRight() {
            this.videoContainerClass = 'videoContainerTopRight';
            this.class = 'videoTopRight';
            this.fullPage = false;
            this.loggedIn = true;
            useChatStore().makeSmall();
        },
        makeVideoWelcomePage() {
            this.videoContainerClass = 'videoContainerWelcome';
            this.class = 'videoBgWelcome';
            this.fullPage = false;
            this.loggedIn = false;
            useChatStore().chatHidden();
        },
    },

    getters: {
        //
    }
})
