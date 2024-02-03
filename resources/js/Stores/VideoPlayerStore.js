import { defineStore } from 'pinia'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useStreamStore } from '@/Stores/StreamStore'
import { useUserStore } from '@/Stores/UserStore'
import { useChannelStore } from '@/Stores/ChannelStore'
import { useShowStore } from '@/Stores/ShowStore'
import videojs from 'video.js'
import { Inertia } from '@inertiajs/inertia'
import { usePage } from '@inertiajs/inertia-vue3'
import { ref } from 'vue'

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
    firstPlayVideoSourceType: '',
    firstPlayVideoSource: '',
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
    paused: true,
    videoCurrentTime: '',
    blue: false, // state for testing purposes. The original test. DO NOT REMOVE.
    videoIsYoutube: false,
    videoUploadComplete: false,
    audioSetupCompleted: false,
    audioLevel: 0, // Initial audio level
    volume: 100, // Initial volume level
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
        loadFirstPlay() {
            const {props} = usePage()
            let videoJs = videojs('main-player')
            const type = props.firstPlayVideoSourceType
            const src = props.firstPlayVideoSource
            videoJs.ready(() => {
                videoJs.src({src, type})
                videoJs.play().then(() => {
                    console.log('Playback started successfully')
                }).catch(error => {
                    console.error('Error trying to play the video:', error)
                    // Handle the error (e.g., showing a user-friendly message)
                })
            })
            console.log(type)
            console.log(src)
        },
        // video controls
        toggleMute() {
            const videoJs = videojs('main-player')
            // Check the current mute state and toggle it
            if (videoJs.muted()) {
                // If currently muted, start the fade-in process instead of directly unmuting
                this.fadeInAudioFromMuted();
            } else {
                // If currently unmuted, just mute the video
                videoJs.muted(true);
                this.muted = true; // Update your application's state to reflect the change
            }
            // If unmuting, try to resume the AudioContext
            if (!this.muted && audioContext.state === 'suspended') {
                audioContext.resume().then(() => {
                    console.log('AudioContext resumed successfully')
                }).catch(error => {
                    console.error('Error resuming AudioContext:', error)
                })
            }
        },
        togglePlay() {
            let videoJs = videojs('main-player')

            if (this.paused) {
                videoJs.play()
            } else {
                videoJs.pause()
            }

            // videoJs.controls(false)
            // // this.paused = !this.paused
            // if (videoJs.paused()) {
            //     videoJs.play()
            //     videoJs.controls(false)
            //     this.paused = false
            // } else if (!videoJs.paused()) {
            //     videoJs.pause()
            //     videoJs.controls(false)
            //     this.paused = true
            // }
        },
        unmute() {
            let videoJs = videojs('main-player')
            this.fadeInAudioFromMuted()
            videoJs.controls(false)

            // // Safely initialize or check the custom property
            // if (typeof videoJs.audioGraphInitialized === 'undefined') {
            //     videoPlayerStore.setupDynamicGainControl(videoJs)
            //     videoJs.audioGraphInitialized = true // Safely set the flag after initial setup
            //     console.log('Audio graph initialized:', videoJs.audioGraphInitialized)
            // } else {
            //     // If unmuting and AudioContext is suspended, try to resume it
            //     if (!this.muted && window.audioContext && window.audioContext.state === 'suspended') {
            //         window.audioContext.resume().then(() => {
            //             console.log('AudioContext resumed successfully')
            //         }).catch(error => {
            //             console.error('Error resuming AudioContext:', error)
            //         })
            //     }
            // }

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




        // This playNewVideo was created to access the audioContext
        // which our audio compressor uses. This is new as of 2/2/2024
        // ~ tec21

        // playNewVideo(source) {
        //     useChannelStore().clearChannel() // Reset or clear channel store
        //     const videoJs = videojs('main-player')
        //     let videoSrc, videoSourceType
        //     // Determine the source type and construct the source URL if necessary
        //     if (source.mediaType === 'externalVideo') {
        //         videoSrc = source.video_url // Direct URL to the video
        //         videoSourceType = source.type // MIME type, e.g., 'video/youtube', 'video/mp4'
        //     } else {
        //         // Construct file path for internal videos
        //         videoSrc = `${source.cdn_endpoint}${source.cloud_folder}${source.folder}/${source.file_name}`
        //         videoSourceType = source.type // MIME type, typically 'video/mp4' for file-based sources
        //     }
        //     this.resumeAudioContextIfNeeded()
        //     videoJs.src({src: videoSrc, type: videoSourceType})
        //     videoJs.muted(false)
        // },

        playNewVideo(source) {
            useChannelStore().clearChannel() // Reset or clear channel store
            const videoJs = videojs('main-player')

            // Determine the source type and construct the source URL if necessary
            let videoSrc, videoSourceType
            if (source.mediaType === 'externalVideo') {
                videoSrc = source.video_url // Direct URL to the video
                videoSourceType = source.type // MIME type, e.g., 'video/youtube', 'video/mp4'
            } else {
                // Construct file path for internal videos
                videoSrc = `${source.cdn_endpoint}${source.cloud_folder}${source.folder}/${source.file_name}`
                videoSourceType = source.type // MIME type, typically 'video/mp4' for file-based sources
            }

            // Update the video source
            videoJs.src({src: videoSrc, type: videoSourceType})
            this.resumeAudioContextIfNeeded();
            // this.reconnectAudioContextIfNeeded(videoJs);
            // Attempt to resume and reconnect the audio context after the source has been set
            videoJs.one('loadedmetadata', () => {

            });

            videoJs.muted(false); // Ensure video is not muted
        },

        reconnectAudioContextIfNeeded(videoJs) {
            // Assuming the video element and audio context setup are similar to previous steps
            const videoElement = videoJs.tech().el();
            // Check if the media element source needs to be reconnected
            if (!videoElement.__mediaElementSourceConnected) {
                const sourceNode = window.audioContext.createMediaElementSource(videoElement);
                // Reconnect source node to your audio nodes or directly to the destination
                sourceNode.connect(window.audioContext.destination);
                videoElement.__mediaElementSourceConnected = true; // Flag to avoid duplicate connections
            }
        },









////////////////////////////////// AUDIO DYNAMICS SETUP
///////////////////////////////////////////////////////

        // tec21: 2024-02-02 this is good... keep this here. It is loaded when the videoPlayer is Ready
        // currently we are only using the VideoPlayerMain

        // Assuming this setup is done once when the player is initially ready
        initialAudioSetup() {
            if (!this.audioSetupCompleted) {
                if (!window.audioContext) {
                    window.audioContext = new (window.AudioContext || window.webkitAudioContext)()
                }

                const videoJs = videojs('main-player')
                // const videoElement = document.querySelector('video#main-player'); // Adjust selector as needed
                if (!window.sourceNode) {
                    window.sourceNode = window.audioContext.createMediaElementSource(videoJs)
                    // Connect to destination or further processing nodes here
                    window.sourceNode.connect(window.audioContext.destination)
                }
                // Additional audio processing setup...
            }
            // Mark setup as completed to prevent re-initialization
            this.audioSetupCompleted = true;
        },

        resumeAudioContextIfNeeded() {
            // Assuming `audioContext` is accessible globally or injected into the store
            if (window.audioContext && window.audioContext.state === 'suspended') {
                window.audioContext.resume().then(() => {
                    console.log('AudioContext resumed successfully');
                    // Any additional actions after resuming can go here
                }).catch(error => {
                    console.error('Error resuming AudioContext:', error);
                });
            }
        },

        setupDynamicGainControl(player) {
            // Ensure the global audioContext is used and not re-initialized
            const audioContext = window.audioContext

            // Access the actual HTML video element from the Video.js player
            const videoElement = player.tech().el()

            // Create the media element source only once to avoid creating multiple connections for the same source
            if (!player.__mediaElementSource) {
                const source = audioContext.createMediaElementSource(videoElement)
                player.__mediaElementSource = source // Store it to reuse

                // Optionally, store gainNode for later use, e.g., in window or another global state
                const gainNode = audioContext.createGain()
                // Configure gain node and compressor as before
                gainNode.gain.setValueAtTime(1, audioContext.currentTime) // Example: start with unity gain

                const compressor = audioContext.createDynamicsCompressor()
                // Compressor settings
                // Connect the audio nodes
                source.connect(gainNode)
                gainNode.connect(compressor)
                compressor.connect(audioContext.destination)

                // Example function to adjust gain - adjust as necessary
                function adjustGainBasedOnAnalysis() {
                    gainNode.gain.setValueAtTime(1.5, audioContext.currentTime) // Example adjustment
                }

                adjustGainBasedOnAnalysis()

                console.log('Dynamic gain control setup complete');
            }
        },

        // Example function to adjust gain dynamically
        // tec21: this isn't setup yet. We can apply different gain and dynamics
        // settings to different channels... and creators can also choose settings
        // for their content that override the defaults... ** FUTURE FEATURE **
        adjustAudioForNewVideo(gainValue) {
            // Assuming gainNode is stored or accessible globally similar to audioContext
            const gainNode = window.gainNode // Ensure this is set up in your initial audio setup
            gainNode.gain.setValueAtTime(gainValue, window.audioContext.currentTime)
        },


/////////////////////////////// ADDITIONAL AUDIO FUNCTIONS
//////////////////////////////////////////////////////////

        ///////// FADE IN AUDIO FROM MUTED
        //////////////////////////////////

        fadeInAudioFromMuted(){
            const videoJs = videojs('main-player')

            // Ensure the video is not muted to allow volume changes to take effect
            videoJs.muted(false);
            this.muted = false; // Update your application's state accordingly

            // Initialize volume at 0 for the fade-in effect
            let currentVolume = 0;
            videoJs.volume(currentVolume);

            const maxVolume = 1; // Define the maximum volume as 100%
            const fadeStep = 0.05; // Define the increment step for the fade-in effect
            const fadeInterval = 100; // Define the time interval for each step in milliseconds

            const fadeAudioIn = setInterval(() => {
                if (currentVolume < maxVolume) {
                    currentVolume += fadeStep; // Increment the volume
                    currentVolume = Math.min(currentVolume, maxVolume); // Ensure it does not exceed maxVolume
                    videoJs.volume(currentVolume); // Apply the incremented volume
                } else {
                    clearInterval(fadeAudioIn); // Stop the interval once the max volume is reached
                }
            }, fadeInterval);
        },

/////////////////////////////// END AUDIO FUNCTIONS AND SETUP
/////////////////////////////////////////////////////////////









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
//     videoJs.controls(false)
//     this.unmute()
//     this.paused = false
// },
// loadNewLiveSourceFromRumble(source) {
//     this.videoIsYoutube = true
//     useChannelStore().clearChannel()
//     let videoJs = videojs('main-player')
//     this.videoSource = source
//     this.videoSourceType = "application/x-mpegURL"
//     videoJs.src({'src': this.videoSource, 'type': this.videoSourceType})
//     videoJs.controls(false)
//     this.unmute()
//     this.paused = false
// },
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
        }
        ,
// loadNewSourceFromMist(source) {
//     this.videoIsYoutube = false
//     let videoJs = videojs('main-player')
//     let filePath = 'https://mist.not.tv/hls/'
//     this.videoSource = filePath + source + '/index.m3u8'
//     this.videoSourceType = "application/x-mpegURL"
//     videoJs.src({'src': this.videoSource, 'type': this.videoSourceType})
//     this.unmute()
//     this.paused = false
// },
// loadNewSourceFromFile(source) {
//     this.videoIsYoutube = false
//     useChannelStore().clearChannel()
//     let videoJs = videojs('main-player')
//     let filePath = source.cdn_endpoint + source.cloud_folder + source.folder + '/'
//     this.videoSource = source.file_name
//     this.videoSourceType = source.type
//     videoJs.src({'src': filePath + this.videoSource, 'type': this.videoSourceType})
//     this.unmute()
//     this.paused = false
// },
        setNowPlayingInfoVideoFile(source) {
            this.nowPlayingType = 'Video File'
            this.nowPlayingName = source.file_name
            useStreamStore().currentChannel = 'On Demand'
        }
        ,
        setNowPlayingInfoShow(show, episode) {
            this.clearNowPlayingInfo()
            const showStore = useShowStore()

            if (show.firstPlayVideo) {
                showStore.setName(show.firstPlayVideo.name)
                showStore.setEpisodeUrl(`/shows/${show.slug}/episode/${source.firstPlayVideo.slug}`)
            } else if (episode) {
                showStore.setName(show.name)
                showStore.setUrl(`/shows/${show.slug}`)
                showStore.setEpisodeName(episode.name)
                showStore.setEpisodeUrl(`/shows/${show.slug}/episode/${episode.slug}`)
            } else {
                showStore.setName(show.name)
                showStore.setUrl(`/shows/${show.slug}`)
            }
        }
        ,
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
        }
        ,
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
        }
        ,
        makeVideoTopRight() {
            const appSettingStore = useAppSettingStore()

            // this.fullPage = false // to be deleted and replaced by appSettingStore.fullPage
            appSettingStore.fullPage = false
            // userStore.hidePage = false // to be deleted and replaced by appSettingStore.hidePage
            appSettingStore.hidePage = false
            // this.currentPageIsStream = false // to be deleted and replaced by appSettingStore.currentPageIsStream
            appSettingStore.currentPageIsStream = false

            this.videoContainerClass = 'topRightVideoContainer'
            this.class = 'topRightVideoClass'
            this.controls = false
        }
        ,
        makeVideoWelcomePage() {
            const appSettingStore = useAppSettingStore()
            this.videoContainerClass = 'welcomeVideoContainer'
            this.class = 'welcomeVideoClass'
            appSettingStore.loggedIn = false
            appSettingStore.fullPage = true
            appSettingStore.hidePage = false
        }
        ,


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
    }
    ,
})
