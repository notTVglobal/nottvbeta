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
            // ottClass: 'OttClose',
            videoSourceIdSrc1: '',
            videoSourceIdSrc2: '',
            videoSourceIdSrc3: '',
            videoSourceTypeSrc1: '',
            videoSourceTypeSrc2: '',
            videoSourceTypeSrc3: '',
            key: '',
            videoName: '',
            videoSource: '',
            videoSourceType: '',
            videoPoster: '',
            nextSource: '',
            previousSource: '',
            currentView: '',
            currentChannelId: 0,
            currentChannelName: '',
            viewerCount: 0,

            // move currentPage from here to userStore.
            currentPage: '',
            hasVideo: false,

            currentPageIsStream: false,
            fullPage: false,
            pip: false,
            loggedIn: false,

            osd: true,
            controls: true,
            ottButtons: true,
            ottChannels: false,
            ottPlaylist: false,
            ottFilters: false,
            ottChat: false,

            muted: true,
            paused: false,
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
            this.osd = true
            this.controls = true
        },
        showOsdAndControlsAndNav() {
            this.osd = true
            this.controls = true
            // useUserStore().showNavDropdown = true
            this.ottButtons = true
        },
        showOsd() {
            this.osd = true
        },
        showOsdControlsOnly() {
            this.controls = true
        },

        // hide
        hideOsdAndControls() {
            this.osd = false
            this.controls = false
        },
        hideOsdAndControlsAndNav() {
            this.osd = false
            this.controls = false
            // useUserStore().showNavDropdown = false
            this.ottButtons = false
        },
        hideOsd() {
            this.osd = false
        },
        hideOsdControlsOnly() {
            this.controls = false
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
            this.osd = !this.osd
            this.controls = !this.controls
        },
        toggleControls() {
            this.controls = !this.controls
        },
        toggleOsdAndControls() {
            this.osd = !this.osd
            this.controls = !this.controls
        },
        toggleOsdAndControlsAndNav() {
            this.osd = !this.osd
            this.controls = !this.controls
            this.ottButtons = !this.ottButtons
        },
        toggleOtt(num) {
            if (this.ott === num) {
                this.ott = 0
            } else {
                this.ott = num
            }
        },
        toggleChannels() {
            this.ottButtons = !this.ottButtons
            this.osd = !this.osd
            this.controls = !this.controls
            this.ottChannels = !this.ottChannels
        },
        togglePlaylist() {
            this.ottButtons = !this.ottButtons
            this.osd = !this.osd
            this.controls = !this.controls
            this.ottPlaylist = !this.ottPlaylist
        },
        toggleChat() {
            this.ottButtons = !this.ottButtons
            this.osd = !this.osd
            this.controls = !this.controls
            this.ottChat = !this.ottChat
        },
        toggleFilters() {
            this.ottButtons = !this.ottButtons
            this.osd = !this.osd
            this.controls = !this.controls
            this.ottFilters = !this.ottFilters
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
            // useChannelStore().clearChannel();
            // if mist_id exists:
            if (source.mist_stream_id) {
                this.loadNewSourceFromMist(source.mist_stream_id)
                this.videoName = source.name
            }
            // if video_id exists:
            else if (source.video_id) {
                this.loadNewSourceFromFile(source.video_id)
                this.videoName = source.name
            }
            // if url exists:
            else if (source.video_url) {
                this.loadNewSourceFromUrl(source.video_url)
                this.videoName = source.name
                Inertia.visit('/stream')
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
        loadNewSourceFromYouTube(source) {
            this.videoIsYoutube = true
            useChannelStore().clearChannel()
            let videoJs = videojs('main-player')
            this.videoSource = source
            this.videoSourceType = "video/youtube"
            videoJs.src({'src': this.videoSource, 'type': this.videoSourceType})
            this.unmute()
            this.paused = false
        },
        loadNewLiveSourceFromRumble(source) {
            this.videoIsYoutube = true
            useChannelStore().clearChannel()
            let videoJs = videojs('main-player')
            this.videoSource = source
            this.videoSourceType = "application/x-mpegURL"
            videoJs.src({'src': this.videoSource, 'type': this.videoSourceType})
            this.unmute()
            this.paused = false
        },
        loadNewSourceFromUrl(source) {
            this.videoIsYoutube = false
            useChannelStore().clearChannel()
            let videoJs = videojs('main-player')
            this.videoSource = source.video_url
            this.videoSourceType = source.type
            videoJs.src({'src': source.video_url, 'type': source.type})
            // this.play()
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
                let videoJs = videojs('main-player')
                this.class = 'pipVideoClass'
                this.videoContainerClass = 'pipVideoContainerClass'
            }
        },
        makeVideoFullPage() {
            this.fullPage = true;
            this.videoContainerClass = 'fullPageVideoContainer'
            this.class = 'fullPageVideoClass'
            // if (useUserStore().isMobile) {
            //     this.videoContainerClass = 'fullPageVideoContainerMobile'
            //     this.class = 'fullPageVideoClassMobile'
            // } else {
            //     this.videoContainerClass = 'fullPageVideoContainer'
            //     this.class = 'fullPageVideoClass'
            // }
            // this.currentPageIsStream = true;
            // useChatStore().makeBig();
            // useStreamStore().osd = false;

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
