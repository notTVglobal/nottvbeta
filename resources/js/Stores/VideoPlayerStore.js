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
            nextSource: [],
            previousSource: [],
            currentView: String,
            currentChannel: [],
            currentPage: String,
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
            ott: Number,
        }
    },

    actions: {
        toggleOtt(num) {
            if (this.ott === num) {
                this.ott = 0;
            } else {
                this.ott = num;
            }
        },
        unmute() {
            let videoJs = videojs('main-player')
            videoJs.muted(false)
        },
        pause() {
            let videoJs = videojs('main-player')
            videoJs.pause()
        },
        play() {
            let videoJs = videojs('main-player')
            videoJs.play()
        },
        next() {
            let videoJs = videojs('main-player')
            videoJs.src(this.nextSource)
        },
        back() {
            let videoJs = videojs('main-player')
            videoJs.src(this.previousSource)
        },
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
