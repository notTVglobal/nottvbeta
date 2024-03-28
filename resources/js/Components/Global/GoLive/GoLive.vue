<template>
  <div class="mx-0 md:mx-4 mt-4 mb-2 px-2 md:px-6 py-1">
    <div
        class="text-sm font-semibold  text-white text-center w-full border-2 rounded uppercase px-0 md:px-6 py-1 "
        :class="[ goLiveStore.isLive ? 'bg-gray-600 border-gray-600 ' : 'bg-red-600 border-red-600' ]">
      Go Live Instructions
    </div>
    <div class="shadow overflow-hidden border-2 border-red-600 rounded p-2 md:p-6"
         :class="[ goLiveStore.isLive ? 'bg-gray-100' : 'bg-red-100' ]">


      <div class="flex flex-row flex-wrap-reverse w-full justify-between gap-2">
        <div>
          <div class="mb-2">
            <button @click="appSettingStore.btnRedirect('/training/go-live-using-zoom')"
                    class="btn bg-blue-500 hover:bg-blue-700 text-white">How To Stream From Zoom
            </button>
            <button @click="openObsInstructions = !openObsInstructions"
                    class="btn bg-blue-500 hover:bg-blue-700 text-white ml-2" :class="{'bg-yellow-800 hover:bg-yellow-900 ':openObsInstructions}">
              <span v-if="!openObsInstructions">View Your Stream Key</span>
              <span v-else>Hide Your Stream Key</span>
            </button>

          </div>
          <div v-if="openObsInstructions" class="my-4 ml-10">
            <h3>Stream from OBS or other software using these details:</h3>
            <div>RTMP full url: <span v-if="fullUrl" class="font-bold">{{ fullUrl }}</span>
              &nbsp;<button v-if="rtmpUri && streamKey" @click="copyFullUrl">
                <font-awesome-icon v-if="rtmpUri && streamKey" icon="fa-clipboard"
                                   class="text-blue-500 hover:text-blue-700 hover:cursor-pointer"/>
              </button>
              <span v-if="showCopiedFullUrl" class="ml-1 copied-message" style="transition: opacity 0.5s; opacity: 1;">Copied!</span>
            </div>
            <div>RTMP url: <span class="font-bold">{{ rtmpUri }}</span>
              &nbsp;<button v-if="rtmpUri" @click="copyRtmpUri">
                <font-awesome-icon v-if="rtmpUri" icon="fa-clipboard"
                                   class="text-blue-500 hover:text-blue-700 hover:cursor-pointer"/>
              </button>
              <span v-if="showCopiedRtmpUri" class="ml-1 copied-message" style="transition: opacity 0.5s; opacity: 1;">Copied!</span>
            </div>
            <div>RTMP stream key: <span class="font-bold">{{ streamKey }}</span>
              &nbsp;<button v-if="streamKey" @click="copyStreamKey">
                <font-awesome-icon v-if="streamKey" icon="fa-clipboard"
                                   class="text-blue-500 hover:text-blue-700 hover:cursor-pointer"/>
              </button>
              <span v-if="showCopiedStreamKey" class="ml-1 copied-message"
                    style="transition: opacity 0.5s; opacity: 1;">Copied!</span>
            </div>
          </div>
        </div>

        <div class="flex flex-row flex-wrap justify-between grow ml-4">
          <div v-if="!openObsInstructions" class="flex flex-col justify-center border-2 border-green-500 rounded-lg px-2 py-2">
            <div class="flex flex-row">
              <div class="mb-2">
                <button v-if="!goLiveStore.isRecording" @click="goLiveStore.startRecording"
                        disabled
                        class="btn btn-sm text-white bg-green-500 hover:bg-green-700 uppercase"
                >Start Recording
                </button>
                <button v-else disabled @click="goLiveStore.stopRecording"
                        class="btn btn-sm text-white bg-red-700 hover:bg-red-900 uppercase"
                >Stop Recording
                </button>
              </div>
              <div class="ml-2">
                <button v-if="!goLiveStore.isLive" disabled @click="goLiveStore.goLive"
                        class="btn btn-sm text-white bg-green-500 hover:bg-green-700 uppercase"
                >Go Live Now
                </button>
                <button v-else disabled @click="goLiveStore.stopLive"
                        class="btn btn-sm text-white bg-red-700 hover:bg-red-900 uppercase"
                >End Live
                </button>

              </div>
            </div>
            <div v-if="!goLiveStore.isRecording || !goLiveStore.isLive"
                 class="text-xs text-green-500 font-semibold tracking-wider text-center">
              Coming Soon!
            </div>
          </div>

          <div>
            <button class="btn btn-secondary" @click="openStats">Live Analytics</button></div>

          <div class="">
            <div>Live will begin in... &nbsp;</div>
            <!--          <div class="font-semibold">{{ formattedCountdown }} (for demo purposes only)</div>-->
            <div class="countdown font-mono text-2xl">
              <!-- Hours (if your countdown includes hours) -->
              <span :style="{ '--value': hours }">{{ hours.toString().padStart(2, '0') }}</span>h
              <!-- Minutes -->
              <span :style="{ '--value': minutes }">{{ minutes.toString().padStart(2, '0') }}</span>m
              <!-- Seconds -->
              <span :style="{ '--value': seconds }">{{ seconds.toString().padStart(2, '0') }}</span>s
            </div>
            <div class="text-xs">for demo purposes only</div>
          </div>
        </div>
      </div>




      <div class="flex flex-col justify-center mt-3 h-fit">
        <div class="text-xs tracking-wider font-semibold mb-1 pl-11">LIVE VIDEO STREAM</div>

        <div class="flex flex-row flex-wrap">
          <div class="flex flex-col">

            <div class="px-10 h-fit w-fit">
              <button @click="reloadPlayer();"
                      class="btn btn-xs w-full"
                      :class="liveOrRecordingGrayButtonClass"
              >Reload Player
              </button>
              <div v-if="goLiveStore.isLive || goLiveStore.isRecording"
                   class="w-full bg-red-700 text-white text-center uppercase font-bold">
                <span v-if="goLiveStore.isLive">LIVE</span> <span v-if="goLiveStore.isLive && goLiveStore.isRecording"> + </span>
                <span v-if="goLiveStore.isRecording">RECORDING</span>
              </div>
              <video-js-aux :id="`aux-player`"
                            :source="videoSource"
                            :sourceType="videoSourceType"
                            class=""
                            :class="liveOrRecordingVideoBorderClass"/>


            </div>
            <div class="flex flex-row px-10 w-full justify-center">
              <div class="mt-2">
                <button v-if="!videoPlayerStore.muted"
                        class="btn btn-warning btn-xs"
                        :class="liveOrRecordingGrayButtonClass"
                        @click="videoPlayerStore.mute">
                  <font-awesome-icon icon="fa-volume-mute" class="mr-1 cursor-pointer hover:text-blue-500"/>
                  Mute Main Video Audio
                </button>
                <button v-else
                        class="btn btn-neutral text-white btn-xs"
                        @click="videoPlayerStore.unMute"
                        :class="liveOrRecordingGrayButtonClass">
                  <font-awesome-icon icon="fa-volume-up" class="mr-1 cursor-pointer hover:text-blue-500"/>
                  Turn On Main Video Audio
                </button>
              </div>
              <div class="mt-2 ml-2">
                <button v-if="!videoAuxPlayerStore.muted"
                        class="btn btn-warning btn-xs"
                        :class="liveOrRecordingGrayButtonClass"
                        @click="videoAuxPlayerStore.mute">
                  <font-awesome-icon icon="fa-volume-mute" class="mr-1 cursor-pointer hover:text-blue-500"/>
                  Mute Live Stream Audio
                </button>
                <button v-else
                        class="btn btn-neutral text-white btn-xs"
                        :class="liveOrRecordingGrayButtonClass"
                        @click="videoAuxPlayerStore.unMute">
                  <font-awesome-icon icon="fa-volume-up" class="mr-1 cursor-pointer hover:text-blue-500"/>
                  Turn On Live Stream Audio
                </button>
              </div>
            </div>
          </div>

          <div v-if="goLiveStore.streamInfo && !goLiveStore.streamInfo.error" class="w-80"
               :key="goLiveStore.selectedShowId">
            <div>
              <h3>Stream Info</h3>
              <!--                <RecursivePropertyList :object="serverInfo" />-->

              <p><strong>Width:</strong> {{ goLiveStore.streamInfo?.width }}</p>
              <p><strong>Height:</strong> {{ goLiveStore.streamInfo?.height }}</p>

              <!-- Assuming you want to display tracks info specifically -->
              <div v-if="goLiveStore.streamInfo?.meta?.tracks">
                <h4 class="font-semibold">Tracks</h4>
                <ul>
                  <li v-for="(track, name) in goLiveStore?.streamInfo?.meta.tracks" :key="name">
                    &middot; {{ name }} - Codec: {{ track.codec }}, Rate: {{ track.rate }}
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div v-if="!goLiveStore?.streamInfo && !goLiveStore?.streamInfo?.error">
            <div class="flex flex-col">
              <span class="mb-2">Loading stream data...</span>
              <span class="loading loading-spinner text-neutral"></span>
            </div>

          </div>
          <div v-if="goLiveStore?.streamInfo?.error">
            <div class="flex flex-col">
              <span class="mb-2">{{ goLiveStore?.streamInfo?.error }}</span>
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

          <div class="flex flex-row flex-wrap-reverse w-full justify-between">
            <div class="mb-2">
              <button @click="appSettingStore.btnRedirect('/training/how-to-push-to-facebook')"
                      class="btn btn-sm bg-blue-500 hover:bg-blue-700 rounded-lg text-white">How To Push To Facebook
              </button>
              <button @click="appSettingStore.btnRedirect('/training/how-to-push-to-rumble')"
                      class="btn btn-sm bg-blue-500 hover:bg-blue-700 rounded-lg text-white ml-2 ">How To Push To Rumble
              </button>
            </div>
            <div>
              <button class="btn bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-lg"
                      @click.prevent="addDestination">Add Push
                Destinations
              </button>
            </div>
          </div>


          <div class="flex flex-row justify-between">
            <div><h2 class="text-xl font-bold">Push Destinations</h2></div>

          </div>

          <div v-if="mistStore.mistStreamPushDestinations.length === 0">
            <div>Set up <span class="font-bold">push destinations:</span></div>
            <div>Here you can set additional streaming destinations such as Facebook, YouTube, Rumble, Twitch, etc. and
              notTV will automatically start pushing to those destinations when you go live.
            </div>
          </div>
          <div v-if="mistStore.mistStreamPushDestinations">
            <div class="flex flex-col gap-4">
              <div v-for="destination in mistStore.mistStreamPushDestinations" :key="destination.id"
                   class="border p-4 rounded-lg shadow flex flex-row items-center gap-4">
                <img :src="destination.destination_image" alt="Destination Image"
                     class="w-24 h-24 object-cover rounded-full"/>
                <div class="flex-grow">
                  <h3 class="text-lg font-semibold">{{ destination.destination_name }}</h3>
                  <h4 class="">{{ destination.comment }}</h4>
                  <p v-if="destination.has_auto_push" class="text-yellow-500 font-semibold">Auto push
                    is enabled</p>
                  <p v-if="destination.push_is_started" class="text-red-500 font-semibold">Push Is Active</p>
                  <div class="flex gap-2 mt-2">
                    <button v-if="destination.push_is_started" @click="mistStore.stopPush(destination.id)"
                            class="py-2 px-4 bg-red-500 text-white rounded hover:bg-red-700 transition duration-150">
                      Stop Push
                    </button>
                    <button v-else @click="mistStore.startPush(destination.id)"
                            class="py-2 px-4 bg-blue-500 text-white rounded hover:bg-blue-700 transition duration-150">
                      Start Push
                    </button>
                    <button v-if="!destination.has_auto_push"
                            @click="mistStore.enableAutoPush(destination.id)"
                            class="py-2 px-4 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition duration-150">
                      Enable Auto Push
                    </button>
                    <button v-if="destination.has_auto_push"
                            @click="mistStore.disableAutoPush(destination.id)"
                            class="py-2 px-4 bg-gray-500 text-white rounded hover:bg-gray-600 transition duration-150">
                      Disable Auto Push
                    </button>
                  </div>
                </div>
                <div class="flex flex-row justify-end">
                  <!--                  <button @click="editDestination(destination.id)" class="py-2 px-4 bg-green-500 text-white rounded hover:bg-green-600 transition duration-150">Edit</button>-->
                  <!--                  <button @click="deleteDestination(destination.id)" class="py-2 px-4 bg-gray-500 text-white rounded hover:bg-gray-600 transition duration-150">Delete</button>-->

                  <button
                      @click.prevent="editDestination(destination)"
                      class="btn btn-sm text-white font-semibold bg-blue-500 hover:bg-blue-600 rounded-lg">
                    <font-awesome-icon icon="fa-pencil" class="my-1 mx-1"/>
                  </button>
                </div>
                <button
                    @click.prevent="mistStore.deleteDestination(destination.id)"
                    class="btn btn-sm text-white font-semibold bg-red-500 hover:bg-red-600 rounded-lg"
                >
                  <font-awesome-icon icon="fa-trash-can" class="my-1 mx-1"/>
                </button>


              </div>
            </div>
          </div>
        </div>

      </div>
      <MistStreamPushDestinationForm @update-success="mistStore.getMistStreamPushDestinations"
                                     :destinationDetails="destinationDetails"
                                     :mode="mistStreamPushDestinationFormModalMode"/>

    </div>

  </div>

  <div class="mx-4 mt-4 mb-8 px-6 py-1 ">
    <div
        class="text-sm font-semibold bg-orange-600 text-white text-center w-full border-2 border-orange-600 rounded uppercase px-6 py-1 ">
      Commercial Breaks
    </div>
    <div class="shadow bg-orange-100 overflow-hidden border-2 border-orange-600 rounded py-6 px-10 space-y-3 w-full">
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
import { computed, onMounted, onUnmounted, ref, watchEffect } from 'vue'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useVideoAuxPlayerStore } from '@/Stores/VideoAuxPlayerStore'
import { useGoLiveStore } from '@/Stores/GoLiveStore'
import { useMistStore } from '@/Stores/MistStore'
import VideoJsAux from '@/Components/Global/VideoPlayer/VideoJs/VideoJsAux'
import videojs from 'video.js'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { useClipboard } from '@vueuse/core'
import MistStreamPushDestinationForm from '@/Components/Global/MistStreams/MistStreamPushDestinationForm'

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()
const videoAuxPlayerStore = useVideoAuxPlayerStore()
const goLiveStore = useGoLiveStore()
const mistStore = useMistStore()

let props = defineProps({
  // show: Object,
})

const showCopiedFullUrl = ref(false)
const showCopiedRtmpUri = ref(false)
const showCopiedStreamKey = ref(false)
const {copy} = useClipboard()

const openObsInstructions = ref(false)

// const mistStreamPushDestinations = ref([])
const mistStreamPushDestinationFormModalMode = ref('add')
const destinationDetails = ref({})


watchEffect(() => {
  // This code will run initially and re-run every time selectedShow or its mist_stream_wildcard.id changes
  const wildcardId = goLiveStore.wilcardId
  if (wildcardId) {
    mistStore.getMistStreamPushDestinations(wilcardId)
  }
})

const addDestination = async () => {
  mistStreamPushDestinationFormModalMode.value = 'add'
  const wildcardId = goLiveStore.selectedShow?.mist_stream_wildcard?.id
  destinationDetails.value = {mist_stream_wildcard_id: wildcardId} // Initialize destinationDetails with the wildcard ID

  document.getElementById('mistStreamPushDestinationForm').showModal()
}

const editDestination = async (destination) => {
  mistStreamPushDestinationFormModalMode.value = 'edit'
  destinationDetails.value = destination
  document.getElementById('mistStreamPushDestinationForm').showModal()
  console.log(`Editing destination with ID: ${destination}`)
  const index = mistStreamPushDestinations.value.findIndex(destination => destination.id === destinationDetails.value.id)
  if (index !== -1) {
    mistStreamPushDestinations.value[index].has_auto_push = 0
  }
}

const videoSource = ref(videoPlayerStore.mistServerUri + 'hls/' + goLiveStore?.selectedShow?.mist_stream_wildcard.name
    + '/index.m3u8')
const videoSourceType = ref('application/vnd.apple.mpegURL')

// Fetch server info on component mount
// goLiveStore.fetchStreamInfo(goLiveStore?.selectedShow?.mist_stream_wildcard.name)
// goLiveStore.fetchRtmpUri()

// Now using computed properties to directly refer to goLiveStore getters
const rtmpUri = computed(() => goLiveStore.fullRtmpUri)
const streamKey = computed(() => goLiveStore.streamKey)
const fullUrl = computed(() => goLiveStore.fullUrl)


// Initialize fetching of server information
goLiveStore.updateAndGetStreamKey()
goLiveStore.fetchStreamInfo(goLiveStore?.selectedShow?.mist_stream_wildcard.name)
goLiveStore.fetchRtmpUri()


// Reactively update URLs when the store updates
// watchEffect(() => {
//   if (goLiveStore.rtmpUri) {
//     rtmpUri.value = goLiveStore.rtmpUri + 'live/'
//     // Check if it's an episode or a selected show and update accordingly
//     if (goLiveStore.isEpisode && goLiveStore.episode?.mist_stream_wildcard?.name) {
//       streamKey.value = goLiveStore.episode.mist_stream_wildcard.name
//     } else if (!goLiveStore.isEpisode && goLiveStore.selectedShow?.mist_stream_wildcard?.name) {
//       streamKey.value = goLiveStore.selectedShow.mist_stream_wildcard.name
//     }
//     fullUrl.value = `${rtmpUri.value}${streamKey.value}`
//   }
// })

// Function to handle the copy action and display the "copied" message for each type
const copyFullUrl = () => {
  copy(fullUrl.value)
  showCopiedFullUrl.value = true
  setTimeout(() => showCopiedFullUrl.value = false, 1000)
}

const copyRtmpUri = () => {
  copy(rtmpUri.value)
  showCopiedRtmpUri.value = true
  setTimeout(() => showCopiedRtmpUri.value = false, 1000)
}

const copyStreamKey = () => {
  copy(streamKey.value)
  showCopiedStreamKey.value = true
  setTimeout(() => showCopiedStreamKey.value = false, 1000)
}

const reloadPlayer = () => {
  let source = null
  if (goLiveStore?.selectedShow?.mist_stream_wildcard?.name) {
    source = goLiveStore?.selectedShow?.mist_stream_wildcard?.name
    goLiveStore.fetchStreamInfo(goLiveStore?.selectedShow?.mist_stream_wildcard?.name)
  } else if (goLiveStore?.episode?.mist_stream_wildcard?.name) {
    source = goLiveStore?.episode?.mist_stream_wildcard?.name
    goLiveStore.fetchStreamInfo(goLiveStore?.episode?.mist_stream_wildcard?.name)
  }
  let sourceUrl = videoPlayerStore.mistServerUri + 'hls/' + source + '/index.m3u8'
  console.log('source url: ' + sourceUrl)
  let sourceType = 'application/vnd.apple.mpegurl'
  let videoJs = videojs('aux-player')
  videoJs.src({'src': sourceUrl, 'type': sourceType})
  // videoAuxPlayerStore.loadNewLiveSource(source, sourceType)
  console.log('reload player')
}

const openStats = () => {
  window.open('/stats', '_blank');
};

// watchEffect(() => {
//   const mistServerUri = videoPlayerStore.mistServerUri
//   if (mistServerUri) {
//     reloadPlayer()
//   }
// })


// check push_auto_list and update

onMounted(async() => {

  // Automatically start the countdown or trigger based on an event
  startCountdown()

  console.log('onPlayerReady AUX')
  // fetchServerInfo()

  // check the push destinations
  await mistStore.getMistStreamPushDestinations(goLiveStore?.selectedShow?.mist_stream_wildcard?.id)

  // check the auto push list
  // mistStore.getMistStreamPushAutoList(goLiveStore?.selectedShow?.mist_stream_wildcard?.id)

  // check the push list
  // mistStore.getMistStreamPushList(goLiveStore?.selectedShow?.mist_stream_wildcard?.id)

})

// Keep a reference to the interval ID so it can be cleared
let intervalId = null

// Initial countdown time in seconds (5 minutes * 60 seconds)
const countdownTime = 5 * 60
// Reactive state for the countdown
const countdown = ref(countdownTime)

// Computed properties for hours, minutes, and seconds
const hours = computed(() => Math.floor(countdown.value / 3600))
const minutes = computed(() => Math.floor((countdown.value % 3600) / 60))
const seconds = computed(() => countdown.value % 60)

// Function to start the countdown
const startCountdown = () => {
  // // Clear any existing interval to prevent multiple intervals
  // if (intervalId !== null) {
  //   clearInterval(intervalId)
  // }
  //
  // // Reset countdown to initial value
  // countdown.value = countdownTime
  //
  // // Start a new interval
  // intervalId = setInterval(() => {
  //   countdown.value--
  //
  //   if (countdown.value < 0) {
  //     clearInterval(intervalId) // Stop the interval
  //     intervalId = null // Reset the interval ID
  //     // Optionally, you can reset countdown.value to countdownTime or another value here
  //   }
  // }, 1000)
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
// const mistStreamWildcardId = ref()
// mistStreamWildcardId.value = goLiveStore?.selectedShow?.mist_stream_wildcard?.id
const channel = Echo.channel(`mistStreamWildcard.${goLiveStore?.selectedShow?.mist_stream_wildcard?.id}`)
channel.subscribed(() => {
  // Handle successful subscription
  // This log will confirm the subscription success
  console.log('Successfully subscribed to the channel!')
})
    .listen('.push-out-start', (event) => {
      console.log('push out start EVENT BROADCASTED!')
      const index = mistStore.mistStreamPushDestinations.findIndex(destination =>
          `${destination.rtmp_url}${destination.rtmp_key}` === event.requestUrl)
      if (index !== -1) {
        mistStore.mistStreamPushDestinations.value[index].push_is_started = 1
      }
    })
    .listen('.push-end', (event) => {
      console.log('push end EVENT BROADCASTED!')
      const index = mistStore.mistStreamPushDestinations.findIndex(destination =>
          `${destination.rtmp_url}${destination.rtmp_key}` === event.requestUrl)
      if (index !== -1) {
        mistStore.mistStreamPushDestinations[index].push_is_started = 0
      }
    })

// Cleanup when the component unmounts
onUnmounted(() => {
  channel.stopListening('.push-out-start')
  channel.stopListening('.push-end')
  Echo.leave(`mistStreamWildcard.${goLiveStore?.selectedShow?.mist_stream_wildcard?.id}`)

  // Cleanup interval on component unmount (countdown timer)
  if (intervalId !== null) {
    clearInterval(intervalId)
  }

  videoAuxPlayerStore.disposePlayer()
  // let videoJs = videojs('aux-player')
  // if (videoJs) {
  //   videoJs.dispose()
  // }
})

</script>

<style scoped>
@keyframes fadeOut {
  from {
    opacity: 1;
  }
  to {
    opacity: 0;
  }
}
</style>