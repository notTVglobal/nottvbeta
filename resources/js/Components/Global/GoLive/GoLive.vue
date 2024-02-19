<template>
  <div class="mx-0 md:mx-4 mt-4 mb-2 px-2 md:px-6 py-1">
    <div
        class="text-sm font-semibold  text-white text-center w-full border-2 rounded uppercase px-0 md:px-6 py-1 "
        :class="[ goLiveStore.isLive ? 'bg-gray-600 border-gray-600 ' : 'bg-red-600 border-red-600' ]">
      Go Live Instructions
    </div>
    <div class="shadow overflow-hidden border-2 border-red-600 rounded p-2 md:p-6"
         :class="[ goLiveStore.isLive ? 'bg-gray-100' : 'bg-red-100' ]">


      <div class="flex flex-row flex-wrap-reverse w-full justify-between">
        <div>
          <div class="mb-2">
            <button @click="appSettingStore.btnRedirect('/training/go-live-using-zoom')"
                    class="btn bg-blue-500 hover:bg-blue-700 rounded-lg text-white">How To Stream From Zoom
            </button>

          </div>
          <div>RTMP full url: <span
              class="font-bold">{{ goLiveStore.rtmpUri }}live/{{
              goLiveStore.selectedShow.mist_stream_wildcard.name
            }}</span>
          </div>
          <div>RTMP url: <span class="font-bold">{{ goLiveStore.rtmpUri }}live/</span></div>
          <div>RTMP stream key: <span class="font-bold">{{ goLiveStore.selectedShow.mist_stream_wildcard.name }}</span>
          </div>
        </div>
        <div class="flex flex-col">
          <div class="">
            <button v-if="!goLiveStore.isRecording" @click="goLiveStore.startRecording"
                    class="btn text-white bg-green-500 hover:bg-green-700 uppercase"
            >Start Recording
            </button>
            <button v-else @click="goLiveStore.stopRecording" class="btn text-white bg-red-700 hover:bg-red-900 uppercase"
            >Stop Recording
            </button>
            <div v-if="!goLiveStore.isRecording" class="text-xs text-green-500 font-semibold tracking-wider">Premium Creator
              Service
            </div>
          </div>
          <div class="my-4">
            <button v-if="!goLiveStore.isLive" @click="goLiveStore.goLive"
                    class="btn text-white bg-green-500 hover:bg-green-700 uppercase"
            >Go Live Now
            </button>
            <button v-else @click="goLiveStore.stopLive" class="btn text-white bg-red-700 hover:bg-red-900 uppercase"
            >End Live
            </button>
            <div v-if="!goLiveStore.isLive" class="text-xs text-green-500 font-semibold tracking-wider">Premium Creator
              Service
            </div>
          </div>
          <div></div>
          <div>Live will begin in...</div>
          <div>{{ formattedCountdown }}</div>
        </div>
      </div>


      <div class="flex flex-col justify-center mt-3 h-fit">
        <div class="text-xs tracking-wider font-semibold mb-1 pl-1">VIDEO STREAM</div>

        <div class="flex flex-row flex-wrap">
          <div class="flex flex-col">
            <div class="px-10 h-fit w-fit">
              <button @click="reloadPlayer"
                      class="btn btn-xs w-full"
                      :class="liveOrRecordingGrayButtonClass"
              >Reload Player
              </button>
              <div v-if="goLiveStore.isLive || goLiveStore.isRecording" class="w-full bg-red-700 text-white text-center uppercase font-bold">
                <span v-if="goLiveStore.isLive">LIVE</span> <span v-if="goLiveStore.isLive && goLiveStore.isRecording"> + </span>
                <span v-if="goLiveStore.isRecording">RECORDING</span>
              </div>
              <video-js-aux :id="`aux-player`"
                            :source="videoSource"
                            :sourceType="videoSourceType"
                            class=""
                            :class="liveOrRecordingVideoBorderClass"/>


              <!--            <div v-if="playerTargetId" class="mistvideo" :id="playerTargetId">-->
              <!--              <noscript>-->
              <!--                <a :href="`http://mist.nottv.io:8080/${goLiveStore.selectedShow.mist_stream_wildcard.name}.html`" target="_blank">-->
              <!--                  Click here to play this video-->
              <!--                </a>-->
              <!--              </noscript>-->
              <!--            </div>-->

            </div>
            <div class="flex flex-row px-10">
              <div class="mt-2">
                <button v-if="!videoPlayerStore.muted"
                        class="btn"
                        :class="liveOrRecordingGrayButtonClass"
                        @click="videoPlayerStore.mute">Mute Main Player Audio
                </button>
                <button v-else
                        class="btn"
                        @click="videoPlayerStore.unMute"
                        :class="liveOrRecordingGrayButtonClass">Turn On Main Player Audio
                </button>
              </div>
              <div class="mt-2 ml-2">
                <button v-if="!videoAuxPlayerStore.muted"
                        class="btn"
                        :class="liveOrRecordingGrayButtonClass"
                        @click="videoAuxPlayerStore.mute">Mute Live Stream
                  Video
                </button>
                <button v-else
                        class="btn"
                        :class="liveOrRecordingGrayButtonClass"
                        @click="videoAuxPlayerStore.unMute">Turn On Live Stream Audio
                </button>
              </div>
            </div>
          </div>

          <div v-if="goLiveStore.streamInfo" class="w-fit" :key="goLiveStore.selectedShowId">
            <div>
              <h3>Stream Info</h3>
              <!--                <RecursivePropertyList :object="serverInfo" />-->

              <p><strong>Width:</strong> {{ goLiveStore.streamInfo?.width }}</p>
              <p><strong>Height:</strong> {{ goLiveStore.streamInfo?.height }}</p>

              <!-- Assuming you want to display tracks info specifically -->
              <div v-if="goLiveStore.streamInfo?.meta?.tracks">
                <h4 class="font-semibold">Tracks</h4>
                <ul>
                  <li v-for="(track, name) in goLiveStore.streamInfo.meta.tracks" :key="name">
                    &middot; {{ name }} - Codec: {{ track.codec }}, Rate: {{ track.rate }}
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div v-else>
            <div class="flex flex-col">
              <span class="mb-2">Loading stream data...</span>
              <span class="loading loading-spinner text-neutral"></span>
            </div>

          </div>
        </div>

      </div>


      <div class="mx-4 mt-4 mb-2 px-6 py-1 ">
        <div
            class="text-sm font-semibold bg-blue-600 text-white text-center w-full border-2 border-blue-600 rounded uppercase px-6 py-1 ">
          Push Destinations
        </div>
        <div class="shadow bg-blue-100 overflow-hidden border-2 border-blue-600 rounded p-6 space-y-3">
          <div></div>
          <div>Set up <span class="font-bold">push destinations:</span></div>
          <div>Here you can set additional streaming destinations such as Facebook, YouTube, Rumble, Twitch, etc. and
            notTV will automatically start pushing to those destinations when you go live.
          </div>
          <div class="w-full flex justify-center pt-4">
            <button onclick="setPushDestinationsNotice.showModal()"
                    class="bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-lg px-4 py-2">Set Push
              Destinations
            </button>
          </div>
        </div>

      </div>

    </div>

  </div>

  <div class="mx-4 mt-4 mb-8 px-6 py-1 ">
    <div
        class="text-sm font-semibold bg-orange-600 text-white text-center w-full border-2 border-orange-600 rounded uppercase px-6 py-1 ">
      Commercial Breaks
    </div>
    <div class="shadow bg-orange-100 overflow-hidden border-2 border-orange-600 rounded p-6 space-y-3">
      <div></div>
      <div>Click the <span class="font-bold">Trigger Commercial Break</span> button below to go to commercial.</div>
      <div>You will see a countdown timer until the show resumes. This will be 1-2 minutes.</div>
      <div>As a notTV creator, your voice matters. How can this feature be improved to better serve you and your
        sponsors?
        Please email and let us know: <a href="mailto:hello@not.tv" target="_blank"
                                         class="text-blue-500 hover:text-blue-600">hello@not.tv</a>
      </div>
      <div class="w-full flex justify-center pt-4">
        <button onclick="setCommercialBreakNotice.showModal()"
                class="bg-orange-600 hover:bg-orange-500 text-white font-semibold rounded-lg px-4 py-2">Trigger
          Commercial Break
        </button>
      </div>
    </div>

  </div>


</template>

<script setup>
// import { useTimeAgo } from '@vueuse/core'
import { computed, onMounted, ref } from 'vue'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useVideoAuxPlayerStore } from '@/Stores/VideoAuxPlayerStore'
import { useGoLiveStore } from '@/Stores/GoLiveStore'
import VideoJsAux from '@/Components/Global/VideoPlayer/VideoJs/VideoJsAux'
import videojs from 'video.js'

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()
const videoAuxPlayerStore = useVideoAuxPlayerStore()
const goLiveStore = useGoLiveStore()

let props = defineProps({
  show: Object,
})


let videoSource = videoPlayerStore.mistServerUri + 'hls/' + goLiveStore.selectedShow.mist_stream_wildcard.name
    + '/index.m3u8'
let videoSourceType = 'application/x-mpegURL'

// Fetch server info on component mount
goLiveStore.fetchStreamInfo(goLiveStore.selectedShow.mist_stream_wildcard.name)

// Asynchronously fetch the JSON data
// async function fetchServerInfo() {
//   try {
//     const response = await fetch('http://mist.nottv.io:8080/json_dvr_playback_2.js'); // Replace with your URL
//     if (!response.ok) throw new Error('Failed to fetch');
//     const data = await response.json();
//     serverInfo.value = data; // Store the data in serverInfo
//     console.log (serverInfo)
//   } catch (error) {
//     console.error('Error fetching server info:', error);
//   }
// }

// let videoSrc = ref('')
// let videoJsAux = ('aux-player')

// const videoOptions = ref({
//   autoplay: true,
//   muted: true,
//   controls: true,
//   fill: true,
//   sources: [
//     {
//       src: videoSource,
//       type: videoSourceType,
//     },
//   ],
// });

const reloadPlayer = () => {
  let source = goLiveStore.selectedShow.mist_stream_wildcard.name
  let sourceUrl = videoPlayerStore.mistServerUri + 'hls/' + source + '/index.m3u8'
  console.log('source url: ' + sourceUrl)
  let sourceType = 'application/vnd.apple.mpegurl'
  let videoJs = videojs('aux-player')
  videoJs.src({'src': sourceUrl, 'type': sourceType})
  // videoAuxPlayerStore.loadNewLiveSource(source, sourceType)
  console.log('reload player')
  goLiveStore.fetchStreamInfo(goLiveStore.selectedShow.mist_stream_wildcard.name)
}

goLiveStore.fetchRtmpUri()

onMounted(() => {
  // videoSrc = goLiveStore.selectedShow.mist_stream_wildcard.name
  // videoJsAux.src({'src': videoSrc, 'type': 'application/vnd.apple.mpegurl'});
  // videoJsAux.ready(() => {
  //
  // })
  startCountdown()

  console.log('onPlayerReady AUX')
  // fetchServerInfo()

})

// Initial countdown time in seconds (5 minutes * 60 seconds)
const countdownTime = 5 * 60
// Reactive state for the countdown
const countdown = ref(countdownTime)

// Computed property to format the countdown as mm:ss
const formattedCountdown = computed(() => {
  const minutes = Math.floor(countdown.value / 60)
  const seconds = countdown.value % 60
  return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
})

// Function to start the countdown
const startCountdown = () => {
  const interval = setInterval(() => {
    countdown.value--

    if (countdown.value < 0) {
      clearInterval(interval) // Stop the interval
      countdown.value = countdownTime // Reset countdown
      startCountdown() // Restart the countdown
    }
  }, 1000)
}

const liveOrRecordingGrayButtonClass = computed(() => {
  if (goLiveStore.isLive || goLiveStore.isRecording) {
    return 'bg-gray-200 hover:bg-gray-400'
  } else {
    return ''
  }
})

const liveOrRecordingVideoBorderClass = computed(() => {
  if (goLiveStore.isLive || goLiveStore.isRecording) {
    return 'border-4 border-red-700'
  } else {
    return ''
  }
})

//
// // Assuming props.show.mist_stream_wildcard.name exists and is reactive
// const playerTargetId = ref('');
//
// const initializePlayer = () => {
//   const targetElement = document.getElementById(playerTargetId.value);
//   if (targetElement) {
//     mistPlay(goLiveStore.selectedShow.mist_stream_wildcard.name, {
//       target: targetElement,
//       fillSpace: true
//     });
//   } else {
//     console.error('Target element for video player not found.');
//   }
// };
//
// const removePlayer = () => {
//   const existingContainer = document.getElementById(playerTargetId.value);
//   if (existingContainer) {
//     existingContainer.remove();
//   }
// };
//
//
// const reloadPlayer = () => {
//   removePlayer();
//
//   nextTick(() => {
//     const newPlayerContainer = document.createElement('div');
//     const uniqueId = `${goLiveStore.selectedShow.mist_stream_wildcard.name}-${Date.now()}`;
//     newPlayerContainer.id = uniqueId;
//     playerTargetId.value = uniqueId;
//
//     document.body.appendChild(newPlayerContainer); // Append to body or choose a specific container
//
//     loadAndInitializePlayer();
//   });
// };
//
// const loadAndInitializePlayer = () => {
//   if (!window.mistplayers) {
//     const playerScript = document.createElement("script");
//     playerScript.src = "https://mist.nottv.io:443/player.js"; // Assuming HTTPS site
//     document.head.appendChild(playerScript);
//     playerScript.onload = initializePlayer;
//   } else {
//     initializePlayer();
//   }
// };
//
// onMounted(() => {
//   playerTargetId.value = `${goLiveStore.selectedShow.mist_stream_wildcard.name}-${Date.now()}`;
//   loadAndInitializePlayer();
// });
//
// watch(() => playerTargetId.value, (newVal) => {
//   if (newVal) {
//     loadAndInitializePlayer();
//   }
// });
//
// onBeforeUnmount(() => {
//   removePlayer(); // Ensure the player's container is removed when the component unmounts
// });


//
//
// const loadAndInitializePlayer = () => {
//   if (!window.mistplayers) {
//     const playerScript = document.createElement("script");
//     playerScript.src = location.protocol === "https:" ? "https://mist.nottv.io:443/player.js" : "http://mist.nottv.io:8080/player.js";
//     document.head.appendChild(playerScript);
//     playerScript.onload = initializePlayer;
//   } else {
//     initializePlayer();
//   }
// };
//
// const removePlayer = () => {
//   const targetElement = document.getElementById(playerTargetId.value);
//   if (targetElement) {
//     // Option 1: Remove the child element completely
//     targetElement.parentNode.removeChild(targetElement);
//
//     // OR Option 2: Reset the content of the element
//     // targetElement.innerHTML = '';
//
//     console.log('Video player removed.');
//   } else {
//     console.error('Target element for video player not found for removal.');
//   }
// };
//
// onMounted(() => {
//   playerTargetId.value = `${props.show.mist_stream_wildcard.name}-${Date.now()}` // Set the target ID based on props
//   console.log(playerTargetId.value)
//   loadAndInitializePlayer();
//   // nextTick(() => {
//   //   loadAndInitializePlayer();
//   // });
// });
//
// // onMounted(() => {
// //
// //   // loadAndInitializePlayer()
// //   if (!window.mistplayers) {
// //     const playerScript = document.createElement("script");
// //     playerScript.src = location.protocol === "https:" ? "https://mist.nottv.io:443/player.js" : "http://mist.nottv.io:8080/player.js";
// //     document.head.appendChild(playerScript);
// //     playerScript.onload = initializePlayer;
// //   } else {
// //     initializePlayer();
// //   }
// // });
//
// // Define `reloadPlayer` method to reload the player. This could be the same as `initializePlayer`
// // if reloading simply means reinitializing.
// const reloadPlayer = () => {
//   // Remove the existing player and its container
//   removePlayer();
//
//   // Recreate the container element for the player
//   const newPlayerContainer = document.createElement('div');
//   newPlayerContainer.id = `${props.show.mist_stream_wildcard.name}-${Date.now()}`; // Set the new target ID
//   playerTargetId.value = newPlayerContainer.id; // Update the reactive reference
//
//   // Append the new container to the appropriate parent element in your DOM
//   // Replace `parentElementSelector` with the actual selector of the parent where the player should be attached
//   const parentElement = document.querySelector('parentElementSelector');
//   if (parentElement) {
//     parentElement.appendChild(newPlayerContainer);
//   } else {
//     console.error('Parent element for video player not found.');
//     return;
//   }
//
//   // Initialize the player in the new container
//   loadAndInitializePlayer();
// };
//
// // If there's a need to watch for `playerTargetId` changes to reinitialize the player
// watch(() => playerTargetId.value, (newVal) => {
//   // Load and initialize player only if newVal is truthy
//   if (newVal) {
//     loadAndInitializePlayer();
//   }
// });
//
// // const loadAndInitializePlayer = () => {
// //   if (!window.mistplayers) {
// //     const playerScript = document.createElement("script");
// //     playerScript.src = location.protocol === "https:" ? "https://mist.nottv.io:443/player.js" : "http://mist.nottv.io:8080/player.js";
// //     document.head.appendChild(playerScript);
// //     playerScript.onload = initializePlayer;
// //   } else {
// //     initializePlayer();
// //   }
// // };
//
// // onBeforeUnmount(() => {
// //   // Example cleanup logic; adjust based on your player's API
// //   if (window.mistplayers && window.mistplayers[playerTargetId.value]) {
// //     window.mistplayers[playerTargetId.value].destroy(); // Hypothetical destroy method
// //   }
// // });
//
// onBeforeUnmount(() => {
//   if (videoPlayer && typeof videoPlayer.dispose === 'function') {
//     videoPlayer.dispose();
//   }
// });
// let videoEmbedCode = props.episode.video_file_embed_code;

// const timeAgo = useTimeAgo(new Date(2023, 10, 5))
// const timeAgo = useTimeAgo(props.episode.scheduledDateTime)
</script>
