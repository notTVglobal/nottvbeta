import { defineStore } from "pinia";
import { useAppSettingStore } from '@/Stores/AppSettingStore';
import { useStreamStore } from "@/Stores/StreamStore";
import { useUserStore } from "@/Stores/UserStore";
import { useChannelStore } from "@/Stores/ChannelStore";
import { useShowStore } from "@/Stores/ShowStore";
import videojs from 'video.js';
import { Inertia } from "@inertiajs/inertia";

const initialState = () => ({
    videoPlayerLoaded: false,
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
    currentShow: {},
    currentShowEpisode: {},
    currentVideo: {},
    hasVideo: false,
    controls: true,
    muted: true,
    paused: false,
    videoCurrentTime: '',
    blue: false, // state for testing purposes. The original test. DO NOT REMOVE.
    videoIsYoutube: false,
    videoUploadComplete: false,
})

export const useVideoPlayerStore = defineStore('videoPlayerStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
        },
        makeBlue() {
            // for testing. DO NOT REMOVE.
            this.blue = true
        },
        // video controls
        toggleMute() {
            let videoJs = videojs('main-player')
            videoJs.controls(false)
            this.muted = !this.muted
            videoJs.muted(this.muted);
        },
        togglePlay() {
            let videoJs = videojs('main-player')
            videoJs.controls(false)
            // this.paused = !this.paused
            if (videoJs.paused()) {
                videoJs.play()
                videoJs.controls(false)
                this.paused = false
            } else if (!videoJs.paused()) {
                videoJs.pause()
                videoJs.controls(false)
                this.paused = true
            }
        },
        unmute() {
            let videoJs = videojs('main-player')
            videoJs.muted(false)
            this.muted = false
            videoJs.controls(false)
        },
        mute() {
            let videoJs = videojs('main-player')
            videoJs.controls(false)
            videoJs.muted(true)
            this.muted = true
        },
        pause() {
            let videoJs = videojs('main-player')
            videoJs.controls(false)
            videoJs.pause()
            this.paused = true
        },
        play() {
            let videoJs = videojs('main-player')

            videoJs.play()
            videoJs.controls(false)
            this.paused = false
        },
        // next not built yet
        next() {
            let videoJs = videojs('main-player')
            videoJs.controls(false)
            videoJs.src(this.nextSource)
        },
        // back not built yet
        back() {
            let videoJs = videojs('main-player')
            videoJs.controls(false)
            videoJs.src(this.previousSource)
        },
        fullscreen() {
            let videoJs = videojs('main-player')
            videoJs.controls(false)
            // videoJs.fullscreen(this.previousSource)
            videoJs.requestFullscreen()
        },
        getVideoCurrentTime() {
            let videoJs = videojs('main-player')
            videoJs.controls(false)
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
            }
            return false
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
            videoJs.controls(false)
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
            videoJs.controls(false)
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
            // videoJs.controls(false)
            // this.play()
            this.unmute()
            this.paused = false
        },
        loadNewSourceFromMist(source) {
            this.videoIsYoutube = false
            let videoJs = videojs('main-player')
            let filePath = 'https://mist.not.tv/hls/'
            this.videoSource = filePath + source + '/index.m3u8'
            this.videoSourceType = "application/x-mpegURL"
            videoJs.src({'src': this.videoSource, 'type': this.videoSourceType})
            this.unmute()
            this.paused = false
        },
        loadNewSourceFromFile(source) {
            this.videoIsYoutube = false
            useChannelStore().clearChannel()
            let videoJs = videojs('main-player')
            let filePath = source.cdn_endpoint + source.cloud_folder + source.folder + '/'
            this.videoSource = source.file_name
            this.videoSourceType = source.type
            videoJs.src({'src': filePath + this.videoSource, 'type': this.videoSourceType})
            this.unmute()
            this.paused = false
        },
        setNowPlayingInfoVideoFile(source) {
            this.nowPlayingType = "Video File"
            this.nowPlayingName = source.file_name
            useStreamStore().currentChannel = 'On Demand'
        },
        setNowPlayingInfoShow(show, episode) {
            this.clearNowPlayingInfo();
            const showStore = useShowStore();

            if (show.firstPlayVideo) {
                showStore.setName(show.firstPlayVideo.name);
                showStore.setEpisodeUrl(`/shows/${show.slug}/episode/${source.firstPlayVideo.slug}`);
            } else if (episode) {
                showStore.setName(show.name);
                showStore.setUrl(`/shows/${show.slug}`);
                showStore.setEpisodeName(episode.name);
                showStore.setEpisodeUrl(`/shows/${show.slug}/episode/${episode.slug}`);
            } else {
                showStore.setName(show.name);
                showStore.setUrl(`/shows/${show.slug}`);
            }
        },
        // change video size/position and page layout
        makeVideoPiP() {
            // const appSettingStore = useAppSettingStore();
            // const userStore = useUserStore();
            //
            // if (userStore.isMobile) {
            //     let videoJs = videojs('main-player')
            //     videoJs.controls(false)
            //     if(this.fullPage) {
            //         this.class = 'pipVideoClassFullPage'
            //         this.videoContainerClass = 'pipVideoContainerFullPage'
            //     } else {
            //         this.class = 'pipVideoClassTopRight'
            //         this.videoContainerClass = 'pipVideoContainerTopRight'
            //     }
            //     appSettingStore.togglePipChatMode()
            // }
        },
        makeVideoFullPage() {
            const appSettingStore = useAppSettingStore()
            const userStore = useUserStore()

            // this.fullPage = true; // to be deleted and replaced by appSettingStore.fullPage
            appSettingStore.fullPage = true
            // userStore.hidePage = true // to be deleted and replaced by appSettingStore.hidePage
            appSettingStore.hidePage = true
            appSettingStore.ott = 0

            this.videoContainerClass = 'fullPageVideoContainer'
            this.class = 'fullPageVideoClass'
            this.controls = !userStore.isMobile
        },
        makeVideoTopRight() {
            const appSettingStore = useAppSettingStore()
            const userStore = useUserStore()

            // this.fullPage = false // to be deleted and replaced by appSettingStore.fullPage
            appSettingStore.fullPage = false
            // userStore.hidePage = false // to be deleted and replaced by appSettingStore.hidePage
            appSettingStore.hidePage = false
            // this.currentPageIsStream = false // to be deleted and replaced by appSettingStore.currentPageIsStream
            appSettingStore.currentPageIsStream = false

            this.videoContainerClass = 'topRightVideoContainer'
            this.class = 'topRightVideoClass'
            this.controls = false
        },
        makeVideoWelcomePage() {
            const appSettingStore = useAppSettingStore()
            const userStore = useUserStore()
            this.videoContainerClass = 'welcomeVideoContainer'
            this.class = 'welcomeVideoClass'
            appSettingStore.loggedIn = false
            appSettingStore.fullPage = true
            appSettingStore.hidePage = false
        },



        // change channel
        // changeChannel(name) {
        //     if (name==='one') {
        //         let source = 'mist1pull1'
        //         this.videoName = 'notTV One'
        //         this.currentChannelName = 'one'
        //         this.currentChannelId = 1
        //         this.addViewerToChannel()
        //         this.getViewerCount()
        //         this.loadNewSourceFromMist(source)
        //     }
        //     if (name==='ambient') {
        //         let source = 'mist1pull2'
        //         this.videoName = 'Ambient'
        //         this.currentChannelName = 'ambient'
        //         this.currentChannelId = 2
        //         this.addViewerToChannel()
        //         this.getViewerCount()
        //         this.loadNewSourceFromMist(source)
        //
        //     }
        //     if (name==='news') {
        //         let source = 'mist1pull3'
        //         this.videoName = 'News'
        //         this.currentChannelName = 'news'
        //         this.currentChannelId = 3
        //         this.addViewerToChannel()
        //         this.getViewerCount()
        //         this.loadNewSourceFromMist(source)
        //
        //     }
        //     if (name==='talk') {
        //         let source = 'mist1pull4'
        //         this.videoName = 'Talk'
        //         this.currentChannelName = 'talk'
        //         this.currentChannelId = 4
        //         this.addViewerToChannel()
        //         this.getViewerCount()
        //         this.loadNewSourceFromMist(source)
        //
        //     }
        //     if (name==='documentary') {
        //         let source = 'mist1pull5'
        //         this.videoName = 'Documentary'
        //         this.currentChannelName = 'documentary'
        //         this.currentChannelId = 5
        //         this.addViewerToChannel()
        //         this.getViewerCount()
        //         this.loadNewSourceFromMist(source)
        //
        //     }
        //     if (name==='music') {
        //         let source = 'mist1pull6'
        //         this.videoName = 'Music'
        //         this.currentChannelName = 'music'
        //         this.currentChannelId = 6
        //         this.addViewerToChannel()
        //         this.getViewerCount()
        //         this.loadNewSourceFromMist(source)
        //
        //     }
        //     if (name==='drama') {
        //         let source = 'mist1pull7'
        //         this.videoName = 'Drama'
        //         this.currentChannelName = 'drama'
        //         this.currentChannelId = 7
        //         this.addViewerToChannel()
        //         this.getViewerCount()
        //         this.loadNewSourceFromMist(source)
        //
        //     }
        //     if (name==='comedy') {
        //         let source = 'mist1pull8'
        //         this.videoName = 'Comedy'
        //         this.currentChannelName = 'comedy'
        //         this.currentChannelId = 8
        //         this.addViewerToChannel()
        //         this.getViewerCount()
        //         this.loadNewSourceFromMist(source)
        //
        //     }
        //     if (name==='education') {
        //         let source = 'mist1pull9'
        //         this.videoName = 'Education'
        //         this.currentChannelName = 'education'
        //         this.currentChannelId = 9
        //         this.addViewerToChannel()
        //         this.getViewerCount()
        //         this.loadNewSourceFromMist(source)
        //
        //     }
        //     if (name==='spirituality') {
        //         let source = 'mist1pull10'
        //         this.videoName = 'Spirituality'
        //         this.currentChannelName = 'spirituality'
        //         this.currentChannelId = 10
        //         this.addViewerToChannel()
        //         this.getViewerCount()
        //         this.loadNewSourceFromMist(source)
        //
        //     }
        //     if (name==='reality') {
        //         let source = 'mist1pull11'
        //         this.videoName = 'Reality'
        //         this.currentChannelName = 'reality'
        //         this.currentChannelId = 11
        //         this.addViewerToChannel()
        //         this.getViewerCount()
        //         this.loadNewSourceFromMist(source)
        //
        //     }
        //     if (name==='variety') {
        //         this.disconnectViewerFromChannel()
        //         let source = 'mist1pull12'
        //         this.videoName = 'Variety'
        //         this.currentChannelName = 'variety'
        //         this.currentChannelId = 12
        //         this.addViewerToChannel()
        //         this.getViewerCount()
        //         this.loadNewSourceFromMist(source)
        //
        //     }
        //     if (name==='sports') {
        //         this.disconnectViewerFromChannel()
        //         let source = 'mist1pull13'
        //         this.videoName = 'Sports'
        //         this.currentChannelName = 'sports'
        //         this.currentChannelId = 13
        //         this.addViewerToChannel()
        //         this.getViewerCount()
        //         this.loadNewSourceFromMist(source)
        //
        //     }
        //     if (name==='local') {
        //         this.disconnectViewerFromChannel()
        //         let source = 'mist1pull14'
        //         this.videoName = 'Local'
        //         this.currentChannelName = 'local'
        //         this.currentChannelId = 14
        //         this.addViewerToChannel()
        //         this.getViewerCount()
        //         this.loadNewSourceFromMist(source)
        //
        //     }
        //     if (name==='world') {
        //         this.disconnectViewerFromChannel()
        //         let source = 'mist1pull15'
        //         this.videoName = 'notTV World'
        //         this.currentChannelName = 'world'
        //         this.currentChannelId = 15
        //         this.addViewerToChannel()
        //         this.getViewerCount()
        //         this.loadNewSourceFromMist(source)
        //
        //     }
        // },
    },

    getters: {
        // Filter the creators and remove null values
        // Define a getter function to get valid creators
        // validCreators(state) {
        //     return state.nowPlayingCreators.filter(
        //         (creator) =>
        //             creator &&
        //             creator.id !== undefined && // Filter out undefined 'id'
        //             creator.name !== undefined // Filter out undefined 'name'
        //     )
        // }
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
        //         `console.log`(response);
        //     })
        //     .catch(error => {
        //         console.log(error);
        //     })
    },
})
