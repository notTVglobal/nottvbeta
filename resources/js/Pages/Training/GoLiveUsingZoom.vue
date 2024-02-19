<template>

  <Head title="How to Go Live Using Zoom"/>

  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>


      <!--      <header class="flex justify-between mb-3">-->
      <!--        <div class="pt-4">-->
      <!--          <h1 class="text-3xl font-semibold pb-3">My Library</h1>-->
      <!--        </div>-->

      <!--      </header>-->
      <!--      <div class="flex flex-row justify-end gap-x-4 mb-4">-->

      <!--        <input v-model="search" type="search" placeholder="Search..." class="border px-2 rounded-lg text-black"/>-->
      <!--      </div>-->

      <!--      <div class="mb-4">-->
      <!--        <div class="p-2 text-red-600">This section is in development. Not currently working.</div>-->
      <!--        <p class="">-->
      <!--          Your notTV Library... Shows and creators you follow will appear here. Movies and episodes on your Watch Later-->
      <!--          list. And live events you're interested in. Also, items from the shop you have saved will all appear here!-->
      <!--        </p>-->
      <!--      </div>-->

      <div class="p-4">
        <div class="flex flex-row justify-end">
          <BackButton/>
        </div>
        <h1 class="text-2xl font-bold text-center mb-4">How to Stream to notTV Using Zoom</h1>

        <p class="mb-4">
          This guide will walk you through the process of streaming your Zoom meetings or webinars directly to notTV.
        </p>

        <div class="space-y-6">
          <!-- Step 1: Configure Zoom -->
          <div>
            <h2 class="text-xl font-semibold mb-2">Step 1: Configure Zoom</h2>
            <p class="mb-4">
              Setting up Zoom for live streaming involves a few detailed steps. Let’s ensure Zoom is ready to connect
              with our notTV live streaming server.
            </p>

            <div class="pl-4 mb-4">
              <h3 class="text-lg font-semibold mb-1">1.1 Configure Your Account</h3>
              <p>
                Verify your Zoom account is on a Pro Plan or higher, as live streaming requires this level of service.
                In your account settings, activate "Live Streaming for meetings." <a
                  class="font-semibold text-blue-600 hover:text-blue-800"
                  href="https://support.zoom.com/hc/en/article?id=zm_kb&sysparm_article=KB0059839" target="_blank"
                  rel="noopener noreferrer">Follow the steps in this guide</a> to enable live streaming on your Zoom
                account.
              </p>
            </div>

            <div class="pl-4 mb-4">
              <h3 class="text-lg font-semibold mb-1">1.2 Create and Start a Meeting</h3>
              <p>
                Launch the latest version of the Zoom client and start a new meeting. This session will be the one we
                broadcast to notTV.
              </p>
            </div>

            <div class="pl-4 mb-4">
              <h3 class="text-lg font-semibold mb-1">1.3 Configure Live Streaming</h3>
              <p>
                Within the meeting, find the three dots indicating more options in the action bar’s lower right-hand
                corner. Select "Live on Custom Live Streaming Service." You'll be prompted to enter your notTV server's
                streaming information in a browser window.
              </p>
              <div v-if="!selectedShow" class="italic">
                Note: Be prepared to input your streaming details, such as Stream URL and Stream Key.
                <p class="mt-2">Get your streaming details on the
                  <Link class="text-blue-500 hover:text-blue-700 font-semibold" :href="`/golive`">Go Live page</Link>
                  .
                </p>
              </div>
              <div v-else class="pl-4 mt-4">
                <p class="text-lg font-semibold text-blue-600"><strong>Streaming URL:</strong> <span
                    class="text-blue-800">
                  {{rtmpUri}}</span>
                  &nbsp;<button @click="copyRtmpUri"><font-awesome-icon icon="fa-clipboard" class="text-blue-500 hover:text-blue-700 hover:cursor-pointer"/></button>
                  <span v-if="showCopiedRtmpUri" class="ml-1 copied-message" style="transition: opacity 0.5s; opacity: 1;">Copied!</span>
                </p>
                <p class="text-lg font-semibold text-green-600"><strong>Streaming Key:</strong> <span
                    class="text-green-800">{{streamKey}}</span>
                  &nbsp;<button @click="copyStreamKey"><font-awesome-icon icon="fa-clipboard" class="text-blue-500 hover:text-blue-700 hover:cursor-pointer"/></button>
                  <span v-if="showCopiedStreamKey" class="ml-1 copied-message" style="transition: opacity 0.5s; opacity: 1;">Copied!</span>
                </p>
                <p class="text-lg font-semibold text-red-600"><strong>Live Streaming Page URL: </strong>
                  {{ fullUrl }}
                  &nbsp;<button @click="copyFullUrl"><font-awesome-icon icon="fa-clipboard" class="text-blue-500 hover:text-blue-700 hover:cursor-pointer"/></button>
                  <span v-if="showCopiedFullUrl" class="ml-1 copied-message" style="transition: opacity 0.5s; opacity: 1;">Copied!</span>
                </p>

              </div>
              <img
                  src="https://global.discourse-cdn.com/business6/uploads/zoomdeveloper/original/3X/a/b/ab641cc4b01892f83290f24effe68ec017f407e7.png"
                  alt="Zoom Streaming Server Information"
                  class="mt-4 max-w-auto h-auto">
            </div>
          </div>


        </div>
      </div>

    </div>
  </div>

</template>

<script setup>
import { usePage } from '@inertiajs/inertia-vue3'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useGoLiveStore } from '@/Stores/GoLiveStore'
import Message from '@/Components/Global/Modals/Messages'
import { computed, ref, watchEffect } from 'vue'
import BackButton from '@/Components/Global/Buttons/BackButton'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { useClipboard } from '@vueuse/core'

usePageSetup('training.streamToNotTV')

const appSettingStore = useAppSettingStore()
const goLiveStore = useGoLiveStore()
let selectedShow = ref(null)

const page = usePage();

let props = defineProps({
  can: Object,
})

const showCopiedFullUrl = ref(false)
const showCopiedRtmpUri = ref(false)
const showCopiedStreamKey = ref(false)
const { copy } = useClipboard()

const fullUrl = ref('');
const rtmpUri = ref('');
const streamKey = ref('');
const appUrl = page.props.value.appUrl

const setSelectedShow = async () => {
  await goLiveStore.fetchShows().then (
      selectedShow = computed(() => goLiveStore.selectedShow)
  )
}
setSelectedShow()

// Reactively update URLs when the store updates
watchEffect(() => {
  if (goLiveStore.rtmpUri) {
    rtmpUri.value = goLiveStore.rtmpUri + 'live/';
    // Check if it's an episode or a selected show and update accordingly
    if (goLiveStore.isEpisode && goLiveStore.episode?.mist_stream_wildcard?.name) {
      streamKey.value = goLiveStore.episode.mist_stream_wildcard.name;
    } else if (!goLiveStore.isEpisode && goLiveStore.selectedShow?.mist_stream_wildcard?.name) {
      streamKey.value = goLiveStore.selectedShow.mist_stream_wildcard.name;
    }
    if (goLiveStore.isEpisode && goLiveStore.episode?.slug) {
      fullUrl.value = `${appUrl}/shows/${selectedShow.value?.slug}/episode/${goLiveStore.episode.slug}`;
    } else if (!goLiveStore.isEpisode && selectedShow.value?.slug) {
      fullUrl.value = `${appUrl}/shows/${selectedShow.value?.slug}`;
    }
  }
});

// Function to handle the copy action and display the "copied" message for each type
const copyFullUrl = () => {
  copy(fullUrl.value);
  showCopiedFullUrl.value = true;
  setTimeout(() => showCopiedFullUrl.value = false, 1000);
};

const copyRtmpUri = () => {
  copy(rtmpUri.value);
  showCopiedRtmpUri.value = true;
  setTimeout(() => showCopiedRtmpUri.value = false, 1000);
};

const copyStreamKey = () => {
  copy(streamKey.value);
  showCopiedStreamKey.value = true;
  setTimeout(() => showCopiedStreamKey.value = false, 1000);
};


</script>

<style scoped>
.copied-message-fade {
  animation: fadeOut 2s forwards;
}

@keyframes fadeOut {
  from {
    opacity: 1;
  }
  to {
    opacity: 0;
  }
}
</style>