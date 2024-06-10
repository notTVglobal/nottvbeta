<template>
  <div class="mx-4 mt-4 mb-2 px-6 py-1">

    <div
        class="text-sm font-semibold bg-blue-600 text-white text-center w-full border-2 border-blue-600 rounded uppercase px-6 py-1 ">
      Push Destinations
    </div>

    <div role="alert" class="alert">
      <!-- Ensure you're importing Tailwind CSS in your project -->
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
           class="stroke-current text-blue-500 w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
      </svg>

      <span>🌟 Our push statuses get a little refresh every minute. Hang tight and keep the creativity flowing – more awesome updates are on their way!</span>
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
        <div class="flex flex-col gap-2">
          <button class="btn bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-lg"
                  @click.prevent="addDestination">Add Push
            Destinations
          </button>
          <div class="flex flex-row justify-end gap-2">
            <span v-if="goLiveStore.isProcessingDisableAllAutoPushes" class="loading loading-spinner text-info"></span>
            <span v-show="!goLiveStore.isProcessingDisableAllAutoPushes" class="text-xs">Next refresh in... {{ countdown }}</span>
            <button
                v-if="anyDestinationHasActiveAutoPush"
                @click="goLiveStore.disableAllAutoPushes()"
                :disabled="goLiveStore.isProcessingDisableAllAutoPushes"
                class="py-2 px-4 bg-gray-500 text-white rounded hover:bg-gray-600 transition duration-150 disabled:bg-gray-400 disabled:cursor-not-allowed"
            >
              Disable All Auto Pushes
            </button>
          </div>

        </div>
      </div>

      <div class="flex flex-row justify-between mb-2 py-2 h-12">
        <div><h2 class="text-xl font-bold">Push Destinations</h2>
        </div>
        <div v-if="goLiveStore.isLoadingDestinations">
          <span class="loading loading-bars loading-lg text-info mr-2"> </span><span class="text-xs uppercase">Refreshing...</span>
        </div>
      </div>


      <div v-if="goLiveStore.destinations.length === 0">
        <div>Set up <span class="font-bold">push destinations:</span></div>
        <div>Here you can set additional streaming destinations such as Facebook, YouTube, Rumble, Twitch, etc. and
          notTV will automatically start pushing to those destinations when you go live.
        </div>
      </div>
      <div v-if="goLiveStore.destinations.length > 0">
        <GoLiveDestinationList />
      </div>
    </div>

    <MistStreamPushDestinationForm @update-success="mistStore.getMistStreamPushDestinations"
                                   :destinationDetails="goLiveStore.destinationDetails"

                                   :mode="goLiveStore.mistStreamPushDestinationFormModalMode"/>
    <ToastNotification/>
  </div>
</template>
<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useMistStore } from '@/Stores/MistStore'
import { useGoLiveStore } from '@/Stores/GoLiveStore'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import MistStreamPushDestinationForm from '@/Components/Global/MistStreams/MistStreamPushDestinationForm'
import { computed, onMounted, onUnmounted, ref, watch, watchEffect } from 'vue'
import ToastNotification from '@/Components/Global/Notifications/Toast/ToastNotification.vue'
import GoLiveDestinationList from '@/Components/Pages/GoLive/GoLiveDestinationList.vue'

const appSettingStore = useAppSettingStore()
const mistStore = useMistStore()
const goLiveStore = useGoLiveStore()

const countdown = ref(15) // Set initial countdown (in seconds)
const intervalId = ref(null)

const anyDestinationHasActiveAutoPush = computed(() => {
  return goLiveStore.destinations.some(destination => destination.has_auto_push === 1)
})

const addDestination = async () => {
  goLiveStore.mistStreamPushDestinationFormModalMode = 'add'
  const wildcardId = goLiveStore.selectedShow?.mist_stream_wildcard?.id
  goLiveStore.destinationDetails = {mist_stream_wildcard_id: wildcardId} // Initialize destinationDetails with the wildcard ID
  document.getElementById('mistStreamPushDestinationForm').showModal()
}


const backgroundFetchPushDestinationsStatus = async () => {
  if (goLiveStore.wildcardId) {
    await goLiveStore.backgroundFetchPushDestinations()
  }
}

const fetchPushDestinationsStatus = async () => {
  if (goLiveStore.wildcardId) {
    await goLiveStore.fetchPushDestinations()
  }
}

const fetchStreamInfo = async () => {
  await goLiveStore.fetchStreamInfo()
}

const reloadPlayer = async () => {
  await goLiveStore.reloadPlayer()
}

const backgroundFetch = async () => {
  try {
    // Fetch data concurrently
    await Promise.all([
      fetchPushDestinationsStatus(),
      fetchStreamInfo(),
    ]);

    // Reset the countdown at the beginning of the fetch cycle
    countdown.value = 15;

    // Check if the stream just transitioned from offline to online
    if (goLiveStore.previousStreamStatus === true && !goLiveStore.streamOffline) {
      // console.log('Stream just transitioned from offline to online, reloading player');
      await reloadPlayer();
    } else if (goLiveStore.streamOffline) {
      // console.log('Stream is offline, no action taken');
    } else {
      // console.log('Stream is online, no action taken');
    }

    // Update the previous stream status for the next check
    goLiveStore.previousStreamStatus = goLiveStore.streamOffline;
  } catch (error) {
    console.error('Error during background fetch:', error);
    // Depending on your application's needs, handle the error appropriately
  }
};


// Decrement the countdown every second
const startCountdown = () => {
  if (intervalId.value !== null) return // Prevent multiple intervals
  intervalId.value = setInterval(() => {
    if (countdown.value > 0) {
      countdown.value -= 1
    } else {
      backgroundFetch() // Refresh data when countdown reaches 0
    }
  }, 1000)
}


watch(() => goLiveStore.selectedShow, async (newVal, oldVal) => {
  if (newVal !== '') {
    await backgroundFetch()
    await fetchPushDestinationsStatus()
    // Additional logic to load the video based on selectedShow can be added here
  }
})

onMounted(async () => {
  // Fetch immediately and then set up an interval for periodic fetching
  startCountdown()
  await backgroundFetch()
  // intervalId = setInterval(backgroundFetch, 60000) // Fetch every 60 seconds
})

onUnmounted(() => {
  // Clear the interval when the component unmounts to prevent memory leaks
  clearInterval(intervalId.value)
})


</script>