<template>
  <div :class="containerClass">
    <div class="text-xs tracking-wider font-semibold mb-1" :class="labelClass">LIVE VIDEO STREAM</div>

    <div :class="flexRowClass">
      <div class="flex flex-col">

        <div :class="playerContainerClass">
          <button @click="goLiveStore.reloadPlayer();" class="btn btn-xs w-full py-1" :class="liveOrRecordingGrayButtonClass">
            <span v-if="goLiveStore.playerIsReloading" class="loading loading-spinner loading-xs"></span> Reload Player
          </button>
          <div v-if="goLiveStore.isLive || goLiveStore.isRecording" class="w-full bg-red-700 text-white text-center uppercase font-bold">
            <span v-if="goLiveStore.isLive">LIVE</span> <span v-if="goLiveStore.isLive && goLiveStore.isRecording"> + </span>
            <span v-if="goLiveStore.isRecording">RECORDING</span>
          </div>
          <video-js-aux :id="`aux-player`" :source="videoSource" :sourceType="videoSourceType" class="" :class="liveOrRecordingVideoBorderClass"/>
        </div>

        <div :class="controlsContainerClass">
          <div class="mt-2">
            <button v-if="!videoPlayerStore.muted" class="btn btn-warning btn-xs" @click="videoPlayerStore.mute">
              <font-awesome-icon icon="fa-volume-mute" class="mr-1 cursor-pointer hover:text-blue-500"/> Mute Main Video Audio
            </button>
            <button v-else class="btn btn-neutral text-white btn-xs" @click="videoPlayerStore.unMute">
              <font-awesome-icon icon="fa-volume-up" class="mr-1 cursor-pointer hover:text-blue-500"/> Turn On Main Video Audio
            </button>
          </div>
          <div class="mt-2 ml-2">
            <button v-if="!videoAuxPlayerStore.muted" class="btn btn-warning btn-xs" @click="videoAuxPlayerStore.mute">
              <font-awesome-icon icon="fa-volume-mute" class="mr-1 cursor-pointer hover:text-blue-500"/> Mute Live Stream Audio
            </button>
            <button v-else class="btn btn-neutral text-white btn-xs" @click="videoAuxPlayerStore.unMute">
              <font-awesome-icon icon="fa-volume-up" class="mr-1 cursor-pointer hover:text-blue-500"/> Turn On Live Stream Audio
            </button>
          </div>
        </div>
      </div>

      <div v-if="goLiveStore.streamInfo && !goLiveStore.streamInfo.error" class="w-80" :key="goLiveStore.selectedShowId">
        <div>
          <h3>Stream Info</h3>
          <p><strong>Width:</strong> {{ goLiveStore.streamInfo?.width }}</p>
          <p><strong>Height:</strong> {{ goLiveStore.streamInfo?.height }}</p>

          <div v-if="goLiveStore.streamInfo?.meta?.tracks">
            <div><span class="font-semibold">Live: </span> {{ goLiveStore.streamInfo?.meta?.live }}</div>
            <div><span class="font-semibold">Buffer Window: </span>{{ formatBufferWindow(goLiveStore.streamInfo?.meta?.buffer_window) }}</div>
            <h4 class="font-semibold">Tracks:</h4>
              <div v-for="(track, name) in goLiveStore?.streamInfo?.meta.tracks" :key="name">
                <div class="flex flex-col">
                  <div>&middot; {{ name }}</div>
                  <div class="pl-2">
                    <span v-if="track.type === 'video' || track.type === 'audio'">{{ formatBitrate(track.bps) }}</span>
                  </div>
                </div>

              </div>
          </div>
        </div>
      </div>

      <div v-if="!goLiveStore?.streamInfo && !goLiveStore?.streamInfo?.error" class="flex flex-col">
        <span class="mb-2">Loading stream data...</span>
        <span class="loading loading-spinner text-neutral"></span>
      </div>

      <div v-if="goLiveStore?.streamInfo?.error" class="flex flex-col">
        <span class="mb-2">{{ goLiveStore?.streamInfo?.error }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useGoLiveStore } from '@/Stores/GoLiveStore'
import { useVideoAuxPlayerStore } from '@/Stores/VideoAuxPlayerStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { computed, onMounted, onUnmounted, ref, watchEffect } from 'vue'
import VideoJsAux from '@/Components/Global/VideoPlayer/VideoJs/VideoJsAux'

const goLiveStore = useGoLiveStore()
const videoAuxPlayerStore = useVideoAuxPlayerStore()
const videoPlayerStore = useVideoPlayerStore()
const appSettingStore = useAppSettingStore()

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


const containerClass = computed(() => {
  return appSettingStore.isSmallScreen ? 'flex flex-col justify-center mt-3 h-fit' : 'flex flex-col justify-center mt-3 h-fit'
})

const labelClass = computed(() => {
  return appSettingStore.isSmallScreen ? 'pl-3' : 'pl-11'
})

const flexRowClass = computed(() => {
  return appSettingStore.isSmallScreen ? 'flex flex-col' : 'flex flex-row flex-wrap'
})

const playerContainerClass = computed(() => {
  return appSettingStore.isSmallScreen ? 'px-3' : 'px-10'
})

const controlsContainerClass = computed(() => {
  return appSettingStore.isSmallScreen ? 'flex flex-col w-full px-3 justify-center' : 'flex flex-row px-10 w-full justify-center'
})

const formatBufferWindow = (ms) => {
  if (ms >= 60000) {
    const minutes = Math.floor(ms / 60000)
    const seconds = ((ms % 60000) / 1000).toFixed(0)
    return `${minutes} minute${minutes > 1 ? 's' : ''} ${seconds} second${seconds > 1 ? 's' : ''}`
  } else if (ms >= 1000) {
    return (ms / 1000).toFixed(2) + ' s'
  } else {
    return ms + ' ms'
  }
}

const formatBitrate = (bps) => {
  if (bps >= 1000000) {
    return (bps / 1000000).toFixed(2) + ' Mbps'
  } else if (bps >= 1000) {
    return (bps / 1000).toFixed(2) + ' Kbps'
  } else {
    return bps + ' bps'
  }
}

onMounted(async() => {
  console.log('onPlayerReady AUX')
  // await goLiveStore.fetchStreamInfo()
})

// Cleanup when the component unmounts
onUnmounted(() => {
  videoAuxPlayerStore.disposePlayer()
})
</script>