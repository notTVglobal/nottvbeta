<template>
  <div :class="containerClass">
    <div>
      <div :class="buttonContainerClass">
        <div>
          <button @click="openObsInstructions = !openObsInstructions"
                  class="btn bg-blue-500 hover:bg-blue-700 text-white ml-2"
                  :class="{'bg-yellow-800 hover:bg-yellow-900 ':openObsInstructions}">
            <span v-if="!openObsInstructions">View Your Stream Key</span>
            <span v-else>Hide Your Stream Key</span>
          </button>
        </div>
        <div v-if="!openObsInstructions">
          <button @click="appSettingStore.btnRedirect('/training/go-live-using-zoom')"
                  class="btn bg-blue-500 hover:bg-blue-700 text-white">How To Stream From Zoom
          </button>
        </div>
      </div>
      <div v-if="openObsInstructions" :class="obsInstructionsClass">
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

    <div v-if="!openObsInstructions" :class="liveControlsClass">
      <div class="flex flex-col justify-center border-2 border-green-500 rounded-lg px-2 py-2 gap-2">
        <div class="flex flex-row gap-2">
          <div class="mb-2">
            <button v-if="!goLiveStore.isRecording" @click="goLiveStore.startRecording"
                    class="w-36 btn btn-sm text-white bg-green-500 hover:bg-green-700 uppercase"
            >
              <span v-if="!goLiveStore.processingRecordingChange">Start Recording</span>
              <span v-else class="loading loading-spinner loading-xs text-white"></span>
            </button>
            <button v-else @click="goLiveStore.stopRecording"
                    class="w-36 btn btn-sm text-white bg-red-700 hover:bg-red-900 uppercase"
            >
              <span v-if="!goLiveStore.processingRecordingChange">Stop Recording</span>
              <span v-else class="loading loading-spinner loading-xs text-white"></span>
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

      <GoLiveCountdown />

<!--      <div v-if="countdownMessage">-->
<!--        <div>{{ countdownMessage }}</div>-->
<!--      </div>-->
<!--      <div v-else>-->
<!--        <div>Live will begin in... &nbsp;</div>-->
<!--        <div class="countdown font-mono text-2xl">-->
<!--          <span v-if="days > 0" :style="{ '&#45;&#45;value': days }">{{ days.toString().padStart(2, '0') }}</span><span v-if="days > 0">d</span>-->
<!--          <span :style="{ '&#45;&#45;value': hours }">{{ hours.toString().padStart(2, '0') }}</span>h-->
<!--          <span :style="{ '&#45;&#45;value': minutes }">{{ minutes.toString().padStart(2, '0') }}</span>m-->
<!--          <span :style="{ '&#45;&#45;value': seconds }">{{ seconds.toString().padStart(2, '0') }}</span>s-->
<!--        </div>-->
<!--      </div>-->

<!--      <div>Live will begin in... &nbsp;</div>-->
<!--      <div class="countdown-container">-->
<!--        <div class="countdown font-mono text-4xl">-->
<!--          <span v-if="days > 0" class="countdown-unit">{{ days.toString().padStart(2, '0') }}</span><span v-if="days > 0">d</span>-->
<!--          <span class="countdown-unit">{{ hours.toString().padStart(2, '0') }}</span>h-->
<!--          <span class="countdown-unit">{{ minutes.toString().padStart(2, '0') }}</span>m-->
<!--          <span class="countdown-unit">{{ seconds.toString().padStart(2, '0') }}</span>s-->
<!--        </div>-->
<!--      </div>-->
    </div>
  </div>
</template>
<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useGoLiveStore } from '@/Stores/GoLiveStore'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { computed, onMounted, onUnmounted, ref } from 'vue'
import { useClipboard } from '@vueuse/core'
import dayjs from 'dayjs';
import duration from 'dayjs/plugin/duration';
import GoLiveCountdown from '@/Components/Pages/GoLive/GoLiveCountdown.vue'

dayjs.extend(duration);

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


const containerClass = computed(() => {
  return appSettingStore.isSmallScreen ? 'flex flex-col w-full' : 'flex flex-row flex-wrap-reverse w-full justify-between gap-2'
})

const buttonContainerClass = computed(() => {
  return appSettingStore.isSmallScreen ? 'flex flex-col gap-2 mb-2' : 'flex flex-row flex-wrap gap-x-2 gap-y-2 mb-2'
})

const aobsButtonClass = computed(() => {
  return openObsInstructions.value ? 'bg-yellow-800 hover:bg-yellow-900' : 'bg-blue-500 hover:bg-blue-700'
})

const zoomButtonClass = computed(() => {
  return appSettingStore.isSmallScreen ? 'w-full mt-2' : 'bg-blue-500 hover:bg-blue-700'
})

const obsInstructionsClass = computed(() => {
  return appSettingStore.isSmallScreen ? 'my-4' : 'my-4 ml-10'
})

const liveControlsClass = computed(() => {
  return appSettingStore.isSmallScreen ? 'flex flex-col gap-2' : 'flex flex-row flex-wrap justify-between grow ml-4 gap-2'
})

// Keep a reference to the interval ID so it can be cleared
let intervalId = null

// Initial countdown time in seconds (5 minutes * 60 seconds)
const countdownTime = 5 * 60
// Reactive state for the countdown
const countdown = ref(countdownTime)

const nextBroadcast = computed(() => goLiveStore.selectedShow?.nextBroadcast);

const days = ref(0);
const hours = ref(0);
const minutes = ref(0);
const seconds = ref(0);
const countdownMessage = ref('');

const updateCountdown = () => {
  if (!nextBroadcast.value) {
    countdownMessage.value = "No broadcast is currently scheduled!";
    return;
  }

  const now = dayjs();
  const targetTime = dayjs.utc(nextBroadcast.value); // Convert the next broadcast time to a dayjs object in UTC
  const difference = targetTime.diff(now);

  if (difference <= 0) {
    countdownMessage.value = "The broadcast is live now!";
    return;
  }

  const duration = dayjs.duration(difference);

  days.value = Math.floor(duration.asDays());
  hours.value = duration.hours();
  minutes.value = duration.minutes();
  seconds.value = duration.seconds();
};

// Computed properties for hours, minutes, and seconds
// const hours = computed(() => Math.floor(countdown.value / 3600))
// const minutes = computed(() => Math.floor((countdown.value % 3600) / 60))
// const seconds = computed(() => countdown.value % 60)

const openStats = () => {
  window.open('/stats', '_blank')
}


// Function to start the countdown
// const startCountdown = () => {
//   // // Clear any existing interval to prevent multiple intervals
//   // if (intervalId !== null) {
//   //   clearInterval(intervalId)
//   // }
//   //
//   // // Reset countdown to initial value
//   // countdown.value = countdownTime
//   //
//   // // Start a new interval
//   // intervalId = setInterval(() => {
//   //   countdown.value--
//   //
//   //   if (countdown.value < 0) {
//   //     clearInterval(intervalId) // Stop the interval
//   //     intervalId = null // Reset the interval ID
//   //     // Optionally, you can reset countdown.value to countdownTime or another value here
//   //   }
//   // }, 1000)
// }

onMounted(async () => {
  // Automatically start the countdown or trigger based on an event
  // startCountdown()
  updateCountdown();
  intervalId = setInterval(updateCountdown, 1000); // Update every second
})

// Cleanup when the component unmounts
onUnmounted(() => {
  // Cleanup interval on component unmount (countdown timer)
  clearInterval(intervalId);
  // if (intervalId !== null) {
  //   clearInterval(intervalId)
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

.countdown-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background-color: #f5f5f5;
  padding: 10px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.countdown {
  display: flex;
  gap: 10px;
}

.countdown-unit {
  background-color: #1f2937;
  color: #ffffff;
  padding: 5px 10px;
  border-radius: 5px;
  font-weight: bold;
}
</style>