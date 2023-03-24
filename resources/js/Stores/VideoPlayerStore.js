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
            currentChannelName: '',

            // move currentPage from here to userStore.
            currentPage: String,

            currentPageIsStream: Boolean,
            fullPage: Boolean,
            loggedIn: Boolean,

            showOSD: true,
            showNav: true,
            showOttButtons: true,
            showChannels: false,
            showPlaylist: false,
            showFilters: false,

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
        // for testing
        makeBlue() {
            this.blue = true
        },

        // toggles
        toggleOSD() {
          this.showOSD = !this.showOSD
          this.showNav = ! this.showNav
          // if (this.fullPage) {
          //     this.showNav = !this.showNav;
          // }
        },
        toggleOtt(num) {
            if (this.ott === num) {
                this.ott = 0
                this.ottClass = 'OttClose'
            } else {
                this.ott = num
                this.ottClass = 'OttOpen'
            }
        },
        toggleChannels() {
            this.showOttButtons = !this.showOttButtons
            this.showChannels = !this.showChannels
            this.showOSD = !this.showOSD
        },
        togglePlaylist() {
            this.showOttButtons = !this.showOttButtons
            this.showPlaylist = !this.showPlaylist
            this.showOSD = !this.showOSD
        },
        toggleChat() {
            this.showOttButtons = !this.showOttButtons
            this.showOSD = !this.showOSD
            this.showNav = ! this.showNav
            useChatStore().toggleChat()
        },
        toggleFilters() {
            this.showOttButtons = !this.showOttButtons
            this.showFilters = !this.showFilters
            this.showOSD = !this.showOSD
        },
        toggleOttInfo() {
            this.toggleOtt(1)
        },
        toggleOttChannels() {
            this.toggleOtt(2)
        },
        toggleOttPlaylist() {
            this.toggleOtt(3)
        },
        toggleOttChat() {
            this.toggleOtt(4)
        },
        toggleOttFilters() {
            this.toggleOtt(5)
        },
        closeOtt() {
            this.toggleOtt(0)
        },

        // video controls
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
        // next not built yet
        next() {
            let videoJs = videojs('main-player')
            videoJs.src(this.nextSource)
        },
        // back not built yet
        back() {
            let videoJs = videojs('main-player')
            videoJs.src(this.previousSource)
        },

        // check if episode has a video
        checkForVideo(source) {
            if (source.mist_stream_id) {
                return true
            } else if (source.video_id) {
                return true
            } else if (source.video_url) {
                return true
            } else if (source.video_embed_code) {
                return true
            } return false
        },

        // play video from source
        playVideo(source) {
            // if mist_id exists:
            if (source.mist_stream_id) {
                this.loadNewSourceFromMist(source.mist_stream_id)
                this.videoName = source.name
                this.currentChannelName = 'On Demand (Mist)'
            }
            // if video_id exists:
            else if (source.video_id) {
                this.loadNewSourceFromFile(source.video_id)
                this.videoName = source.name
                this.currentChannelName = 'On Demand (File)'
            }
            // if url exists:
            else if (source.video_url) {
                this.loadNewSourceFromUrl(source.video_url)
                this.videoName = source.name
                this.currentChannelName = 'On Demand (Url)'
            }
            // if embed_code exists:
            else if (source.video_embed_code) {
                this.loadNewSourceFromEmbedCode(source.video_embed_code)
                this.videoName = source.name
                this.currentChannelName = 'On Demand (Embed Code)'
            }
            // else return... videoNotAvailable
            else {
                // need to create a video that can loop
                // to indicate this video is not available.
            }

        },

        // load video from different types of sources:
            // Url
            // EmbedCode
            // Mist
            // File
        loadNewSourceFromUrl(source) {
            let videoJs = videojs('main-player')
            this.videoSource = source
            this.videoSourceType = "video/mp4"
            videoJs.src({'src': this.videoSource, 'type': this.videoSourceType})
            this.unmute()
        },
        loadNewSourceFromEmbedCode(source) {
            let videoJs = videojs('main-player')
            this.videoSource = source
            this.videoSourceType = "video/mp4"
            videoJs.src({'src': this.videoSource, 'type': this.videoSourceType})
            this.unmute()
        },
        loadNewSourceFromMist(source) {
            let videoJs = videojs('main-player')
            let filePath = 'https://mist2.not.tv/hls/'
            this.videoSource = filePath+source+'/index.m3u8'
            this.videoSourceType = "application/x-mpegURL"
            videoJs.src({'src': this.videoSource, 'type': this.videoSourceType})
            this.unmute()
        },
        loadNewSourceFromFile(source) {
            let videoJs = videojs('main-player')
            let filePath = '/storage/videos/'
            this.videoName = source.file_name
            useStreamStore().currentChannel = 'On Demand'
            this.videoSource = source.file_name
            this.videoSourceType = source.type
            videoJs.src({'src': filePath+this.videoSource, 'type': this.videoSourceType})
            this.unmute()
        },

        // change video size/position and page layout
        makeVideoFullPage() {
            this.fullPage = true;
            if (useUserStore().isMobile) {
                this.videoContainerClass = 'fullPageVideoContainerMobile'
                this.class = 'fullPageVideoClassMobile'
            } else {
                this.videoContainerClass = 'fullPageVideoContainer'
                this.class = 'fullPageVideoClass'
            }
            // this.currentPageIsStream = true;
            // useChatStore().makeBig();
            // useStreamStore().showOSD = false;
            let videoJs = videojs('main-player')
            videoJs.play()
        },
        makeVideoTopRight() {
            this.currentPageIsStream = false
            this.videoContainerClass = 'topRightVideoContainer'
            this.class = 'topRightVideoClass'
            this.fullPage = false
        },
        makeVideoWelcomePage() {
            this.videoContainerClass = 'welcomeVideoContainer'
            this.class = 'welcomeVideoClass'
            this.fullPage = true
            this.loggedIn = false
            // useChatStore().chatHidden();
        },

        // change channel\
        changeChannel(name) {
            if (name==='one') {
                let source = 'mist1pull1'
                this.videoName = 'notTV One'
                this.currentChannelName = 'one'
                this.loadNewSourceFromMist(source)
            }
            if (name==='pacific') {
                let source = 'mist1pull2'
                this.videoName = 'notTV Pacific'
                this.currentChannelName = 'pacific'
                this.loadNewSourceFromMist(source)
            }
            if (name==='west') {
                let source = 'mist1pull3'
                this.videoName = 'notTV West'
                this.currentChannelName = 'west'
                this.loadNewSourceFromMist(source)
            }
            if (name==='central') {
                let source = 'mist1pull4'
                this.videoName = 'notTV Central'
                this.currentChannelName = 'central'
                this.loadNewSourceFromMist(source)
            }
            if (name==='ontario') {
                let source = 'mist1pull5'
                this.videoName = 'notTV Ontario'
                this.currentChannelName = 'ontario'
                this.loadNewSourceFromMist(source)
            }
            if (name==='east') {
                let source = 'mist1pull6'
                this.videoName = 'notTV East'
                this.currentChannelName = 'east'
                this.loadNewSourceFromMist(source)
            }
            if (name==='usa') {
                let source = 'mist1pull7'
                this.videoName = 'notTV U.S.A.'
                this.currentChannelName = 'usa'
                this.loadNewSourceFromMist(source)
            }
            if (name==='world') {
                let source = 'mist1pull8'
                this.videoName = 'notTV World'
                this.currentChannelName = 'world'
                this.loadNewSourceFromMist(source)
            }
        },
    },

    getters: {
        //
    }
})
