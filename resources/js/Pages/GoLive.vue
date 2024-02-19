<template>
  <Head title="Go Live"/>
  <!--        <template #header>-->
  <!--            <h2 class="font-semibold text-xl text-gray-800 leading-tight">-->
  <!--                Dashboard-->
  <!--            </h2>-->
  <!--        </template>-->

  <!--        <div class="py-12">-->
  <!--            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">-->
  <!--                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">-->

  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white text-black p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <div class="flex justify-between mx-4 px-6">
        <div class="grid grid-cols-1 grid-rows-2">
          <h1 class="text-3xl font-semibold">Go Live</h1>
        </div>
        <div>
          <CancelButton/>
        </div>
        <!--                <div class="grid grid-cols-1 grid-rows-2">-->
        <!--                    <div class="justify-self-end">-->
        <!--                        <Link :href="`/dashboard`"><button-->
        <!--                            class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"-->
        <!--                        >Dashboard</button>-->
        <!--                        </Link>-->
        <!--                    </div>-->
        <!--                </div>-->
      </div>
      <div v-if="goLiveStore.shows && goLiveStore.shows.length > 0" class="mb-6 mx-4 px-6">
        <label class="block mb-2 uppercase font-bold text-xs text-light text-gray-700"
               for="show"
        >
          Select Show To Go Live On
        </label>

        <select
            class="border border-gray-400 text-gray-800 p-2 w-full rounded-lg block my-2 uppercase font-bold text-xs "
            v-model="goLiveStore.selectedShowId"
            @change="reloadPlayer"
        >
          <option disabled value="">Select show</option>
          <option v-for="show in goLiveStore.shows"
                  :key="show.id" :value="show.id">{{ show.name }}
          </option>

        </select>



      </div>

      <div v-else class="bg-black w-3/4 text-center px-10 py-6 text-white mx-auto border-red-700 border-2">
        You don't have any shows to go live with... please check your show(s).
      </div>

      <div v-if="goLiveStore.selectedShow" class="text-2xl font-semibold text-center w-full">
        <Link :href="`/shows/${goLiveStore.selectedShow.slug}/manage`">{{goLiveStore.selectedShow.name}}</Link>
      </div>

      <GoLive v-if="goLiveStore.selectedShow && goLiveStore.selectedShow.mist_stream_wildcard_id" />
      <div v-if="goLiveStore.selectedShow && !goLiveStore.selectedShow.mist_stream_wildcard_id" class="flex flex-col justify-items-center text-center px-16">
        <div class="mb-3">Please generate a stream key:</div>
        <div><button @click="handleGenerateStreamKey" class="btn btn-sm w-fit bg-green-500 hover:bg-green-700 text-white">generate key</button></div>
      </div>
    </div>
  </div>
  <!--                </div>-->
  <!--            </div>-->
  <!--        </div>-->

  <ManageShowEpisodeNoticeModals />
</template>

<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useVideoAuxPlayerStore } from '@/Stores/VideoAuxPlayerStore'
import { useGoLiveStore } from '@/Stores/GoLiveStore'
import Message from '@/Components/Global/Modals/Messages'
import CancelButton from '@/Components/Global/Buttons/CancelButton'
import GoLive from '@/Components/Global/GoLive/GoLive'
import { computed, onMounted, ref, watch, withDefaults } from 'vue'
import videojs from 'video.js'
import ManageShowEpisodeNoticeModals from '@/Components/Pages/ShowEpisodes/Elements/ManageShowEpisodeNoticeModals.vue'
import Button from '@/Jetstream/Button.vue'

usePageSetup('goLive')

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()
const videoAuxPlayerStore = useVideoAuxPlayerStore()
const goLiveStore = useGoLiveStore()

const props = defineProps({
  shows: Object
})

onMounted(async () => {
  goLiveStore.isEpisode = null
  goLiveStore.episode = null
  goLiveStore.fetchShows().then(() => {
    if (goLiveStore.preSelectedShowId) {
      goLiveStore.selectedShowId = goLiveStore.preSelectedShowId;
    }
  });
});

// const selectedShowId = ref('');
// const selectedShow = computed(() => {
//   return props.shows?.find(show => show.id === selectedShowId.value) || null;
// });
// const selectedShow = computed(() => goLiveStore.selectedShow);

const reloadPlayer = () => {
  let source = goLiveStore?.selectedShow?.mist_stream_wildcard?.name
  let sourceUrl = videoAuxPlayerStore.mistServerUri + 'hls/' + source + '/index.m3u8'
  console.log('source url: ' + sourceUrl)
  let sourceType = 'application/vnd.apple.mpegurl'
  let videoJs = videojs('aux-player')
  videoJs.src({'src': sourceUrl, 'type': sourceType})
  // videoAuxPlayerStore.loadNewLiveSource(source, sourceType)
  goLiveStore.fetchStreamInfo(goLiveStore?.selectedShow?.mist_stream_wildcard?.name);
  console.log('reload player')
}

const onChangeShow = (event) => {
  goLiveStore.setSelectedShowId(event);
  goLiveStore.fetchStreamInfo(goLiveStore?.selectedShow?.mist_stream_wildcard?.name);
  reloadPlayer()
  // videoAuxPlayerStore.loadNewVideo(goLiveStore.selectedShow.mist)
};



const handleGenerateStreamKey = async () => {
  await goLiveStore.generateStreamKey();
};

// const generateStreamKey = () => {
//   // Ensure selectedShowId is accessible and has a value
//   if (!goLiveStore.selectedShowId.value) {
//     console.error("No show selected");
//     return; // Exit the function if no show is selected
//   }
//
//   axios.post(`/go-live/shows/${goLiveStore.selectedShowId.value}/stream-key`)
//       .then(response => {
//         // Handle the successful response here
//         console.log("Stream key generated:", response.data);
//         // You might want to do something with the response data, like updating a data property
//       })
//       .catch(error => {
//         // Handle any errors here
//         console.error("Error generating stream key:", error.response ? error.response.data : error);
//       });
// }

// watch(goLiveStore.preSelectedShowId, (newVal, oldVal) => {
//   if (newVal !== '') {
//     // Assuming the video player is ready to be initialized at this point
//     // const videoPlayer = videojs('main-player');
//     videoPlayerStore.mute()
//     // goLiveStore.selectedShowId = selectedShowId
//
//     // Additional logic to load the video based on selectedShowId can be added here
//   }
// });
</script>

