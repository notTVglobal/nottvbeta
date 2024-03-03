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
            <button @click="openObsInstructions = !openObsInstructions"
                    class="btn bg-blue-500 hover:bg-blue-700 rounded-lg text-white ml-2 ">How To Stream From OBS
            </button>

          </div>
          <div v-if="openObsInstructions">
            <h2>Stream from OBS or other software using these details:</h2>
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

        <div class="flex flex-row flex-wrap justify-between">
          <div class="flex flex-col justify-center border-2 border-green-500 rounded-lg px-2 py-2">
            <div class="flex flex-row">
              <div class="mb-2">
                <button v-if="!goLiveStore.isRecording" @click="goLiveStore.startRecording"
                        class="btn text-white bg-green-500 hover:bg-green-700 uppercase"
                >Start Recording
                </button>
                <button v-else @click="goLiveStore.stopRecording"
                        class="btn text-white bg-red-700 hover:bg-red-900 uppercase"
                >Stop Recording
                </button>
              </div>
              <div class="ml-2">
                <button v-if="!goLiveStore.isLive" @click="goLiveStore.goLive"
                        class="btn text-white bg-green-500 hover:bg-green-700 uppercase"
                >Go Live Now
                </button>
                <button v-else @click="goLiveStore.stopLive"
                        class="btn text-white bg-red-700 hover:bg-red-900 uppercase"
                >End Live
                </button>

              </div>
            </div>
            <div v-if="!goLiveStore.isRecording || !goLiveStore.isLive"
                 class="text-xs text-green-500 font-semibold tracking-wider text-center">
              Premium
              Creator
              Service
            </div>
          </div>

          <div class="ml-2">
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
        <div class="text-xs tracking-wider font-semibold mb-1 pl-1">VIDEO STREAM</div>

        <div class="flex flex-row flex-wrap">
          <div class="flex flex-col">
            <div class="px-10 h-fit w-fit">
              <button @click="reloadPlayer"
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

          <div v-if="goLiveStore.streamInfo && !goLiveStore.streamInfo.error" class="w-fit"
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
          <div class="flex flex-row justify-between">
            <div><h2 class="text-xl font-bold mb-4">Push Destinations</h2></div>
            <div>
              <button class="btn bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-lg"
                      @click.prevent="addDestination">Add Push
                Destinations
              </button>
            </div>
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
                  <p v-if="destination.push_is_started" class="text-green-500">Is Active</p>
                  <p v-if="destination.has_auto_push && !destination.push_is_started" class="text-green-500">Auto push
                    is enabled</p>
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
                      Auto Push
                    </button>
                    <button v-if="destination.has_auto_push"
                            @click="mistStore.disableAutoPush(destination.id)"
                            class="py-2 px-4 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition duration-150">
                      Remove Auto Push
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
import { computed, onBeforeUnmount, onMounted, onUnmounted, ref, watchEffect } from 'vue'
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


// async function getMistStreamPushDestinations() {
//   // Assuming `goLiveStore.selectedShow.mist_stream_wildcard.id` holds the wildcard ID
//   const wildcardId = goLiveStore?.selectedShow?.mist_stream_wildcard?.id
//   if (wildcardId) {
//     try {
//       // Append the wildcard ID as a query parameter
//       const response = await axios.get(`/mist-stream-push-destinations?wildcardId=${wildcardId}`)
//       mistStreamPushDestinations.value = response.data // Update the reactive variable
//     } catch (error) {
//       console.error('Failed to fetch push destinations:', error)
//     }
//   } else {
//     console.error('No wildcard ID found')
//   }
// }

// async function getMistStreamPushAutoList() {
//   // Assuming `goLiveStore.selectedShow.mist_stream_wildcard.id` holds the wildcard ID
//   const wildcardId = goLiveStore?.selectedShow?.mist_stream_wildcard?.id
//   if (wildcardId) {
//     try {
//       // Append the wildcard ID as a query parameter
//       const response = await axios.post(`/mist-stream/get-push-auto-list?wildcardId=${wildcardId}`)
//       mistStreamPushDestinations.value = response.data // Update the reactive variable
//     } catch (error) {
//       console.error('Failed to fetch push auto list:', error)
//     }
//   } else {
//     console.error('No wildcard ID found')
//   }
// }

// async function getMistStreamPushList() {
//   // Assuming `goLiveStore.selectedShow.mist_stream_wildcard.id` holds the wildcard ID
//   const wildcardId = goLiveStore?.selectedShow?.mist_stream_wildcard?.id
//   if (wildcardId) {
//     try {
//       // Append the wildcard ID as a query parameter
//       const response = await axios.post(`/mist-stream/get-push-list?wildcardId=${wildcardId}`)
//       mistStreamPushDestinations.value = response.data // Update the reactive variable
//     } catch (error) {
//       console.error('Failed to fetch push list:', error)
//     }
//   } else {
//     console.error('No wildcard ID found')
//   }
// }

// async function startPush(destinationId) {
//   console.log(`Starting push for destination ${destinationId}`)
//   const data = {destinationId}
//
//   try {
//     const response = await axios.post('/mist-stream/start-push', data, {
//       headers: {
//         'Content-Type': 'application/json',
//       },
//     })
//
//     console.log('Push started successfully:', response.data)
//     // Update the component's state to reflect the change
//     const index = mistStreamPushDestinations.value.findIndex(destination => destination.id === destinationId)
//     if (index !== -1) {
//       mistStreamPushDestinations.value[index].push_is_started = 1
//     }
//   } catch (error) {
//     console.error('Error starting push:', error)
//     // Handle the error appropriately in your UI
//   }
// }

// async function stopPush(destinationId) {
//   console.log(`Stopping push for destination ${destinationId}`)
//   const data = {destinationId}
//
//   try {
//     const response = await axios.post('/mist-stream/stop-push', data, {
//       headers: {
//         'Content-Type': 'application/json',
//       },
//     })
//
//     console.log('Push stopped successfully:', response.data)
//     // Update the component's state to reflect the change
//     const index = mistStreamPushDestinations.value.findIndex(destination => destination.id === destinationId)
//     if (index !== -1) {
//       mistStreamPushDestinations.value[index].push_is_started = 0
//     }
//   } catch (error) {
//     console.error('Error stopping push:', error)
//     // Handle the error appropriately in your UI
//   }
// }

// const enableAutoPush = async (destinationId) => {
//   const data = {
//     destinationId,
//   }
//
//   try {
//     const response = await axios.post('/mist-stream/push-auto-add', data, {
//       headers: {
//         'Content-Type': 'application/json',
//       },
//     })
//
//     console.log('Auto push enabled successfully:', response.data)
//     const index = mistStreamPushDestinations.value.findIndex(destination => destination.id === destinationId)
//     if (index !== -1) {
//       mistStreamPushDestinations.value[index].has_auto_push = 1
//     }
//   } catch (error) {
//     console.error('Error enabling auto push:', error)
//   }
// }

// async function disableAutoPush(destinationId) {
//   console.log(`Disabling auto push for destination ${destinationId}`)
//   const data = {
//     destinationId,
//   }
//
//   try {
//     const response = await axios.post('/mist-stream/push-auto-remove', data, {
//       headers: {
//         'Content-Type': 'application/json',
//       },
//     })
//
//     console.log('Auto push disabled successfully:', response.data)
//     const index = mistStreamPushDestinations.value.findIndex(destination => destination.id === destinationId)
//     if (index !== -1) {
//       mistStreamPushDestinations.value[index].has_auto_push = 0
//     }
//   } catch (error) {
//     console.error('Error disabling auto push:', error)
//   }
// }

// moved the logic into the mistStore...
// const wildcardId = ref(goLiveStore?.selectedShow?.mist_stream_wildcard?.id)
// console.log('do we have the wildcard ID? ' + wildcardId.value)
// mistStore.getMistStreamPushDestinations(wildcardId.value)

// mistStore.getMistStreamPushDestinations(goLiveStore?.selectedShow?.mist_stream_wildcard?.id)

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
//
// const deleteDestination = async (destinationId) => {
//   // Confirm deletion with the user before proceeding
//   if (confirm(`Are you sure you want to delete the destination with ID: ${destinationId}?`)) {
//     try {
//       // Perform the delete operation
//       await axios.delete(`/mist-stream-push-destinations/${destinationId}`)
//       console.log(`Deleted destination with ID: ${destinationId}`)
//       // Optionally, remove the item from your local state to update the UI
//       mistStreamPushDestinations.value = mistStreamPushDestinations.value.filter(destination => destination.id !== destinationId)
//     } catch (error) {
//       console.error(`Error deleting destination with ID: ${destinationId}`, error)
//     }
//   }
// }


let videoSource = videoPlayerStore.mistServerUri + 'hls/' + goLiveStore?.selectedShow?.mist_stream_wildcard.name
    + '/index.m3u8'
let videoSourceType = 'application/vnd.apple.mpegURL'

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

// watchEffect(() => {
//   const mistServerUri = videoPlayerStore.mistServerUri
//   if (mistServerUri) {
//     reloadPlayer()
//   }
// })


// check push_auto_list and update

onMounted(() => {

  // Automatically start the countdown or trigger based on an event
  startCountdown()

  console.log('onPlayerReady AUX')
  // fetchServerInfo()

  // check the push destinations
  mistStore.getMistStreamPushDestinations(goLiveStore?.selectedShow?.mist_stream_wildcard?.id)

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