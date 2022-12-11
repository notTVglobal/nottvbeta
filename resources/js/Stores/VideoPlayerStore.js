import { defineStore } from "pinia";
import { useChatStore } from "@/Stores/ChatStore";
import { useStreamStore } from "@/Stores/StreamStore";
import { useUserStore } from "@/Stores/UserStore";
import videojs from 'video.js';

export let useVideoPlayerStore = defineStore('videoPlayerStore', {
    state() {
        return {
            class: [],
            videoContainerClass: [],
            ottClass: [],
            videoSourceIdSrc1: [],
            videoSourceIdSrc2: [],
            videoSourceIdSrc3: [],
            videoSourceTypeSrc1: [],
            videoSourceTypeSrc2: [],
            videoSourceTypeSrc3: [],
            key: [],
            videoName: String,
            videoSource: String,
            videoSourceType: String,
            videoPoster: String,
            nextSource: String,
            previousSource: String,
            currentView: String,
            currentChannel: [],

            // move currentPage from here to userStore.
            currentPage: String,

            currentPageIsStream: Boolean,
            fullPage: Boolean,
            loggedIn: Boolean,

            showOSD: true,
            showNav: true,
            showOttButtons: true,
            showChannels: false,

            muted: true,
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
            blue: false,
        }
    },

    actions: {
        makeBlue() {
            this.blue = true
        },
        toggleOSD() {
          this.showOSD = !this.showOSD;
          this.showNav = ! this.showNav;
          // if (this.fullPage) {
          //     this.showNav = !this.showNav;
          // }
        },
        toggleOtt(num) {
            if (this.ott === num) {
                this.ott = 0;
                this.ottClass = 'OttClose';
            } else {
                this.ott = num;
                this.ottClass = 'OttOpen';
            }
        },
        toggleChannels() {
            this.showOttButtons = !this.showOttButtons;
            this.showChannels = !this.showChannels;
            this.showOSD = !this.showOSD;
        },
        toggleChat() {
            this.showOttButtons = !this.showOttButtons;
            this.showOSD = !this.showOSD;
            this.showNav = ! this.showNav;
            useChatStore().toggleChat();
        },
        unmute() {
            let videoJs = videojs('main-player')
            videoJs.muted(false)
            this.muted = false
        },
        mute() {
            let videoJs = videojs('main-player')
            videoJs.muted(true)
            this.muted = true
        },
        pause() {
            let videoJs = videojs('main-player')
            videoJs.pause()
            this.paused = true
        },
        play() {
            let videoJs = videojs('main-player')
            videoJs.play()
            this.paused = false
        },
        next() {
            let videoJs = videojs('main-player')
            videoJs.src(this.nextSource)
        },
        back() {
            let videoJs = videojs('main-player')
            videoJs.src(this.previousSource)
        },
        loadNewSourceFromFile(source) {
            let videoJs = videojs('main-player')
            let filePath = '/storage/videos/'
            this.videoName = source.file_name;
            useStreamStore().currentChannel = 'On Demand';
            this.videoSource = source.file_name;
            this.videoSourceType = source.type;
            videoJs.src({'src': filePath+this.videoSource, 'type': this.videoSourceType});
            this.unmute()
        },
        loadNewSourceFromMist(source) {
            let videoJs = videojs('main-player')
            let filePath = 'https://mist2.not.tv/hls/'
            this.videoSource = filePath+source+'/index.m3u8';
            this.videoSourceType = "application/x-mpegURL";
            videoJs.src({'src': this.videoSource, 'type': this.videoSourceType});
            this.unmute()
        },
        makeVideoFullPage() {
            this.fullPage = true;
            if (useUserStore().isMobile) {
                this.videoContainerClass = 'fullPageVideoContainerMobile';
                this.class = 'fullPageVideoClassMobile';
            } else {
                this.videoContainerClass = 'fullPageVideoContainer';
                this.class = 'fullPageVideoClass';
            }
            // this.currentPageIsStream = true;


            // useChatStore().makeBig();
            // useStreamStore().showOSD = false;
            let videoJs = videojs('main-player')
            videoJs.play()
        },
        makeVideoTopRight() {
            this.currentPageIsStream = false;
            this.videoContainerClass = 'topRightVideoContainer';
            this.class = 'topRightVideoClass';
            this.fullPage = false;
        },
        makeVideoWelcomePage() {
            this.videoContainerClass = 'welcomeVideoContainer';
            this.class = 'welcomeVideoClass';
            this.fullPage = true;
            this.loggedIn = false;
            // useChatStore().chatHidden();
        },
        toggleOttChannels() {
            this.toggleOtt(1);
        },
        toggleOttInfo() {
            this.toggleOtt(2);
        },
        toggleOttPlaylist() {
            this.toggleOtt(3);
        },
        toggleOttChat() {
            this.toggleOtt(4);
        },
        toggleOttFilters() {
            this.toggleOtt(5);
        },
        closeOtt() {
            this.toggleOtt(0);
        }
    },

    getters: {
        //
    }
})
