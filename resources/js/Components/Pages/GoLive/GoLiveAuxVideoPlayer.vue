<template>
  <div class="flex flex-col justify-center mt-3 h-fit">
    <div class="text-xs tracking-wider font-semibold mb-1 pl-11">LIVE VIDEO STREAM</div>

    <div class="flex flex-row flex-wrap">
      <div class="flex flex-col">

        <div class="px-10 h-fit w-fit">

          <button @click="goLiveStore.reloadPlayer();"
                  class="btn btn-xs w-full py-1"
                  :class="liveOrRecordingGrayButtonClass"
          ><span v-if="goLiveStore.playerIsReloading" class="loading loading-spinner loading-xs"></span> Reload Player
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

                    @click="videoPlayerStore.mute">
              <font-awesome-icon icon="fa-volume-mute" class="mr-1 cursor-pointer hover:text-blue-500"/>
              Mute Main Video Audio
            </button>
            <button v-else
                    class="btn btn-neutral text-white btn-xs"
                    @click="videoPlayerStore.unMute"
            >
              <font-awesome-icon icon="fa-volume-up" class="mr-1 cursor-pointer hover:text-blue-500"/>
              Turn On Main Video Audio
            </button>
          </div>
          <div class="mt-2 ml-2">
            <button v-if="!videoAuxPlayerStore.muted"
                    class="btn btn-warning btn-xs"

                    @click="videoAuxPlayerStore.mute">
              <font-awesome-icon icon="fa-volume-mute" class="mr-1 cursor-pointer hover:text-blue-500"/>
              Mute Live Stream Audio
            </button>
            <button v-else
                    class="btn btn-neutral text-white btn-xs"

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
</template>
<script setup>
import { useGoLiveStore } from '@/Stores/GoLiveStore'
import { useVideoAuxPlayerStore } from '@/Stores/VideoAuxPlayerStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { computed, onMounted, onUnmounted, ref, watchEffect } from 'vue'
import VideoJsAux from '@/Components/Global/VideoPlayer/VideoJs/VideoJsAux'
import videojs from 'video.js'


const goLiveStore = useGoLiveStore()
const videoAuxPlayerStore = useVideoAuxPlayerStore()
const videoPlayerStore = useVideoPlayerStore()

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

const videoSource = ref(videoPlayerStore.mistServerUri + 'hls/' + goLiveStore?.selectedShow?.mist_stream_wildcard.name
    + '/index.m3u8')
const videoSourceType = ref('application/vnd.apple.mpegURL')

onMounted(async() => {
  console.log('onPlayerReady AUX')
  // await goLiveStore.fetchStreamInfo()
})

// Cleanup when the component unmounts
onUnmounted(() => {
  videoAuxPlayerStore.disposePlayer()
})


// const source = ref('')

// const videoPlayerStore = useVideoPlayerStore

// if (goLiveStore?.selectedShow?.mist_stream_wildcard?.name) {
//   source.value = goLiveStore?.selectedShow?.mist_stream_wildcard?.name
//   goLiveStore.fetchStreamInfo(goLiveStore?.selectedShow?.mist_stream_wildcard?.name)
// } else if (goLiveStore?.episode?.mist_stream_wildcard?.name) {
//   source.value = goLiveStore?.episode?.mist_stream_wildcard?.name
//   goLiveStore.fetchStreamInfo(goLiveStore?.episode?.mist_stream_wildcard?.name)
// }
//
// const reloadPlayer = async () => {
//   // Create an object and set the 'name' property
//   const mistStream = {
//     name: goLiveStore.sourceName
//   };
//   await videoAuxPlayerStore.getMistServerUri()
//   // Now, you can pass the 'mistStream' object to the method
//   videoAuxPlayerStore.loadMistStreamVideo(mistStream, true);
//
//     console.log('source: ' + mistStream.name)
// };

// const reloadPlayer = () => {
//   const mistStream.name = goLiveStore.sourceName
//
//   videoAuxPlayerStore.loadMistStreamVideo(mistStream)
//

//
//   // let sourceUrl = videoPlayerStore.mistServerUri + 'hls/' + source + '/index.m3u8'
//   // console.log('source url: ' + sourceUrl)
//   // let sourceType = 'application/vnd.apple.mpegurl'
//   // let videoJs = videojs('aux-player')
//   // videoJs.src({'src': sourceUrl, 'type': sourceType})
//   // // videoAuxPlayerStore.loadNewLiveSource(source, sourceType)
//   console.log('reload player')
// }
</script>