<template>

  <Head title="How To Push To Rumble"/>

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
        <h1 class="text-2xl font-bold text-center mb-4">How To Push To Rumble</h1>

        <p class="mb-4">
          This guide will walk you through the process of pushing your Live Stream to Rumble.
        </p>

        <div class="space-y-6">
          <!-- Step 1: Configure Zoom -->
          Coming Soon!


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