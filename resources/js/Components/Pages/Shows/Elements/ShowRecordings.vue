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
        {{ userStore.formatDateInUserTimezone(recording.start_time) }}
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
        <button @click.stop="confirmDownload"
        class="btn btn-xs" >Download</button>
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
      <p class="py-4">Would you like to play the <span class="font-semibold">recording</span> from
        <span class="font-semibold">{{ selectedRecordingDate }}</span>?</p>
      <button class="btn" @click="play">Play</button>
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
      <h3 class="font-bold text-lg pt-8">Download</h3>
      <p class="py-4">We are working on this feature!</p>
      <div class="modal-action">
        <form method="dialog">
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

const selectedRecordingDate = ref('')
const selectedRecordingStreamName = ref('')
const selectedRecordingId = ref('')
const nowPlayingRecordingId = ref('')

// Computed property returning a function to determine row classes
const rowClass = computed(() => (recordingId) => ({
  'hover:bg-blue-100 cursor-pointer': state.hoveredRow === recordingId,
  'bg-green-100': nowPlayingRecordingId.value === recordingId,
}));

const openVideo = (recording) => {
  document.getElementById('confirmRecordingPlaybackModal').showModal()
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
    nowPlayingRecordingId.value = selectedRecordingId.value
    appSettingStore.toggleOttInfo()
    console.log('now playing ID: ' + nowPlayingRecordingId.value)

  } catch (error) {
    console.error('Error loading new video:', error);
    // Handle the error appropriately
  }
  const mediaType = source.value.mediaType
  // Common details for nowPlayingStore
  const commonDetails = {
    primaryName: 'Recording', // Show or Movie name
    secondaryName: '', // Episode name
    description: selectedRecordingStreamName,
    primaryUrl: null,
    secondaryUrl: null,
    channelName: null,
    image: null,
    team: null,
    creative_commons: null,
  }
  const videoDetails = {
    // Assuming video details are structured correctly in your episode data
    video_url: '',
    type: source.value.recording.sourceType, // MIME type for video.js
  }
  // Set the currently playing media in nowPlayingStore
  nowPlayingStore.setActiveMedia(mediaType, {
    ...commonDetails,
    videoDetails, // Spread in the specific details for internal or external video
  })
  document.getElementById('confirmRecordingPlaybackModal').close()
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

const confirmDownload = () => {
  document.getElementById('confirmDownloadModal').showModal()
}

const confirmSaveToPremium = () => {
  document.getElementById('confirmSaveToPremiumModal').showModal()
}


function triggerDownload(url, filename = "") {
  const a = document.createElement('a');
  a.href = url;
  a.download = filename; // You can specify a filename here, or leave it empty
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
}

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
