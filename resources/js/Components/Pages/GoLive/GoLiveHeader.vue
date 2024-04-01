<template>
  <div class="flex flex-row flex-wrap-reverse w-full justify-between gap-2">
    <div>
      <div class="mb-2">
        <button @click="appSettingStore.btnRedirect('/training/go-live-using-zoom')"
                class="btn bg-blue-500 hover:bg-blue-700 text-white">How To Stream From Zoom
        </button>
        <button @click="openObsInstructions = !openObsInstructions"
                class="btn bg-blue-500 hover:bg-blue-700 text-white ml-2"
                :class="{'bg-yellow-800 hover:bg-yellow-900 ':openObsInstructions}">
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

    <div class="flex flex-row flex-wrap justify-between grow ml-4 gap-2">
      <div v-if="!openObsInstructions"
           class="flex flex-col justify-center border-2 border-green-500 rounded-lg px-2 py-2 gap-2">
        <div class="flex flex-row gap-2">
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

      <div class="ml-2">
        <button class="btn btn-secondary" @click="openStats">Live Analytics</button>
      </div>

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
</template>
<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useGoLiveStore } from '@/Stores/GoLiveStore'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { computed, onMounted, onUnmounted, ref, watchEffect } from 'vue'
import { useClipboard } from '@vueuse/core'

const appSettingStore = useAppSettingStore()
const goLiveStore = useGoLiveStore()

const showCopiedFullUrl = ref(false)
const showCopiedRtmpUri = ref(false)
const showCopiedStreamKey = ref(false)

const {copy} = useClipboard()
const openObsInstructions = ref(false)

const rtmpUri = computed(() => goLiveStore.fullRtmpUri)
const streamKey = computed(() => goLiveStore.streamKey)
const fullUrl = computed(() => goLiveStore.fullUrl)

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

const openStats = () => {
  window.open('/stats', '_blank')
}


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

onMounted(async () => {
  // Automatically start the countdown or trigger based on an event
  startCountdown()
})

// Cleanup when the component unmounts
onUnmounted(() => {
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