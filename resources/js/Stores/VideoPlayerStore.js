import { defineStore } from 'pinia'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useStreamStore } from '@/Stores/StreamStore'
import { useUserStore } from '@/Stores/UserStore'
import { useChannelStore } from '@/Stores/ChannelStore'
import { useShowStore } from '@/Stores/ShowStore'
import { useAudioStore } from '@/Stores/AudioStore'
import { useNowPlayingStore } from '@/Stores/NowPlayingStore'
import videojs from 'video.js'
import { usePage } from '@inertiajs/vue3'
import { nextTick } from 'vue'
import { useNotificationStore } from '@/Stores/NotificationStore'
import { router } from '@inertiajs/vue3'

const initialState = () => ({
    mistServerUri: 'https://mist.not.tv/', // tec21: 2024-02-09, if we don't start with the address here changing channels is really slow and buggy. Address for the MistServer listed in the Admin Settings saved in AppSetting
    player: null, // Video.js player instance
    videoElementId: 'main-player',
    playerOptions: {
        controlBar: {
            audioTrackButton: false,
            autoHide: true,
            captionsButton: false,
            chaptersButton: false,
            currentTimeDisplay: false,
            customControlSpacer: false,
            descriptionsButton: false,
            durationDisplay: false,
            fullscreenToggle: false, // Removes the full screen option
            liveDisplay: false,
            pictureInPictureToggle: false,
            playToggle: false,
            playbackRateMenuButton: false,
            progressControl: {seekBar: false},
            remainingTimeDisplay: false,
            seekToLive: false,
            subsCapsButton: false,
            subtitlesButton: false,
            timeDivider: false,
            volumePanel: {
                inline: true,
                volumeBar: {
                    vertical: false,
                    liveDisplay: false,
                },
            },
            userActions: {
                hotkeys: true, // Enable hotkeys to show/hide controls
            },
            controls: false,
            muted: true, // Start muted to comply with autoplay policies
            inactivityTimeout: 0,
            autoplay: true,
            preload: 'auto',
            // techOrder: ['html5'],
            html5: {
                hls: {
                    overrideNative: !videojs.browser.IS_SAFARI, // Override native HLS on non-Safari browsers
                },
            },
        },
    },
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
    firstPlayVideoName: '',
    firstPlayVideoSource: '',
    firstPlayVideoSourceType: '',
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
    isFullScreen: false,
})

export const useVideoPlayerStore = defineStore('videoPlayerStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
        },

        async getMistServerUri() {
            try {
                let response = await axios.get('/mist-server/uri')
                this.mistServerUri = response.data
            } catch (error) {
                console.error(error)
            }
        },

        // Initialize or update the player instance
        setPlayer(playerInstance) {
            this.player = playerInstance
            this.initializePlayer().then(() => {
                // Use player.ready() to ensure the player is fully initialized
                this.player.ready(() => {
                    this.attachEventListeners() // Attach event listeners after the player is fully ready
                })
            }).catch(error => {
                console.error('Error during video player initialization:', error)
            })
        },

        setMistServerUri(mistServerUri) {
            this.mistServerUri = mistServerUri
        },

        // Initialize the player with necessary settings and start playback
        async initializePlayer() {
            if (!this.player) {
                console.error('Video.js player is not initialized.')
                return
            }

            try {
                await nextTick() // Ensure Vue's DOM updates are processed

                // Perform any necessary audio context and node setup
                const audioStore = useAudioStore()
                await audioStore.ensureAudioContextAndNodesReady(this.player)

                // Apply initial player settings
                this.player.controls(false)
                this.player.muted(this.muted)


                // Await the player to be ready
                await new Promise(resolve => this.player.ready(resolve))

                // Attempt to start playback
                await this.player.play()
                console.log('Playback started successfully')

            } catch (error) {
                console.error('Error during player initialization or playback:', error)
                // Handle the error (e.g., showing a user-friendly message)
            }

        },
        // Attach event listeners to the player
        attachEventListeners() {
            const nowPlayingStore = useNowPlayingStore()
            if (!this.player || this.eventListenersAttached) {
                console.log('Event listeners are already attached or video player is not initialized.')
                return
            }

            this.player.on('timeupdate', this.handleTimeUpdate)
            this.player.on('play', this.handlePlay)
            this.player.on('pause', this.handlePause)
            this.player.on('error', this.handleError)

            // this.player.on('fullscreenchange', () => {
            //     if (this.player.isFullscreen()) {
            //         this.enterFullscreen()
            //     } else {
            //         this.exitFullscreen()
            //         if (!this.player.paused()) {
            //             this.player.play() // Resume playback
            //         }
            //     }
            // })

            // Ensure keydown listener is managed by the fullscreen change handler
            // this.handleFullscreenChange();  // Call to ensure initial setup

            // TODO: This .on ended will be useful for something.
            //  I (tec21) was using it to loop the FirstPlay Test video.
            //  But, that causes problems with playback on Recording files
            //  that are short or won't play because of bad file path.
            this.player.on('ended', () => {
                const channelStore = useChannelStore()
                const userStore = useUserStore()
                console.log('Video has ended. Evaluating next steps based on the current channel...')
                if (channelStore.currentChannel === 'firstPlay') {
                    console.log('Reloading firstPlay video...')
                    userStore.videoSettings.firstPlay = true
                    const source = {
                        name: this.firstPlayVideoName,
                        source: this.firstPlayVideoSource,
                        type: this.firstPlayVideoSourceType,
                        mediaType: 'firstPlay', // Hardcoded as this is specifically for firstPlay
                    }
                    nowPlayingStore.reset()
                    nowPlayingStore.activeMedia.details.primaryName = source.name
                    this.loadNewVideo(source)
                } else if (channelStore.currentChannel && channelStore.currentChannel.id) {
                    console.log('Changing to the next video in the current channel...')
                    channelStore.changeChannel(channelStore.currentChannel)
                } else {
                    console.log('No specific channel or firstPlay detected, handling default scenario...')
                    // Optionally handle a default case
                    const defaultSource = {
                        videoSrc: this.firstPlayVideoSource,
                        videoType: this.firstPlayVideoSourceType,
                        mediaType: 'firstPlay',
                    }
                    this.loadNewVideo(defaultSource)
                }
                // this.refreshVideoPlayer();

            })
            // console.log('video source: ' + this.videoSource)
            this.eventListenersAttached = true
            console.log('Event listeners attached.')
        },

        refreshVideoPlayer() {
            let videoJs = videojs('main-player')
            const currentSource = this.videoSource
            const currentSourceType = this.videoSourceType

            // Reset the player source to force reload
            videoJs.src({
                type: currentSourceType,
                src: currentSource,
            })
            videoJs.ready(() => {
                videoJs.play().catch(error => {
                    useNotificationStore().setGeneralServiceNotification('Error', 'Code: 456A. Playback initiation error: ' + error)
                    console.error('Code: 456A. Playback initiation error: ', error)
                })
                console.log('Player refreshed...')
            })
        },

        // Detach event listeners from the player
        detachEventListeners() {
            if (!this.player || !this.eventListenersAttached) {
                console.log('Event listeners are already detached or video player is not initialized.')
                return
            }

            useAudioStore().stopAudioLevelMonitoring()
            this.player.off('timeupdate', this.handleTimeUpdate)
            this.player.off('fullscreenchange', this.handleFullscreenChange)
            this.player.off('play', this.handlePlay)
            this.player.off('pause', this.handlePause)
            // TODO: If you use the .on 'ended', remember
            //  to detach it here:
            this.player.off('ended', this.handleEnd)
            this.player.off('error', this.handleError)

            this.eventListenersAttached = false
            console.log('Event listeners detached.')
        },

        // Dispose of the player and perform cleanup
        disposePlayer() {
            if (!this.player) {
                console.error('Video player is not initialized.')
                return
            }

            this.detachEventListeners() // Detach event listeners if attached
            this.player.dispose() // Dispose of the player instance
            this.player = null // Reset the player state

            // Optionally, stop audio level monitoring if linked to the player lifecycle
            // const audioStore = useAudioStore();
            // audioStore.stopAudioLevelMonitoring();

            console.log('Video player disposed and cleaned up.')
        },

        // New method to prepare for a new video source
        prepareForNewVideoSource(source) {
            console.log('Preparing for new video source')

            // Example: Clear any existing channel or video-specific state
            useChannelStore().clearChannel()

            // Reset or cleanup any existing video playback, if necessary
            // this.resetPlayback();

            // Load and play the new video source
            this.loadNewVideo(source)
        },

        resetPlayback() {
            if (this.player) {
                this.player.pause()
                this.player.muted(true)
                this.player.currentTime(0) // Optionally reset the time
                // Further cleanup logic here, if necessary
                this.detachEventListeners() // Detach event listeners if attached
            }
        },

        // Event handlers
        handleTimeUpdate() {
            // console.log('Handling timeupdate...');
            // Implement your logic
            this.currentTime = this.player.currentTime()
            this.duration = this.player.duration()
            const progressPercentage = (this.currentTime / this.duration) * 100
            this.formattedTime = `${this.formatDuration(this.currentTime)} / ${this.formatDuration(this.duration)}`

            // Update UI elements or emit events as needed
            // Note: Direct manipulation of the DOM or component refs from the store is not recommended

        },
        // for the handleTimeUpdate eventHandler
        formatDuration(durationInSeconds) {
            const hours = Math.floor(durationInSeconds / 3600)
            const minutes = Math.floor((durationInSeconds % 3600) / 60)
            const seconds = Math.floor(durationInSeconds % 60)

            const parts = [hours, minutes, seconds].map(part => part.toString().padStart(2, '0'))
            return parts.join(':')
        },
        enterFullscreen() {
            this.isFullscreen = true
        },
        exitFullscreen() {
            this.isFullscreen = false
        },
        // handleFullscreenChange() {
        //     console.log('Handling fullscreenchange...')
        //     // Implement your logic
        //     this.player?.on('fullscreenchange', () => {
        //         if (this.player.isFullscreen()) {
        //             // Video is entering fullscreen mode
        //             console.log('Entering fullscreen mode.');
        //             this.enterFullscreen()
        //         } else {
        //             // Video is exiting fullscreen mode
        //             console.log('Exiting fullscreen mode.');
        //             this.exitFullscreen()
        //             // Check if the video was playing before entering fullscreen
        //             if (!this.player.paused()) {
        //                 // Resume playback after exiting fullscreen
        //                 this.player.play()
        //             }
        //         }
        //     })
        // },
        handlePlay() {
            // console.log('Handling play...')
            // Implement your logic
            this.player?.on('play', () => {
                this.paused = false
            })
        },
        handlePause() {
            // console.log('Handling pause...')
            // Implement your logic
            this.player?.on('pause', () => {
                this.paused = true
            })
        },
        handleEnd() {
            this.player.on('ended', () => {
                console.log('Video has ended. Refreshing the player...')
                this.refreshVideoPlayer()
            })
        },
        handleError() {
            console.log('Handling error...')
            // Implement your logic
            this.player?.on('error', () => {
                const error = this.player.error()
                console.error('Video.js Error:', error.code, error.message)
                if (error && error.code === 4) { // MEDIA_ERR_SRC_NOT_SUPPORTED
                    console.log('Refreshing due to source error...')
                    this.refreshVideoPlayer()
                }
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
        updateFirstPlay(newFirstPlay) {
            this.firstPlayVideoName = newFirstPlay.name
            this.firstPlayVideoSource = newFirstPlay.source
            this.firstPlayVideoSourceType = newFirstPlay.type
        },
        // Toggle mute state
        toggleMute() {
            if (this.muted) {
                this.unMute()
            } else {
                this.mute()
            }
        },
        // Mute the video
        mute() {
            if (this.player) {
                this.player.muted(true)
                this.muted = true
            }
        },
        // Unmute the video
        unMute() {
            const audioStore = useAudioStore()

            if (this.player) {
                // Prepare audio setup for when it's unmuted
                audioStore.userInteractionForAudio()

                // Optionally, if fadeInAudioFromMuted is a gradual process,
                // ensure this.player.muted(false) is called within that function.
                audioStore.fadeInAudioFromMuted()

                this.muted = false
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
            const userStore = useUserStore() // Make sure you import useUserStore at the top
            console.log(source)
            let videoSrc, videoSourceType

            // Default to 'video/mp4' if type is not specified or is empty
            videoSourceType = source.type || 'video/mp4'

            // Early return if the mediaType is 'firstPlay' and the user setting for firstPlay is false

            if (source.mediaType === 'firstPlay' && userStore.videoSettings?.firstPlay === false) {
                // TODO: We should stop listening to the firstPlayVideo channel instead or as well.
                console.log('Skipping first play video based on user settings')
                return null  // Signal to not proceed in the parent function
            } else if (source.mediaType === 'firstPlay') {
                videoSrc = source.source
                videoSourceType = source.type
                return {videoSrc, videoSourceType}
            } else if (source.mediaType === 'externalVideo') {
                // For external videos, use the URL as provided without encoding
                // videoSrc = source.video_url
                // console.log('Using external video source:', videoSrc)
            } else if (source.mediaType === 'recording') {
                console.log('Video is a recording.')
                videoSrc = this.mistServerUri + source.recording.source
                videoSourceType = source.recording.sourceType
                console.log(videoSrc)
                console.log(videoSourceType)
            } else if (source.mediaType === 'mistStream') {
                videoSrc = source.source
                videoSourceType = source.type
            } else {
                // console.log('CDN Endpoint:', source.cdn_endpoint)
                // console.log('Cloud Folder:', source.cloud_folder)
                // console.log('Cloud Private Folder:', source.cloud_private_folder)
                // console.log('Folder:', source.folder)
                // console.log('File Name:', source.file_name)
                // For internal videos, construct the URL from its components
                // Here, we assume the cdn_endpoint, cloud_folder, and folder are correctly formatted
                // and do not require encoding. Only the file_name might need encoding.
                // Determine the basePath using cloud_folder or cloud_private_folder
                const basePath = `${source.cdn_endpoint}${source.cloud_folder ? source.cloud_folder : source.cloud_private_folder}${source.folder}/`
                // const encodedFileName = encodeURIComponent(source.file_name);
                const fileName = source.file_name
                videoSrc = basePath + fileName
                // console.log('Constructed internal video source:', videoSrc)
                // If your server or CDN is configured to handle spaces in URLs without %20 encoding
                // or if the original working URLs did not use standard URL encoding,
                // you might adjust the encoding strategy here.
                // For example, to replace spaces with %20 but leave other characters as-is:
                // const fileNameForUrl = source.file_name.replace(/ /g, '%20');
                // videoSrc = basePath + fileNameForUrl;
            }

            // console.log(`Final Constructed Video Source: ${videoSrc}, Type: ${videoSourceType}`)
            userStore.setFirstPlayFalse()
            return {videoSrc, videoSourceType}
        },


        loadNewVideo(source) {
            try {
                let videoJs = videojs('main-player')
                console.log('LOAD NEW VIDEO')
                const audioStore = useAudioStore()
                const userStore = useUserStore()

                // Ensure videoSettings is initialized
                if (!userStore.videoSettings) {
                    userStore.videoSettings = {}
                }

                // First check if the source details are valid
                const sourceDetails = this.getSourceDetails(source)
                if (!sourceDetails) {
                    console.log('Exiting loadNewVideo due to getSourceDetails returning null.')
                    return // Exit the function silently
                }

                // Now that we know sourceDetails is not null, destructure it
                const {videoSrc, videoSourceType} = sourceDetails

                // Proceed only if videoSrc and videoSourceType are valid
                if (!videoSrc || !videoSourceType) {
                    // console.log('Exiting loadNewVideo due to no video source or type.');
                    return // Exit the function silently
                }

                // Example: Stopping and cleaning up the current video and audio setup
                if (videoJs) {
                    // Pause the current video if it's playing
                    if (!videoJs.paused()) {
                        videoJs.pause()
                    }

                    // Clear the current source
                    videoJs.src('')

                    // Set up the new source
                    videoJs.src({'src': videoSrc, 'type': videoSourceType})

                    videoJs.ready(() => {
                        // ensureAudioContextAndNodesReady does the following:
                        // 1. Resumes AudioContext if suspended.
                        // 2. (Re)connects MediaElementSource from the video element to AudioContext.
                        audioStore.deferAudioSetup = false
                        audioStore.ensureAudioContextAndNodesReady(videoJs).then(() => {
                            // Only attempt to play the video after ensuring the AudioContext is ready
                            videoJs.play().catch(error => {
                                useNotificationStore().setGeneralServiceNotification('Error', 'Code: 123A. Playback initiation error: ' + error)
                                console.error('Code 123A. Playback initiation error: ', error)

                                // Ensure videoSettings is initialized
                                if (!userStore.videoSettings) {
                                    userStore.videoSettings = {}
                                }

                                // if an
                                if (!userStore.isSubscriber || !userStore.isVip) {
                                    userStore.videoSettings.firstPlay = true
                                }
                            })

                            // Consider toggling mute based on the user's preference or previous state
                            // videoJs.muted(false)
                            // this.muted = false
                        })
                    })
                }
            } catch (error) {
                // Log the error or perform any other error handling
                useNotificationStore().setGeneralServiceNotification('Error', 'Code: 789A. Error loading new video: ' + error)
                console.error('Code: 789A. Error loading new video: ', error)
            }
        },


        // loadNewVideo(source) {
        //     console.log('LOAD NEW VIDEO TTTTTTTTTTTTTTTTTTTTTTTTTS');
        //     console.log('LOAD NEW VIDEO initiated with source:', source);
        //     const audioStore = useAudioStore();
        //     this.unMute();
        //     // Destructure the returned object to get videoSrc and videoSourceType
        //     const { videoSrc, videoSourceType } = this.getSourceDetails(source);
        //     console.log(`Video source details received: Src - ${videoSrc}, Type - ${videoSourceType}`);
        //
        //     // Ensure you're using the same player instance (`videoJs` in this context) for consistency
        //     let videoJs = videojs('main-player'); // Ensure this ID matches your video player element
        //     console.log('Video.js player instance created or retrieved.');
        //
        //     // Stop and clean up the current video and audio setup if necessary
        //     videoJs.ready(() => {
        //         console.log('Video.js player is ready.');
        //         videoJs.src({ 'src': videoSrc, 'type': videoSourceType });
        //         console.log(`Video source set: ${videoSrc} of type ${videoSourceType}`);
        //
        //         // Ensure AudioContext and nodes are ready
        //         audioStore.ensureAudioContextAndNodesReady(videoJs).then(() => {
        //             console.log('Audio context and nodes are ready. Attempting to play video.');
        //
        //             videoJs.play().then(() => {
        //                 console.log('Video playback started successfully.');
        //             }).catch(error => {
        //                 console.error('Playback initiation error:', error);
        //             });
        //             // Attempt to play the video after ensuring the AudioContext is ready
        //             // videoJs.play().catch(error => {
        //             //     console.error('Playback initiation error:', error);
        //             // });
        //
        //             // Apply the unMute logic from loadMistStreamVideo to ensure audio is correctly configured
        //             this.unMute();
        //             console.log('Audio unmuted.');
        //             this.paused = false;
        //             console.log('Paused flag set to false.');
        //         }).catch(error => {
        //             console.error('Error ensuring audio context and nodes are ready:', error);
        //         });
        //     });
        // },

        playNewVideo() {
            console.log('PLAY NEW VIDEO')
            if (this.player) {
                // Wait for the video to be ready before playing
                this.player.ready(() => {
                    this.player.play()
                    // Assuming you want to unmute here; check if this aligns with user interaction policies
                    this.player.muted(false)
                    this.muted = false
                })
            }
        },


// load video from different types of sources:
// Url
// YouTube
// EmbedCode
// Mist
// File

        // The YouTube loader requires the video-js YouTube plugin which is not currently installed.
        loadNewSourceFromYouTube(source) {
            this.videoIsYoutube = true
            useChannelStore().clearChannel()
            let videoJs = videojs('main-player')
            this.videoSource = source
            this.videoSourceType = 'video/youtube'
            videoJs.src({'src': this.videoSource, 'type': this.videoSourceType})
            videoJs.controls(false)
            this.unMute()
            this.paused = false
            useUserStore().setFirstPlayFalse()
        },
        loadNewLiveSourceFromRumble(source) {
            this.videoIsYoutube = true
            useChannelStore().clearChannel()
            let videoJs = videojs('main-player')
            this.videoSource = source
            this.videoSourceType = 'application/x-mpegURL'
            videoJs.src({'src': this.videoSource, 'type': this.videoSourceType})
            videoJs.controls(false)
            this.unMute()
            this.paused = false
            useUserStore().setFirstPlayFalse()
        },
        loadNewSourceFromUrl(source) {
            try {
                this.videoIsYoutube = false
                useChannelStore().clearChannel()
                let videoJs = videojs('main-player')

                if (!source.video_url || !source.type) {
                    useNotificationStore().setGeneralServiceNotification('Error', 'Invalid video source.')
                    throw new Error('Invalid video source.')
                }

                this.videoSource = source.video_url
                this.videoSourceType = source.type
                videoJs.src({'src': source.video_url, 'type': source.type})

                this.unMute()
                this.paused = false
                useUserStore().setFirstPlayFalse()
            } catch (error) {
                useNotificationStore().setGeneralServiceNotification('Error', 'Failed to load new source: ' + error)
                console.error('Failed to load new source:', error)
                throw error // Re-throw the error to be caught by the caller
            }
        },
        loadNewSourceFromMist(source) {
            this.videoIsYoutube = false
            let videoJs = videojs('main-player')
            let filePath = 'https://mist.not.tv/hls/'
            this.videoSource = filePath + source + '/index.m3u8'
            this.videoSourceType = 'application/x-mpegURL'
            videoJs.src({'src': this.videoSource, 'type': this.videoSourceType})
            this.unMute()
            this.paused = false
            useUserStore().setFirstPlayFalse()
        },
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
            useUserStore().setFirstPlayFalse()
        },

        // The new load video functions (2024-02-09 tec21 and ChatGPT)
        loadExternalSourceVideo() {

        },
        loadPlaylistVideos() {

        },
        async loadMistStreamVideo(mistStream) {
            try {
                const videoJs = videojs('main-player');
                const audioStore = useAudioStore();
                const userStore = useUserStore();
                const notificationStore = useNotificationStore();

                // Ensure mistServerUri is set
                if (!this.mistServerUri) {
                    console.log('Mist Server URI not set, fetching...');
                    await this.getMistServerUri();
                }

                if (this.mistServerUri) {
                    console.log('Mist Server URI:', this.mistServerUri); // Log the URI to confirm it's fetched

                    // Construct the video source URL
                    const basePath = this.mistServerUri;
                    this.videoSource = `${basePath}hls/${mistStream}/index.m3u8`;
                    console.log('Video Source Set To:', this.videoSource); // Log the final video source

                    this.videoSourceType = 'application/vnd.apple.mpegURL';

                    // Pause the current video if it's playing
                    if (!videoJs.paused()) {
                        videoJs.pause();
                    }

                    // Clear the current source
                    videoJs.src('');

                    // Set the video source in video.js
                    videoJs.src({src: this.videoSource, type: this.videoSourceType});
                    this.unMute();
                    this.paused = false;

                    videoJs.ready(() => {
                        console.log('Video.js is ready');

                        // Ensure the audio context and nodes are ready
                        audioStore.deferAudioSetup = false;
                        audioStore.ensureAudioContextAndNodesReady(videoJs).then(() => {
                            // Attempt to play the video after ensuring the AudioContext is ready
                            videoJs.play().catch(error => {
                                notificationStore.setGeneralServiceNotification('Error', 'Code: 123B. Playback initiation error: ' + error);
                                console.error('Code 123B. Playback initiation error: ', error);

                                // Ensure videoSettings is initialized
                                if (!userStore.videoSettings) {
                                    userStore.videoSettings = {};
                                }

                                // If the user is not a subscriber or VIP, set firstPlay to true
                                if (!userStore.isSubscriber || !userStore.isVip) {
                                    userStore.videoSettings.firstPlay = true;
                                }
                            });

                            // Consider toggling mute based on the user's preference or previous state
                            // videoJs.muted(false);
                            // this.muted = false;
                        }).catch(error => {
                            notificationStore.setGeneralServiceNotification('Error', 'Code: 456B. Audio setup error: ' + error);
                            console.error('Code 456B. Audio setup error: ', error);
                        });
                    });
                } else {
                    console.error('Mist Server URI is still not set after fetching.');
                }
            } catch (error) {
                // Log the error or perform any other error handling
                const notificationStore = useNotificationStore();
                notificationStore.setGeneralServiceNotification('Error', 'Code: 789B. Error loading new video: ' + error);
                console.error('Code: 789B. Error loading new video: ', error);
            }
        },

        async loadVideoFromFile(video) {
            // console.log('Loading Mist Stream Video for:', mistStream.name); // Log which Mist Stream is being loaded
            // This mistServerUri comes from appSettings
            // if (!this.mistServerUri) {
            //     console.log('Mist Server URI not set, fetching...') // Log fetching attempt
            //     await this.getMistServerUri()
            // }
            // if (this.mistServerUri) {
            // console.log('Mist Server URI:', this.mistServerUri); // Log the URI to confirm it's fetched
            // let basePath = this.mistServerUri
            // this.videoSource = basePath + 'hls/' + video.name + '/index.m3u8'
            // console.log('Video Source Set To:', this.videoSource); // Log the final video source

            // this.videoSourceType = 'video/mp4'
            console.log('Video Source Type:', video.type)
            console.log('Video URL:', video.video_url)
            console.log('CDN Endpoint:', video.cdn_endpoint)
            console.log('Cloud Folder:', video.cloud_folder)
            console.log('Folder:', video.folder)
            console.log('File Name:', video.file_name)

            let videoSource = video.cdn_endpoint + video.cloud_folder + video.folder + '/' + video.file_name
            // console.log('Setting player source to:', this.videoSource, 'of type:', this.videoSourceType); // Log the source setting
            let videoJs = videojs('main-player')
            videoJs.src({'src': videoSource, 'type': video.type})
            this.unMute()
            this.paused = false
            // } else {
            //     console.error('Mist Server URI is still not set after fetching.')
            // }
            // let basePath = this.mistServerUri
            // this.videoSource = basePath + 'hls/' + mistStream.name + '/index.m3u8'
            // this.videoSourceType = "application/vnd.apple.mpegURL"
            // this.player.src({'src': this.videoSource, 'type': this.videoSourceType})
            // this.unMute()
            // this.paused = false
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
        clickOnVideoAction() {
            const appSettingStore = useAppSettingStore()
            const userStore = useUserStore()
            if (appSettingStore.currentPage === 'stream') {
                if (userStore.isMobile) {
                    this.controls = !this.controls
                } else {
                    // videoPlayerStore.togglePlay()
                    appSettingStore.toggleOsd()
                    if (appSettingStore.ott === 2 || appSettingStore.ott === 3 || appSettingStore.ott === 5) {
                        appSettingStore.closeOtt()
                    }
                }
            } else if (!appSettingStore.pipChatMode) {
                router.visit('/stream')
            }
        },


    },

    getters:
        {
            isLocalhost: (state) => {
                return state.mistServerUri ? state.mistServerUri.includes('localhost') : false;
            },
        }
    ,
})
