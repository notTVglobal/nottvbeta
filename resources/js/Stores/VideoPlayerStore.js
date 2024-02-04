import { defineStore } from 'pinia'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useStreamStore } from '@/Stores/StreamStore'
import { useUserStore } from '@/Stores/UserStore'
import { useChannelStore } from '@/Stores/ChannelStore'
import { useShowStore } from '@/Stores/ShowStore'
import { useAudioStore } from '@/Stores/AudioStore'
import videojs from 'video.js'
import { usePage } from '@inertiajs/inertia-vue3'
import { nextTick } from 'vue'

const initialState = () => ({
    player: null, // Video.js player instance
    eventListenersAttached: false, // Track if listeners are attached
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
    // videoCurrentTime: '',
    currentTime: 0, // Current playback time in seconds
    duration: 0, // Total video duration in seconds
    formattedTime: '00:00 / 00:00', // Formatted time string
    blue: false, // DO NOT REMOVE
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

        // Initialize or update the player instance
        setPlayer(playerInstance) {
            this.player = playerInstance;
            this.initializePlayer().then(() => {
                this.attachEventListeners(); // Attach event listeners after initialization
            }).catch(error => {
                console.error('Error during video player initialization:', error);
            });
        },

        // Initialize the player with necessary settings and start playback
        async initializePlayer() {
            if (!this.player) {
                console.error('Video.js player is not initialized.');
                return;
            }

            await nextTick(); // Ensure Vue's DOM updates are processed

            // Perform any necessary audio context and node setup
            await useAudioStore().ensureAudioContextAndNodesReady(this.player);

            // Apply initial player settings
            this.player.controls(false);
            this.player.muted(this.muted);

            // Attempt to start playback
            this.player.ready(() => {
                this.player.play().then(() => {
                    console.log('Playback started successfully');
                }).catch(error => {
                    console.error('Error trying to play the video:', error);
                    // Handle the error (e.g., showing a user-friendly message)
                });
            });
        },

        // Attach event listeners to the player
        attachEventListeners() {
            if (!this.player || this.eventListenersAttached) {
                console.log('Event listeners are already attached or video player is not initialized.');
                return;
            }

            this.player.on('timeupdate', this.handleTimeUpdate);
            this.player.on('fullscreenchange', this.handleFullscreenChange);
            this.player.on('play', this.handlePlay);
            this.player.on('pause', this.handlePause);
            this.player.on('error', this.handleError);

            this.eventListenersAttached = true;
            console.log('Event listeners attached.');
        },

        // Detach event listeners from the player
        detachEventListeners() {
            if (!this.player || !this.eventListenersAttached) {
                console.log('Event listeners are already detached or video player is not initialized.');
                return;
            }

            useAudioStore().stopAudioLevelMonitoring()
            this.player.off('timeupdate', this.handleTimeUpdate);
            this.player.off('fullscreenchange', this.handleFullscreenChange);
            this.player.off('play', this.handlePlay);
            this.player.off('pause', this.handlePause);
            this.player.off('error', this.handleError);

            this.eventListenersAttached = false;
            console.log('Event listeners detached.');
        },

        // Dispose of the player and perform cleanup
        disposePlayer() {
            if (!this.player) {
                console.error('Video player is not initialized.');
                return;
            }

            this.detachEventListeners(); // Detach event listeners if attached
            this.player.dispose(); // Dispose of the player instance
            this.player = null; // Reset the player state

            // Optionally, stop audio level monitoring if linked to the player lifecycle
            // const audioStore = useAudioStore();
            // audioStore.stopAudioLevelMonitoring();

            console.log('Video player disposed and cleaned up.');
        },

        // New method to prepare for a new video source
        prepareForNewVideoSource(source) {
            console.log('Preparing for new video source');

            // Example: Clear any existing channel or video-specific state
            useChannelStore().clearChannel();

            // Reset or cleanup any existing video playback, if necessary
            this.resetPlayback();

            // Load and play the new video source
            this.loadNewVideo(source);
        },

        resetPlayback() {
            if (this.player) {
                this.player.pause();
                this.player.muted(true)
                this.player.currentTime(0); // Optionally reset the time
                // Further cleanup logic here, if necessary
                this.detachEventListeners(); // Detach event listeners if attached
            }
        },

        // Event handlers
        handleTimeUpdate() {
            // console.log('Handling timeupdate...');
            // Implement your logic
            this.currentTime = this.player.currentTime();
            this.duration = this.player.duration();
            const progressPercentage = (this.currentTime / this.duration) * 100;
            this.formattedTime = `${this.formatDuration(this.currentTime)} / ${this.formatDuration(this.duration)}`;

            // Update UI elements or emit events as needed
            // Note: Direct manipulation of the DOM or component refs from the store is not recommended

        },
        // for the handleTimeUpdate eventHandler
        formatDuration(durationInSeconds) {
            const hours = Math.floor(durationInSeconds / 3600);
            const minutes = Math.floor((durationInSeconds % 3600) / 60);
            const seconds = Math.floor(durationInSeconds % 60);

            const parts = [hours, minutes, seconds].map(part => part.toString().padStart(2, '0'));
            return parts.join(':');
        },
        handleFullscreenChange() {
            console.log('Handling fullscreenchange...');
            // Implement your logic
            this.player.on('fullscreenchange', () => {
                if (this.player.isFullscreen()) {
                    // Video is entering fullscreen mode
                    // You can add custom behavior for entering fullscreen here if needed
                } else {
                    // Video is exiting fullscreen mode
                    // Check if the video was playing before entering fullscreen
                    if (this.player.paused() === false) {
                        // Resume playback after exiting fullscreen
                        this.player.play()
                    }
                }
            })
        },
        handlePlay() {
            console.log('Handling play...');
            // Implement your logic
            this.player.on('play', () => {
                this.paused = false
            })
        },
        handlePause() {
            console.log('Handling pause...');
            // Implement your logic
            this.player.on('pause', () => {
                this.paused = true
            })
        },
        handleError() {
            console.log('Handling error...');
            // Implement your logic
            this.player.on('error', function () {
                const error = this.player.error()
                console.error('Video.js Error:', error.code, error.message)
            })
        },
        makeBlue() {
            // for testing. DO NOT REMOVE.
            this.blue = true
        },
        // Apparently this loadFirstPlay isn't being used...
        // loadFirstPlay() {
        //     const {props} = usePage()
        //     let videoJs = videojs('main-player')
        //     const type = props.firstPlayVideoSourceType
        //     const src = props.firstPlayVideoSource
        //     videoJs.ready(() => {
        //         videoJs.src({src, type})
        //         videoJs.play().then(() => {
        //             console.log('Playback started successfully')
        //         }).catch(error => {
        //             console.error('Error trying to play the video:', error)
        //             // Handle the error (e.g., showing a user-friendly message)
        //         })
        //     })
        //     console.log(type)
        //     console.log(src)
        // },
        // Toggle mute state
        toggleMute() {
            if (this.muted) {
                this.unMute();
            } else {
                this.mute();
            }
        },
        // Mute the video
        mute() {
            if (this.player) {
                this.player.muted(true);
                this.muted = true;
                console.log("Video muted");
            }
        },
        // Unmute the video
        unMute() {
            const audioStore = useAudioStore();

            if (this.player) {
                // Prepare audio setup for when it's unmuted
                audioStore.userInteractionForAudio();

                // Optionally, if fadeInAudioFromMuted is a gradual process,
                // ensure this.player.muted(false) is called within that function.
                audioStore.fadeInAudioFromMuted();

                this.muted = false;
                console.log("Video unmuted");
            }
        },
        togglePlay() {
            let videoJs = videojs('main-player')

            if (this.paused) {
                videoJs.play()
            } else {
                videoJs.pause()
            }
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

        // getSourceDetails(source) {
        //     let videoSrc = source.video_url; // Directly access the video URL
        //
        //     // Default to 'video/mp4' if type is falsy ('', null, undefined, etc.)
        //     let videoSourceType = source.type || 'video/mp4'
        //
        //     // Determine the mediaType and construct the source URL if necessary
        //     if (source.mediaType === 'externalVideo') {
        //         videoSrc = source.video_url // Direct URL to the video
        //     } else {
        //         // Internal video: construct the path, ensuring the file name is encoded
        //         let encodedFileName = encodeURIComponent(source.file_name);
        //         console.log(encodedFileName)
        //         videoSrc = `${source.cdn_endpoint}${source.cloud_folder}${source.folder}/${encodedFileName}`
        //     }
        //     // Logic to determine videoSrc and videoSourceType
        //     console.log(`Video Source: ${videoSrc}, Type: ${videoSourceType}`);
        //     return { videoSrc, videoSourceType } // Return as an object
        // },


        getSourceDetails(source) {
            let videoSrc, videoSourceType;

            // Default to 'video/mp4' if type is not specified or is empty
            videoSourceType = source.type || 'video/mp4';

            if (source.mediaType === 'externalVideo') {
                // For external videos, use the URL as provided without encoding
                videoSrc = source.video_url;
            } else {
                // For internal videos, construct the URL from its components
                // Here, we assume the cdn_endpoint, cloud_folder, and folder are correctly formatted
                // and do not require encoding. Only the file_name might need encoding.
                const basePath = `${source.cdn_endpoint}${source.cloud_folder}${source.folder}/`;
                // const encodedFileName = encodeURIComponent(source.file_name);
                const fileName = source.file_name
                videoSrc = basePath + fileName;

                // If your server or CDN is configured to handle spaces in URLs without %20 encoding
                // or if the original working URLs did not use standard URL encoding,
                // you might adjust the encoding strategy here.
                // For example, to replace spaces with %20 but leave other characters as-is:
                // const fileNameForUrl = source.file_name.replace(/ /g, '%20');
                // videoSrc = basePath + fileNameForUrl;
            }

            console.log(`Constructed Video Source: ${videoSrc}, Type: ${videoSourceType}`);
            return { videoSrc, videoSourceType };
        },


        loadNewVideo(source) {
            console.log('LOAD NEW VIDEO')
            const audioStore = useAudioStore()
            // Correctly destructure the returned object to get videoSrc and videoSourceType
            const { videoSrc, videoSourceType } = this.getSourceDetails(source);

            // Example: Stopping and cleaning up the current video and audio setup
            if (this.player) {
                this.player.src({ 'src': videoSrc, 'type': videoSourceType });

                this.player.ready(() => {
                    // ensureAudioContextAndNodesReady does the following:
                    // 1. Resumes AudioContext if suspended.
                    // 2. (Re)connects MediaElementSource from the video element to AudioContext.
                    audioStore.deferAudioSetup = false
                    audioStore.ensureAudioContextAndNodesReady(this.player).then(() => {
                        // Only attempt to play the video after ensuring the AudioContext is ready
                        this.player.play().catch(error => {
                            console.error('Playback initiation error:', error);
                        });

                        // Consider toggling mute based on the user's preference or previous state
                        this.player.muted(false);
                        this.muted = false
                    });
                });


                //     useAudioStore().ensureAudioContextAndNodesReady(this.player);
                //     this.attachEventListeners(); // Reattach event listeners as needed
                //
                //     this.unMute()
                // });

            }
        },

        playNewVideo() {
            console.log('PLAY NEW VIDEO')
            if (this.player) {
                // Wait for the video to be ready before playing
                this.player.ready(() => {
                    this.player.play();
                    // Assuming you want to unmute here; check if this aligns with user interaction policies
                    this.player.muted(false);
                    this.muted = false
                });
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
        },
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
loadNewSourceFromFile(source) {
    this.videoIsYoutube = false
    useChannelStore().clearChannel()
    let videoJs = videojs('main-player')
    let filePath = source.cdn_endpoint + source.cloud_folder + source.folder + '/'
    this.videoSource = source.file_name
    this.videoSourceType = source.type
    videoJs.src({'src': filePath + this.videoSource, 'type': this.videoSourceType})
    this.unMute()
    this.paused = false
},
        setNowPlayingInfoVideoFile(source) {
            this.nowPlayingType = 'Video File'
            this.nowPlayingName = source.file_name
            useStreamStore().currentChannel = 'On Demand'
        },
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
            this.videoContainerClass = 'welcomeVideoContainer'
            this.class = 'welcomeVideoClass'
            appSettingStore.loggedIn = false
            appSettingStore.fullPage = true
            appSettingStore.hidePage = false
        },


    },

    getters: {
    },
})
