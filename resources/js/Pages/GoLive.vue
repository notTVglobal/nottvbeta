<template>
  <Head title="Go Live"/>

  <div :class="containerClass">
    <div id="topDiv" class="bg-white text-black p-5 mb-10">
      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <div :class="headerClass">
        <div>
          <h1 :class="titleClass">Go Live</h1>
        </div>
        <div :class="buttonContainerClass">
          <CancelButton/>
        </div>
      </div>

      <div v-if="goLiveStore.shows && goLiveStore.shows.length > 0" class="mb-6 w-full" :class="showSelectorClass">
        <label class="block my-2 uppercase font-bold text-xs text-light text-neutral dark:text-white" for="show">
          Select Show To Go Live On
        </label>
        <select
            class="select select-info select-lg w-full p-2 block my-2 uppercase font-bold text-lg bg-white dark:bg-gray-800 dark:text-white"
            v-model="goLiveStore.selectedShowId"
            @change="reloadPlayer"
        >
          <option disabled selected>Select show</option>
          <option v-for="show in goLiveStore.shows" :key="show.id" :value="show.id">{{ show.name }}</option>
        </select>
      </div>

      <div v-else>
        <div v-if="showLoader" class="bg-black text-center py-6 text-white mx-auto border-red-700 border-2">
          <span class="loading loading-ball mr-2"/> Please wait while we load your shows...
        </div>
        <div v-else class="bg-black text-center py-6 text-white mx-auto border-red-700 border-2" :class="noShowsClass">
          ⚠️ You don't have any shows to go live with... please check your show(s) from your
          <button
              @click.prevent="router.visit('/dashboard')"
              class="text-white bg-transparent border-none underline underline-offset-4 cursor-pointer hover:text-gray-300"
          >dashboard
          </button>.
        </div>
      </div>

<!--      <div v-else class="bg-black text-center py-6 text-white mx-auto border-red-700 border-2" :class="noShowsClass">-->
<!--        You don't have any shows to go live with... please check your show(s).-->
<!--      </div>-->

      <div v-if="goLiveStore.selectedShow" class="text-center w-full hover:text-blue-500" :class="selectedShowClass">
        <Link :href="`/shows/${goLiveStore.selectedShow.slug}/manage`">{{ goLiveStore.selectedShow.name }}</Link>
      </div>

      <GoLive v-if="goLiveStore.selectedShow && goLiveStore.selectedShow.mist_stream_wildcard_id"/>
      <div v-if="goLiveStore.selectedShow && !goLiveStore.selectedShow.mist_stream_wildcard_id"
           class="flex flex-col justify-items-center text-center" :class="streamKeyClass">
        <div v-if="generateStreamKeyError" class="text-red-700">{{ generateStreamKeyError }}</div>
        <div v-if="generateStreamKeyProcessing && !generateStreamKeyError">
          <div>Stream key is being generated...</div>
          <div><span class="loading loading-infinity loading-lg text-primary"></span></div>
        </div>
        <div v-if="!generateStreamKeyProcessing && !generateStreamKeyError">
          <div class="mb-3">Please generate a stream key:</div>
          <div>
            <button @click="handleGenerateStreamKey"
                    class="btn btn-sm w-fit bg-green-500 hover:bg-green-700 text-white">Generate Key
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <ManageShowEpisodeNoticeModals/>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useVideoAuxPlayerStore } from '@/Stores/VideoAuxPlayerStore'
import { useGoLiveStore } from '@/Stores/GoLiveStore'
import Message from '@/Components/Global/Modals/Messages'
import CancelButton from '@/Components/Global/Buttons/CancelButton'
import GoLive from '@/Components/Global/GoLive/GoLive'
import ManageShowEpisodeNoticeModals from '@/Components/Pages/ShowEpisodes/Elements/ManageShowEpisodeNoticeModals.vue'
import Button from '@/Jetstream/Button.vue'
import { router } from '@inertiajs/vue3'

usePageSetup('goLive')

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()
const videoAuxPlayerStore = useVideoAuxPlayerStore()
const goLiveStore = useGoLiveStore()

const props = defineProps({
  shows: Object,
})

const showLoader = ref(true)

const containerClass = computed(() => {
  return 'place-self-center flex flex-col gap-y-3 min-h-screen bg-blue-900'
})

const headerClass = computed(() => {
  return appSettingStore.isSmallScreen ? 'flex flex-row flex-wrap justify-between mx-4 px-6 w-full' : 'flex flex-row justify-between mx-4 px-6 w-full'
})

const titleClass = computed(() => {
  return appSettingStore.isSmallScreen ? 'text-xl font-semibold' : 'text-3xl font-semibold'
})

const buttonContainerClass = computed(() => {
  return appSettingStore.isSmallScreen ? 'my-2' : 'mt-0'
})

const showSelectorClass = computed(() => {
  return appSettingStore.isSmallScreen ? 'px-4' : 'px-6'
})

const noShowsClass = computed(() => {
  return appSettingStore.isSmallScreen ? 'w-full px-4' : 'w-3/4 px-10'
})

const selectedShowClass = computed(() => {
  return appSettingStore.isSmallScreen ? 'text-xl font-semibold' : 'text-3xl font-semibold'
})

const streamKeyClass = computed(() => {
  return appSettingStore.isSmallScreen ? 'px-4' : 'px-16'
})

onMounted(async () => {

  setTimeout(() => {
    showLoader.value = false
  }, 10000)

  // Use the browser's window.location to get the full URL and extract the query parameter
  const url = new URL(window.location.href)
  const queryShowId = url.searchParams.get('show')

  // Convert the query parameter to an integer
  const selectedShowId = queryShowId ? parseInt(queryShowId, 10) : null

  console.log('Query Show ID:', queryShowId) // This should correctly log the query parameter

  goLiveStore.isEpisode = null
  goLiveStore.episode = null

  await goLiveStore.fetchShows()

  if (selectedShowId) {
    goLiveStore.selectedShowId = selectedShowId
  } else if (goLiveStore.preSelectedShowId) {
    goLiveStore.selectedShowId = goLiveStore.preSelectedShowId
    console.log('Setting query parameter for preSelectedShowId:', goLiveStore.preSelectedShowId)

    // Manually update the URL with the query string
    const newUrl = `${window.location.pathname}?show=${goLiveStore.preSelectedShowId}`
    window.history.replaceState({}, '', newUrl)
  } else {
    goLiveStore.selectedShowId = null
  }

  // goLiveStore.fetchShows().then(() => {
  //   if (goLiveStore.preSelectedShowId) {
  //     goLiveStore.selectedShowId = goLiveStore.preSelectedShowId
  //   }
  // })
})

const reloadPlayer = async () => {
  // Use a new Promise to wait for 1 second
  await new Promise(resolve => setTimeout(resolve, 1000)) // 1000 milliseconds = 1 second

  // Update the query string with the selected show ID
  const newUrl = `${window.location.pathname}?show=${goLiveStore.selectedShowId}`
  window.history.replaceState({}, '', newUrl)

  // After waiting, call the reloadPlayer method
  await goLiveStore.reloadPlayer()
}

const generateStreamKeyProcessing = ref(false)
const generateStreamKeyError = ref('')

// const onChangeShow = async (event) => {
//   goLiveStore.setSelectedShowId(event);
//   await goLiveStore.fetchStreamInfo(goLiveStore?.selectedShow?.mist_stream_wildcard?.name);
//   await goLiveStore.reloadPlayer();
// };


const handleGenerateStreamKey = async () => {
  generateStreamKeyProcessing.value = true // Start processing

  try {
    // Await the store's generateStreamKey method
    await goLiveStore.generateStreamKey()
    // Optional: Perform any additional actions after the key has been generated
    await goLiveStore.fetchStreamInfo(goLiveStore?.selectedShow?.mist_stream_wildcard?.name)
    await goLiveStore.reloadPlayer()
  } catch (error) {
    // Check if the error is from Axios and has a response object
    let displayError = 'Failed to generate stream key: '
    if (error.response && error.response.data && error.response.data.error) {
      // If there's an error message in the response data, use it
      displayError += error.response.data.error
    } else if (error.message) {
      // Fallback to the error's message if no detailed response data is available
      displayError += error.message
    } else {
      // Generic error text if neither of the above is available
      displayError += 'An unexpected error occurred.'
    }
    console.log(displayError)
    generateStreamKeyError.value = displayError // Display the detailed error message on the page
  } finally {
    generateStreamKeyProcessing.value = false // End processing
  }
}
</script>

