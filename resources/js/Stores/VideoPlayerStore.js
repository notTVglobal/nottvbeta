import { defineStore } from "pinia";
import { useChatStore } from "@/Stores/ChatStore";
import { useStreamStore } from "@/Stores/StreamStore";
import { useUserStore } from "@/Stores/UserStore";
import videojs from 'video.js';
import {Inertia} from "@inertiajs/inertia";
import {useChannelStore} from "@/Stores/ChannelStore";

export const useVideoPlayerStore = defineStore('videoPlayerStore', {
    state: () => ({
            class: '',
            videoContainerClass: '',
            ottClass: 'OttClose',
            videoSourceIdSrc1: '',
            videoSourceIdSrc2: '',
            videoSourceIdSrc3: '',
            videoSourceTypeSrc1: '',
            videoSourceTypeSrc2: '',
            videoSourceTypeSrc3: '',
            key: '',
            videoName: '',
            videoSource: String,
            videoSourceType: String,
            videoPoster: String,
            nextSource: String,
            previousSource: String,
            currentView: String,
            currentChannelId: 0,
            currentChannelName: String,
            viewerCount: 0,

            // move currentPage from here to userStore.
            currentPage: String,
            hasVideo: Boolean,

            currentPageIsStream: Boolean,
            fullPage: Boolean,
            loggedIn: Boolean,

            showOSD: true,
            showControls: true,
            showNav: true,
            showOttButtons: true,
            showChannels: false,
            showPlaylist: false,
            showFilters: false,

            muted: true,
            paused: Boolean,
            videoCurrentTime: '',
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
            ott: 0,
            blue: false,
            videoIsYoutube: false,
            videoUploadComplete: false,
        }),

    actions: {
        // for testing
        makeBlue() {
            this.blue = true
        },

        // show
        showOsdAndControls() {
            this.showOSD = true
            this.showControls = true
        },
        showOsdAndControlsAndNav() {
            this.showOSD = true
            this.showControls = true
            this.showNav = true
            this.showOttButtons = true
        },
        showOsd() {
            this.showOSD = true
        },
        showOsdControlsOnly() {
            this.showControls = true
        },

        // hide
        hideOsdAndControls() {
            this.showOSD = false
            this.showControls = false
        },
        hideOsdAndControlsAndNav() {
            this.showOSD = false
            this.showControls = false
            this.showNav = false
            this.showOttButtons = false
        },
        hideOsd() {
            this.showOSD = false
        },
        hideOsdControlsOnly() {
            this.showControls = false
        },

        // toggles
        togglePiP() {
            if (this.class === 'topRightVideoClass' && useUserStore().isMobile) {
                this.class = 'PipVideoClass'
                this.videoContainerClass = 'PipVideoContainerClass'
            } else
                this.class = 'topRightVideoClass'
                this.videoContainerClass = 'topRightVideoContainer'
        },
        toggleOSD() {
            this.showOSD = !this.showOSD
            this.showControls = !this.showControls
            this.showNav = ! this.showNav
            // if (this.fullPage) {
            //     this.showNav = !this.showNav;
            // }
        },
        toggleOsdAndControls() {
            this.showOSD = !this.showOSD
            this.showControls = !this.showControls
          // this.showNav = ! this.showNav
          // if (this.fullPage) {
          //     this.showNav = !this.showNav;
          // }
        },
        toggleOsdAndControlsAndNav() {
            this.showOSD = !this.showOSD
            this.showControls = !this.showControls
            this.showNav = !this.showNav
            this.showOttButtons = !this.showOttButtons
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
            this.showControls = !this.showControls
            this.showChannels = !this.showChannels
        },
        togglePlaylist() {
            this.showOttButtons = !this.showOttButtons
            this.showControls = !this.showControls
            this.showPlaylist = !this.showPlaylist
            // this.showOSD = !this.showOSD
        },
        toggleChat() {
            this.showOttButtons = !this.showOttButtons
            this.showControls = !this.showControls
            // this.showOSD = !this.showOSD
            // this.showNav = ! this.showNav
            useChatStore().toggleChat()
        },
        toggleFilters() {
            this.showOttButtons = !this.showOttButtons
            this.showControls = !this.showControls
            this.showFilters = !this.showFilters
            // this.showOSD = !this.showOSD
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
            this.ottClass = 'ottClose'
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
        fullscreen() {
            let videoJs = videojs('main-player')
            // videoJs.fullscreen(this.previousSource)
            videoJs.requestFullscreen()
        },
        getVideoCurrentTime() {
            let videoJs = videojs('main-player')
            this.videoCurrentTime = videoJs.currentTime
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
                Inertia.visit('/stream')
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
            // YouTube
            // EmbedCode
            // Mist
            // File
        // loadNewSourceFromYouTube(source) {
        //     this.videoIsYoutube = true
        //     useChannelStore().clearChannel()
        //     let videoJs = videojs('main-player')
        //     this.videoSource = source
        //     this.videoSourceType = "video/youtube"
        //     videoJs.src({'src': this.videoSource, 'type': this.videoSourceType})
        //     this.unmute()
        //     this.paused = false
        // },
        loadNewSourceFromUrl(source) {
            this.videoIsYoutube = false
            // useChannelStore().clearChannel()
            let videoJs = videojs('main-player')
            this.videoSource = source
            this.videoSourceType = "video/mp4"
            videoJs.src({'src': this.videoSource, 'type': this.videoSourceType})
            // this.play()
            this.unmute()
            this.paused = false
        },
        loadNewSourceFromEmbedCode(source) {
            this.videoIsYoutube = false
            useChannelStore().clearChannel()
            let videoJs = videojs('main-player')
            this.videoSource = source
            this.videoSourceType = "video/mp4"
            videoJs.src({'src': this.videoSource, 'type': this.videoSourceType})
            this.unmute()
            this.paused = false
        },
        loadNewSourceFromMist(source) {
            this.videoIsYoutube = false
            let videoJs = videojs('main-player')
            let filePath = 'https://mist.not.tv/hls/'
            this.videoSource = filePath+source+'/index.m3u8'
            this.videoSourceType = "application/x-mpegURL"
            videoJs.src({'src': this.videoSource, 'type': this.videoSourceType})
            this.unmute()
            this.paused = false
        },
        loadNewSourceFromFile(source) {
            this.videoIsYoutube = false
            useChannelStore().clearChannel()
            let videoJs = videojs('main-player')
            let filePath = source.cdn_endpoint+source.cloud_folder+source.folder+'/'
            this.videoName = "Video File"
            useStreamStore().currentChannel = 'On Demand'
            this.videoSource = source.file_name
            this.videoSourceType = source.type
            videoJs.src({'src': filePath+this.videoSource, 'type': this.videoSourceType})
            this.unmute()
            this.paused = false
        },

        // change video size/position and page layout
        makeVideoPiP() {
            if (useUserStore().isMobile) {
                this.class = 'PipVideoClass'
                this.videoContainerClass = 'PipVideoContainerClass'
            }
        },
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

            // tec21: this lets the video start playing when the stream page is loaded.
            // but it's preventing the videoPlayer from mounting.
            // let videoJs = videojs.getPlayer('main-player')
            // videoJs.play()
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


        // change channel
        changeChannel(name) {
            if (name==='one') {
                let source = 'mist1pull1'
                this.videoName = 'notTV One'
                this.currentChannelName = 'one'
                this.currentChannelId = 1
                this.addViewerToChannel()
                this.getViewerCount()
                this.loadNewSourceFromMist(source)
            }
            if (name==='ambient') {
                let source = 'mist1pull2'
                this.videoName = 'Ambient'
                this.currentChannelName = 'ambient'
                this.currentChannelId = 2
                this.addViewerToChannel()
                this.getViewerCount()
                this.loadNewSourceFromMist(source)

            }
            if (name==='news') {
                let source = 'mist1pull3'
                this.videoName = 'News'
                this.currentChannelName = 'news'
                this.currentChannelId = 3
                this.addViewerToChannel()
                this.getViewerCount()
                this.loadNewSourceFromMist(source)

            }
            if (name==='talk') {
                let source = 'mist1pull4'
                this.videoName = 'Talk'
                this.currentChannelName = 'talk'
                this.currentChannelId = 4
                this.addViewerToChannel()
                this.getViewerCount()
                this.loadNewSourceFromMist(source)

            }
            if (name==='documentary') {
                let source = 'mist1pull5'
                this.videoName = 'Documentary'
                this.currentChannelName = 'documentary'
                this.currentChannelId = 5
                this.addViewerToChannel()
                this.getViewerCount()
                this.loadNewSourceFromMist(source)

            }
            if (name==='music') {
                let source = 'mist1pull6'
                this.videoName = 'Music'
                this.currentChannelName = 'music'
                this.currentChannelId = 6
                this.addViewerToChannel()
                this.getViewerCount()
                this.loadNewSourceFromMist(source)

            }
            if (name==='drama') {
                let source = 'mist1pull7'
                this.videoName = 'Drama'
                this.currentChannelName = 'drama'
                this.currentChannelId = 7
                this.addViewerToChannel()
                this.getViewerCount()
                this.loadNewSourceFromMist(source)

            }
            if (name==='comedy') {
                let source = 'mist1pull8'
                this.videoName = 'Comedy'
                this.currentChannelName = 'comedy'
                this.currentChannelId = 8
                this.addViewerToChannel()
                this.getViewerCount()
                this.loadNewSourceFromMist(source)

            }
            if (name==='education') {
                let source = 'mist1pull9'
                this.videoName = 'Education'
                this.currentChannelName = 'education'
                this.currentChannelId = 9
                this.addViewerToChannel()
                this.getViewerCount()
                this.loadNewSourceFromMist(source)

            }
            if (name==='spirituality') {
                let source = 'mist1pull10'
                this.videoName = 'Spirituality'
                this.currentChannelName = 'spirituality'
                this.currentChannelId = 10
                this.addViewerToChannel()
                this.getViewerCount()
                this.loadNewSourceFromMist(source)

            }
            if (name==='reality') {
                let source = 'mist1pull11'
                this.videoName = 'Reality'
                this.currentChannelName = 'reality'
                this.currentChannelId = 11
                this.addViewerToChannel()
                this.getViewerCount()
                this.loadNewSourceFromMist(source)

            }
            if (name==='variety') {
                this.disconnectViewerFromChannel()
                let source = 'mist1pull12'
                this.videoName = 'Variety'
                this.currentChannelName = 'variety'
                this.currentChannelId = 12
                this.addViewerToChannel()
                this.getViewerCount()
                this.loadNewSourceFromMist(source)

            }
            if (name==='sports') {
                this.disconnectViewerFromChannel()
                let source = 'mist1pull13'
                this.videoName = 'Sports'
                this.currentChannelName = 'sports'
                this.currentChannelId = 13
                this.addViewerToChannel()
                this.getViewerCount()
                this.loadNewSourceFromMist(source)

            }
            if (name==='local') {
                this.disconnectViewerFromChannel()
                let source = 'mist1pull14'
                this.videoName = 'Local'
                this.currentChannelName = 'local'
                this.currentChannelId = 14
                this.addViewerToChannel()
                this.getViewerCount()
                this.loadNewSourceFromMist(source)

            }
            if (name==='world') {
                this.disconnectViewerFromChannel()
                let source = 'mist1pull15'
                this.videoName = 'notTV World'
                this.currentChannelName = 'world'
                this.currentChannelId = 15
                this.addViewerToChannel()
                this.getViewerCount()
                this.loadNewSourceFromMist(source)

            }
        },
    },

    getters: {
        // incrementViewerCount() {
        //     this.viewerCount++
        // },
        // decrementViewerCount() {
        //     this.viewerCount++
        // },
        // incrementViewerCount: (state) => this.state.viewerCount++,
        // decrementViewerCount: (state) => this.state.viewerCount--,
        // incrementViewerCount(state) {
        //     return state.viewerCount++
        // },
        // decrementViewerCount(state) {
        //     return state.viewerCount--
        // },
        // updateViewerCount() {
        //     const channel = Echo.private('channel.' + this.currentChannelId)
        //     channel.subscribed(() => {
        //     }).listen('channel.' + this.currentChannelId, (event) => {
        //         if (event.channel_id === this.currentChannelId) {
        //             this.viewerCount = this.viewerCount + event.viewerCount;
        //         }
        //         console.log('channel connected')
        //     })
        // }

        // addViewer() {
        //     axios.post('/api/addCurrentViewer', {'channel_id': this.currentChannelId, 'user_id': useUserStore().id})
        //     .then(response => {
        //         console.log(response);
        //     })
        //     .catch(error => {
        //         console.log(error);
        //     })
    },
})
