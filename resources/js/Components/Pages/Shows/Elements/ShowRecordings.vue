<template>

  <table class="min-w-full divide-y divide-gray-200">
    <thead class="divide-y divide-gray-200">
    <!--                                <tr v-for="episode in episodes.data" :key="episode.id">-->
    <tr>
      <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
          <div>
            <div class="text-sm font-semibold pl-14">
              <!--                                                    <Link :href="`/admin/users/${episode.id}`" class="text-indigo-600 hover:text-indigo-900">{{ episode.name }}</Link>-->
              Date
            </div>
          </div>
        </div>
      </td>

      <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
        Start Time
      </td>

      <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
        End Time
      </td>

      <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
        Duration
      </td>

      <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-right">
        Expires in
      </td>

      <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-right">

      </td>
    </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
    <tr v-for="recording in showRecordings" :key="recording.id"
        @mouseover="state.hoveredRow = recording.id"
        @mouseleave="state.hoveredRow = null"
        @click="openVideo(recording)"
        :class="rowClass(recording.id)"
      >
      <td class="px-6 py-4 whitespace-nowrap">
        <div>{{ userStore.formatDateInUserTimezone(recording.start_time) }}</div>
        <div v-if="recording.comment" class="text-xs uppercase text-red-500 font-semibold">{{ recording.comment }}</div>
      </td>
      <td class="px-6 py-4 whitespace-nowrap">
        {{ userStore.formatTimeFromDateInUserTimezone(recording.start_time) }}
      </td>
      <td class="px-6 py-4 whitespace-nowrap">
        {{ userStore.formatTimeFromDateInUserTimezone(recording.end_time) }}
      </td>
      <td class="px-6 py-4 whitespace-nowrap">
        {{ formatDuration(recording.total_milliseconds_recorded) }}
      </td>
      <td class="px-6 py-4 whitespace-nowrap">
        <!-- Placeholder for expiration logic -->

      </td>
      <!-- We are not making recordings shareable at this time. -->
      <td class=" px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-1">
        <button @click.stop="shareRecording(recording.shareUrl)"
                class="hidden btn btn-xs bg-orange-200 hover:bg-orange-300 text-black">
          <font-awesome-icon icon="fa-share" class=""/>Share
        </button>
        <button @click.stop="confirmAddToEpisode"
                class="btn btn-xs">Add To Episode</button>
        <button @click.stop="confirmDownload(recording)"
        class="btn btn-xs btn-info" >Download</button>
        <button @click.stop="confirmSaveToPremium"
                class="btn btn-xs">Save to Premium Storage</button>
      </td>
    </tr>
    </tbody>

  </table>

  <dialog id="confirmRecordingPlaybackModal" class="modal">
    <div class="modal-box w-full items-center text-center">
      <form method="dialog">
        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
      </form>
      <h3 class="font-bold text-lg"></h3>
      <p class="py-4 text-xl mt-4">Would you like to play the recording<br /> from
        <span class="font-semibold">{{ selectedRecordingDate }}</span>?</p>
      <p class="my-2 text-left" v-if="selectedRecording && selectedRecording.path"><span class="font-semibold">Path: </span>{{ selectedRecording.path }}</p>
      <p class="my-2 text-left" v-if="selectedRecording && selectedRecording.comment"><span class="font-semibold">Comment: </span>{{ selectedRecording.comment }}</p>
      <button class="mt-4 btn" @click="play">Play</button>
    </div>

    <form method="dialog" class="modal-backdrop">
      <button>close</button>
    </form>
  </dialog>

  <transition name="fade">
    <div v-if="showCopyMessage" class="copy-message">
      {{ copyMessage }}
    </div>
  </transition>

  <dialog id="confirmAddToEpisodeModal" class="modal">
    <div class="modal-box text-center">
      <h3 class="font-bold text-lg pt-8">Add Recording To Episode</h3>
      <p class="py-4">We are working on this feature!</p>
      <div class="modal-action">
        <form method="dialog">
          <!-- if there is a button in form, it will close the modal -->
          <button class="btn">Okay</button>
        </form>
      </div>
    </div>
  </dialog>

  <dialog id="confirmDownloadModal" class="modal">
    <div class="modal-box text-center">
      <h3 class="font-bold text-lg pt-8">Confirm Download</h3>
      <p class="py-4">Are you sure you want to download the recording?</p>
      <div class="modal-action">
        <form method="dialog" class="w-full flex justify-center">
          <!-- if there is a button in form, it will close the modal -->
          <button @click="beginDownload" class="btn btn-info w-20">Yes</button>
        </form>
      </div>
    </div>
  </dialog>

  <dialog id="downloadStarted" class="modal">
    <div class="modal-box text-center">
      <h3 class="font-bold text-lg pt-8">Download Started</h3>
      <p class="py-4">Your recording is now downloading!</p>
      <div class="modal-action">
        <form method="dialog" class="w-full flex justify-center">
          <!-- if there is a button in form, it will close the modal -->
          <button class="btn">Okay</button>
        </form>
      </div>
    </div>
  </dialog>

  <dialog id="confirmSaveToPremiumModal" class="modal">
    <div class="modal-box text-center">
      <h3 class="font-bold text-lg pt-8">Save To Premium Storage</h3>
      <p class="py-4">We are working on this feature!</p>
      <div class="modal-action">
        <form method="dialog">
          <!-- if there is a button in form, it will close the modal -->
          <button class="btn">Okay</button>
        </form>
      </div>
    </div>
  </dialog>



</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useNowPlayingStore } from "@/Stores/NowPlayingStore"
import { useUserStore } from '@/Stores/UserStore'
import { computed, reactive, ref } from 'vue'
import { useClipboard } from '@vueuse/core'

const videoPlayerStore = useVideoPlayerStore()
const appSettingStore = useAppSettingStore()
const nowPlayingStore = useNowPlayingStore()
const userStore = useUserStore()

const shareClip = useClipboard();

const props = defineProps({
  showName: String,
  showImage: Object,
  showRecordings: Array
})

const formatDuration = (totalMilliseconds) => {
  let seconds = Math.floor(totalMilliseconds / 1000);
  let minutes = Math.floor(seconds / 60);
  const hours = Math.floor(minutes / 60);

  seconds = seconds % 60;
  minutes = minutes % 60;

  // Padding numbers to always show two digits
  const paddedHours = hours.toString().padStart(2, '0');
  const paddedMinutes = minutes.toString().padStart(2, '0');
  const paddedSeconds = seconds.toString().padStart(2, '0');

  return `${paddedHours}h ${paddedMinutes}m ${paddedSeconds}s`;
};

const state = reactive({
  hoveredRow: null,
});

const selectedRecording = ref(null)
const selectedRecordingDate = ref('')
const selectedRecordingStreamName = ref('')
const selectedRecordingId = ref('')
const nowPlayingRecording = ref(null)
const nowPlayingRecordingId = ref('')
const nowPlayingStreamName = ref('')

// Computed property returning a function to determine row classes
const rowClass = computed(() => (recordingId) => ({
  'hover:bg-blue-100 cursor-pointer': state.hoveredRow === recordingId,
  'bg-green-100': nowPlayingRecordingId.value === recordingId,
}));

const openVideo = (recording) => {
  document.getElementById('confirmRecordingPlaybackModal').showModal()
  selectedRecording.value = recording
  console.log(recording)
  console.log(selectedRecording.value)
  selectedRecordingStreamName.value = recording.streamName
  selectedRecordingId.value = recording.id
  selectedRecordingDate.value = userStore.formatDateInUserTimezone(recording.start_time)
  console.log('Opening video for stream:', recording.streamName);
  // Implement the video opening logic here
};

const source = ref({
  mediaType: '',
  recording: {
    source: '',
    sourceType: '',
  }
});

const play = () => {
  source.value.mediaType = 'recording'
  source.value.recording = {
    source: selectedRecordingStreamName,
    sourceType: 'video/mp4',
  };
  console.log('recording source: ' + selectedRecordingStreamName.value)
  try {
    videoPlayerStore.loadNewVideo(source.value)
    nowPlayingRecording.value = selectedRecording.value
    nowPlayingRecordingId.value = selectedRecordingId.value
    nowPlayingStreamName.value = selectedRecordingStreamName.value
    appSettingStore.toggleOttInfo()
    console.log('now playing ID: ' + nowPlayingRecordingId.value)
    const nowPlayingDate = userStore.formatDateInUserTimezone(nowPlayingRecording.value.start_time)
    const nowPlayingStartTime = userStore.formatTimeFromDateInUserTimezone(nowPlayingRecording.value.start_time)
    const secondaryName = nowPlayingDate + ' ' + nowPlayingStartTime + ' Recording'

    // Set common details specific to this video play event for nowPlayingStore
    const mediaType = source.value.mediaType
    const commonDetails = {
      primaryName: props.showName, //showName
      secondaryName: secondaryName, //date startTime Recording
      description: nowPlayingRecording?.value?.comment ?? null, // Comment
      primaryUrl: null,
      secondaryUrl: null,
      channelName: null,
      image: props?.showImage,
      team: null,
      creative_commons: null,
    }
    const videoDetails = {
      // Assuming video details are structured correctly in your episode data
      video_url: '',
      type: source.value.recording.sourceType, // MIME type for video.js
    }

    // Update the currently playing media in nowPlayingStore with the latest details
    nowPlayingStore.setActiveMedia(mediaType, {
      ...commonDetails,
      videoDetails, // Spread in the specific details for internal or external video
    })

    // Close the modal upon successful video playback
    document.getElementById('confirmRecordingPlaybackModal').close()
  } catch (error) {
    console.error('Error loading new video:', error);
    // Handle error appropriately, possibly with user feedback
  }

}

const shareRecording = (shareUrl) => {
  shareClip.copy(shareUrl);
  copyMessage.value = 'Video share URL copied!';
  showCopyMessage.value = true;
  setTimeout(() => {
    showCopyMessage.value = false;
  }, 1000); // Hide after 3 seconds
};

const showCopyMessage = ref(false);
const copyMessage = ref('');

const confirmAddToEpisode = () => {
  document.getElementById('confirmAddToEpisodeModal').showModal()
}

const confirmDownload = (recording) => {
  selectedRecording.value = recording
  // Confirm the download.
  document.getElementById('confirmDownloadModal').showModal();
}

const beginDownload = () => {
  // Once confirmed, execute the download.

  // The URL where your files are hosted
  const url = selectedRecording.value.download.url;

  console.log('download url: ' + url)
  // Create an anchor (<a>) element
  const downloadLink = document.createElement('a');
  downloadLink.href = url;

  // Optionally, if you want the download to have a specific filename:
  // downloadLink.download = 'YourCustomFileNameHere';

  // This is necessary for the download to work in Firefox when triggered programmatically
  document.body.appendChild(downloadLink);

  downloadLink.click();

  // Clean up by removing the element after triggering the download
  document.body.removeChild(downloadLink);
  selectedRecording.value = null
  document.getElementById('downloadStarted').showModal()
}

const confirmSaveToPremium = () => {
  document.getElementById('confirmSaveToPremiumModal').showModal()
}


// function triggerDownload(url, filename = "") {
//   const a = document.createElement('a');
//   a.href = url;
//   a.download = filename; // You can specify a filename here, or leave it empty
//   document.body.appendChild(a);
//   a.click();
//   document.body.removeChild(a);
// }

</script>
<style>
.copy-message {
  position: fixed;
  bottom: 20%;
  left: 50%;
  transform: translateX(-50%);
  background-color: rgba(0, 0, 0, 0.75);
  color: white;
  padding: 20px;
  border-radius: 10px;
  font-size: 1.5rem;
  z-index: 100;
  transition: opacity 0.5s ease;
}
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>
