import { defineStore } from 'pinia'
import videojs from 'video.js'

const initialState = () => ({
    deferAudioSetup: true, // Flag to defer audio setup on initial load
    audioSetupDone: false, // Flag to track if audio setup has been completed
    showVolumeIndicator: true,
    audioSetupCompleted: false,
    audioAnimationFrameId: null,
    audioLevel: 0, // Initial audio level
    volume: 100, // Initial volume level
})

export const useAudioStore = defineStore('audioStore', {
    state: initialState,
    actions: {
        reset() {
            // Reset the store to its original state (clear all data)
            Object.assign(this, initialState())
        },

        // Method to call when user interaction requires audio
        userInteractionForAudio() {
            const videoJs = videojs('main-player')
            // this.audioSetupRequired = true;
            // Trigger audio setup if the player is already initialized

            if (window.audioContext.state === 'suspended') {
                window.audioContext.resume().then(() => {
                    console.log('AudioContext successfully resumed')
                    this.deferAudioSetup = false
                    // Further configuration if necessary
                    if (videoJs && !this.audioSetupDone) {
                        console.log('Check audio nodes.')
                        // Ensure that ensureAudioContextAndNodesReady is properly awaited
                        this.ensureAudioContextAndNodesReady(videoJs).then(() => {
                            console.log('Audio nodes ready after user interaction.');
                            // Any further actions that depend on audio setup completion
                        }).catch(error => {
                            console.error('Error setting up audio nodes:', error);
                        });
                    }
                }).catch(error => {
                    console.error('Error resuming AudioContext:', error)
                })
            } else {
                console.log('AudioContext already running')
            }
            // Other audio setup logic here
        },

////////////////////////////////// AUDIO DYNAMICS SETUP
///////////////////////////////////////////////////////

        // tec21: 2024-02-02 this is good... keep this here. It is loaded when the videoPlayer is Ready
        // currently we are only using the VideoPlayerMain... so check VideoPlayerMain to see the starting
        // of this process. And note, audioContext is available globally as we set it up in our app.js.

        // Since a MediaElementSourceNode can only be created once per media element, and we're managing
        // dynamic video source changes, ensure that this node is not being recreated unnecessarily.
        // This could disrupt the audio processing chain.

        // When initializing or changing the video source, call a central function from VideoPlayerStore
        // to set up or reconfigure the audio context and its nodes: this.resumeAudioContextIfNeeded()

        // Assuming this setup is done once when the player is initially ready
        // initialAudioSetup() {
        //     if (!this.audioSetupCompleted) {
        //         if (!window.audioContext) {
        //             window.audioContext = new (window.AudioContext || window.webkitAudioContext)()
        //         }
        //
        //         const videoJs = videojs('main-player')
        //         // const videoElement = document.querySelector('video#main-player'); // Adjust selector as needed
        //         if (!window.sourceNode) {
        //             window.sourceNode = window.audioContext.createMediaElementSource(videoJs)
        //             // Connect to destination or further processing nodes here
        //             window.sourceNode.connect(window.audioContext.destination)
        //         }
        //         // Additional audio processing setup...
        //     }
        //     // Mark setup as completed to prevent re-initialization
        //     this.audioSetupCompleted = true;
        // },

        ensureAudioContextAndNodesReady(videoPlayer) {
            return new Promise((resolve, reject) => {
                // Wait for the player to be ready to ensure tech is available
                videoPlayer.ready(() => {
                    if (this.deferAudioSetup) {
                        console.log('Audio setup deferred until required.');
                        resolve(); // Resolve the promise since no immediate action is taken
                    } else {
                        this.audioLevel = 0; // Reset or initialize the audio signal indicator
                        const videoElement = videoPlayer.tech().el();
                        console.log('Ensuring AudioContext and nodes are ready for video element', videoElement);

                        if (window.audioContext.state === 'suspended') {
                            window.audioContext.resume().then(() => {
                                console.log('AudioContext resumed successfully');
                                return this.configureAudioProcessingChain(videoElement); // Ensure this method returns a promise
                            }).then(() => {
                                this.audioSetupDone = true; // Mark audio setup as completed after successful configuration
                                console.log('Audio setup marked complete.');
                                resolve(); // Resolve after all async operations are complete
                            }).catch(error => {
                                console.error('Error in audio setup:', error);
                                reject(error);
                            });
                        } else {
                            console.log('AudioContext is not suspended, checking if configuration is necessary.');
                            this.configureAudioProcessingChainIfNecessary(videoElement) // Ensure this method returns a promise
                                .then(() => {
                                    this.audioSetupDone = true; // Mark audio setup as completed after successful configuration
                                    console.log('Audio setup marked complete.');
                                    resolve(); // Resolve after all async operations are complete
                                }).catch(reject);
                        }
                    }
                });
            });
        },


        configureAudioProcessingChainIfNecessary(videoElement) {
            return new Promise((resolve, reject) => {
                if (!videoElement.__sourceConnected) {
                    console.log('Configuring audio processing chain...');
                    // Execute the configuration logic
                    this.configureAudioProcessingChain(videoElement)
                        .then(() => {
                            videoElement.__sourceConnected = true; // Mark as connected
                            console.log('Audio processing chain configured and connected.');
                            resolve(); // Resolve the promise upon successful configuration
                        })
                        .catch(error => {
                            console.error('Error configuring audio processing chain:', error);
                            reject(error); // Reject the promise if there's an error
                        });
                } else {
                    console.log('Audio processing chain already configured and connected.');
                    resolve(); // Resolve immediately if no configuration is needed
                }
            });
        },


        configureAudioProcessingChain(videoElement) {
            return new Promise((resolve, reject) => {
                console.log('Setting up audio nodes for:', videoElement)
                // Check and create MediaElementSourceNode, GainNode, etc., as discussed previously
                // Ensure connections between nodes are correctly set up
                // This includes checking if nodes already exist to avoid re-initialization

                // Ensure the AudioContext is globally initialized
                if (!window.audioContext) {
                    window.audioContext = new (window.AudioContext || window.webkitAudioContext)()
                }


                // Create or ensure MediaElementSourceNode is configured for the video element
                // if (!videoElement.__mediaElementSourceConnected) {
                //     videoElement.__mediaElementSource = window.audioContext.createMediaElementSource(videoElement);
                //     videoElement.__mediaElementSourceConnected = true; // Mark as connected
                // }

                // Create or reuse the MediaElementSourceNode for the video element
                if (!videoElement.__sourceNode) {
                    console.log('Creating or reconnecting MediaElementSourceNode...')
                    videoElement.__sourceNode = window.audioContext.createMediaElementSource(videoElement)
                    videoElement.__sourceNodeNeedsReconnect = false // Reset flag after reconnection
                    console.log('MediaElementSourceNode created or reconnected.')
                }

                // Setup or ensure gain node is configured
                if (!window.gainNode) {
                    // console.log('Connecting MediaElementSourceNode to gainNode...')
                    window.gainNode = window.audioContext.createGain()
                    // console.log('gainNode connected.')
                    // Additional gain node configuration here, e.g., setting initial gain value
                    window.gainNode.gain.setValueAtTime(1, window.audioContext.currentTime)
                    // console.log('gainNode value set.')
                }

                // Setup or ensure DynamicsCompressorNode is configured with broadcast settings
                if (!window.compressorNode) {
                    // console.log('Connecting gainNode to compressorNode...')
                    window.compressorNode = window.audioContext.createDynamicsCompressor()
                    // console.log('compressorNode connected.')
                    // Apply broadcast-optimized settings
                    window.compressorNode.threshold.setValueAtTime(-50, window.audioContext.currentTime)
                    window.compressorNode.knee.setValueAtTime(40, window.audioContext.currentTime)
                    window.compressorNode.ratio.setValueAtTime(12, window.audioContext.currentTime)
                    window.compressorNode.attack.setValueAtTime(0.003, window.audioContext.currentTime)
                    window.compressorNode.release.setValueAtTime(0.25, window.audioContext.currentTime)
                    // console.log('compressor settings applied.')
                }

                // Setup or ensure AnalyserNode is configured
                if (!window.analyserNode) {
                    window.analyserNode = window.audioContext.createAnalyser()
                    // console.log('Analyser node configured.')
                    window.analyserNode.fftSize = 256 // Example size, adjust based on needs
                    // Further configuration for the analyserNode can be added here
                    // Start the audio level monitoring process
                    // this.updateAudioLevel(window.analyserNode);
                    // this.startAudioLevelMonitoring(window.analyserNode)
                    // Start the audio level monitoring process

                } else {
                    console.log('Audio nodes already set up and connected.')
                }

                // Connect the audio nodes in the correct order
                // This part is typically synchronous
                // console.log('Connecting sourceNode to gainNode.')
                videoElement.__sourceNode.connect(window.gainNode) // Source to Gain
                // console.log('Connecting gainNode to compressorNode.')
                window.gainNode.connect(window.compressorNode) // Gain to Compressor
                // console.log('Connecting compressorNode to analyserNode.')
                window.compressorNode.connect(window.analyserNode) // Analyze post-compression
                // console.log('Connecting analyserNode to AudioContext destination.')
                window.analyserNode.connect(window.audioContext.destination) // Output the analyzed audio
                // console.log('Audio output connected to destination.')

                this.startAudioLevelMonitoring(window.analyserNode) // Use the global analyserNode

                // Optionally adjust gain based on analysis or other criteria
                // This could be dynamic based on your application's needs
                // console.log('Adjust gain based on analysis.')
                this.adjustGainBasedOnAnalysis(window.gainNode)

                // If there's any asynchronous operation involved in the setup, handle it here
                // For example, fetching audio processing configurations from a server

                // Once all setups (including any asynchronous ones) are complete, resolve the promise
                resolve()
            });
        },

        adjustGainBasedOnAnalysis(gainNode) {
            // Example function to dynamically adjust gain - adjust as necessary
            // This can be called here directly or in response to specific application events
            gainNode.gain.setValueAtTime(1.5, window.audioContext.currentTime) // Example adjustment
            // console.log('Gain adjusted.')
        },

        setAudioLevel(level) {
            this.audioLevel = level
        },


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


        // resumeAudioContextIfNeeded() {
        //     // Assuming `audioContext` is accessible globally or injected into the store
        //     if (window.audioContext && window.audioContext.state === 'suspended') {
        //         window.audioContext.resume().then(() => {
        //             console.log('AudioContext resumed successfully');
        //             // Any additional actions after resuming can go here
        //         }).catch(error => {
        //             console.error('Error resuming AudioContext:', error);
        //         });
        //     }
        // },


        // this.resumeAudioContextIfNeeded()
        // Update the video source
        // if (window.audioContext.state === 'suspended') {
        //     window.audioContext.resume().then(() => {
        //         console.log('AudioContext resumed successfully');
        //         // Now proceed to load the video and play
        //         videoJs.src({src: videoSrc, type: videoSourceType})
        //     }).catch(error => {
        //         console.error('Error resuming AudioContext:', error);
        //     });
        // } else {
        //     // AudioContext is already running, so just load and play the video
        //     videoJs.src({src: videoSrc, type: videoSourceType})
        // }
        // // videoJs.src({src: videoSrc, type: videoSourceType})
        // videoJs.one('loadedmetadata', () => {
        //     // useAudioStore.ensureAudioContextAndNodesReady(videoJs);
        //    // this.toggleMute(); // Ensure video is not muted
        // });
        // useAudioStore.ensureAudioContextAndNodesReady(videoJs);

        // this.reconnectAudioContextIfNeeded(videoJs);

        // Attempt to resume and reconnect the audio context after the source has been set
        // videoJs.one('loadedmetadata', () => {
        //
        // });


        // setupAudioAnalyser(videoElement) {
        //     if (!window.audioContext) {
        //         console.error("AudioContext not initialized.");
        //         return;
        //     }
        //
        //     // Check if the sourceNode has already been created to avoid re-creation
        //     if (!videoElement.__sourceNode) {
        //         videoElement.__sourceNode = window.audioContext.createMediaElementSource(videoElement);
        //     }
        //
        //     // Create and configure AnalyserNode
        //     if (!videoElement.__analyserNode) {
        //         videoElement.__analyserNode = window.audioContext.createAnalyser();
        //         videoElement.__analyserNode.fftSize = 256; // Example size, adjust based on needs
        //     }
        //
        //     // Connect the nodes
        //     videoElement.__sourceNode.connect(videoElement.__analyserNode);
        //     videoElement.__analyserNode.connect(window.audioContext.destination);
        //
        //     // Start the audio level monitoring process
        //     this.updateAudioLevel(videoElement.__analyserNode);
        // },

        startAudioLevelMonitoring(analyserNode) {
            console.log('Audio level monitoring started.')
            const bufferLength = analyserNode.frequencyBinCount
            const dataArray = new Uint8Array(bufferLength)

            const updateAudioLevel = () => {
                analyserNode.getByteFrequencyData(dataArray)

                let sum = 0
                for (let i = 0; i < bufferLength; i++) {
                    sum += dataArray[i]
                }
                let average = sum / bufferLength

                // Update the audio level for display (scale as needed)
                this.audioLevel = average * 100 / 128 // Adjust as necessary

                this.audioAnimationFrameId = requestAnimationFrame(updateAudioLevel)
            }

            updateAudioLevel()
        },

        reconnectAudioContextIfNeeded(videoJs) {
            // This function shouldn't be needed as the MediaElementSourceNode can only be
            // created once per media element.

            // Assuming the video element and audio context setup are similar to previous steps
            const videoElement = videoJs.tech().el()
            // Check if the media element source needs to be reconnected
            if (!videoElement.__mediaElementSourceConnected) {
                const sourceNode = window.audioContext.createMediaElementSource(videoElement)
                // Reconnect source node to your audio nodes or directly to the destination
                sourceNode.connect(window.audioContext.destination)
                videoElement.__mediaElementSourceConnected = true // Flag to avoid duplicate connections
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

                console.log('Dynamic gain control setup complete')
            }
        },


        // startAudioLevelMonitoring() {
        //     const updateAudioLevel = () => {
        //         // Your existing logic...
        //         this.audioAnimationFrameId = requestAnimationFrame(updateAudioLevel);
        //     };
        //
        //     updateAudioLevel();
        // },
        stopAudioLevelMonitoring() {
            if (this.audioAnimationFrameId !== null) {
                cancelAnimationFrame(this.audioAnimationFrameId)
                this.audioAnimationFrameId = null // Reset the ID after stopping
            }
        },


        // tec21: this isn't setup yet. We can apply different gain and dynamics
        // settings to different channels... and creators can also choose settings
        // for their content that override the defaults... ** FUTURE FEATURE **
        // Example function to adjust gain dynamically
        adjustAudioForNewVideo(gainValue) {
            // Assuming gainNode is stored or accessible globally similar to audioContext
            const gainNode = window.gainNode // Ensure this is set up in your initial audio setup
            gainNode.gain.setValueAtTime(gainValue, window.audioContext.currentTime)
        },


/////////////////////////////// ADDITIONAL AUDIO FUNCTIONS
//////////////////////////////////////////////////////////

        ///////// FADE IN AUDIO FROM MUTED
        //////////////////////////////////

        fadeInAudioFromMuted() {
            const videoJs = videojs('main-player')

            // Ensure the video is not muted to allow volume changes to take effect
            videoJs.muted(false)
            this.muted = false // Update your application's state accordingly

            // Initialize volume at 0 for the fade-in effect
            let currentVolume = 0
            videoJs.volume(currentVolume)

            const maxVolume = 1 // Define the maximum volume as 100%
            const fadeStep = 0.05 // Define the increment step for the fade-in effect
            const fadeInterval = 100 // Define the time interval for each step in milliseconds

            const fadeAudioIn = setInterval(() => {
                if (currentVolume < maxVolume) {
                    currentVolume += fadeStep // Increment the volume
                    currentVolume = Math.min(currentVolume, maxVolume) // Ensure it does not exceed maxVolume
                    videoJs.volume(currentVolume) // Apply the incremented volume
                } else {
                    clearInterval(fadeAudioIn) // Stop the interval once the max volume is reached
                }
            }, fadeInterval)
        },

/////////////////////////////// END AUDIO FUNCTIONS AND SETUP
/////////////////////////////////////////////////////////////


    },

    getters: {},

})
