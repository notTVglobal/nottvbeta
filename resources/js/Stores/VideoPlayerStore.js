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
            currentPage: String,
            currentPageIsStream: Boolean,
            fullPage: Boolean,
            loggedIn: Boolean,
            showControls: Boolean,
            showNav: true,
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
        toggleControls() {
          this.showControls = !this.showControls;
          if (this.fullPage) {
              this.showNav = !this.showNav;
          }
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
        loadNewSource() {
            let videoJs = videojs('main-player')
            videoJs.src(this.videoSource)
        },
        makeVideoFullPage() {
            if (useUserStore().isMobile) {
                this.class = 'fullPageVideoClassMobile';
            } else {
                this.class = 'fullPageVideoClass';
            }
            this.currentPageIsStream = true;
            // this.class = 'fullPageVideoClass';
            this.videoContainerClass = 'fullPageVideoContainer';
            this.fullPage = true;
            useChatStore().makeBig();
            useStreamStore().showOSD = false;
            let videoJs = videojs('main-player')
            videoJs.play()
        },
        makeVideoTopRight() {
            this.currentPageIsStream = false;
            this.videoContainerClass = 'topRightVideoContainer';
            this.class = 'topRightVideoClass';
            this.fullPage = false;
            this.loggedIn = true;
            useChatStore().makeSmall();
        },
        makeVideoWelcomePage() {
            this.videoContainerClass = 'welcomeVideoContainer';
            this.class = 'welcomeVideoClass';
            this.fullPage = false;
            this.loggedIn = false;
            useChatStore().chatHidden();
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
