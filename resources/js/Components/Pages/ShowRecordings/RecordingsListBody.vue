<template>
  <tbody class="bg-white divide-y divide-gray-200">
  <tr v-for="recording in recordings" :key="recording.id"
      @mouseover="state.hoveredRow = recording.id"
      @mouseleave="state.hoveredRow = null"
      @click="selectRecording(recording)"
      :class="rowClass(recording.id)">
    <td class="px-6 py-4 whitespace-nowrap">
      <div>{{ recording?.meta?.title }}</div>
      <div>{{ recording.start_date_local }}</div>
      <div v-if="recording.comment" class="text-xs uppercase text-orange-700 font-semibold break-words">
        <span :class="{ 'text-indigo-600': recording.comment !== 'automated recording' }">{{ recording.comment }}</span>
      </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
      {{ recording.start_time_local }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
      {{ recording.end_time_local }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
      {{ formatDuration(recording.total_milliseconds_recorded) }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap"></td>
    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-1">
      <button @click.stop="shareRecording(recording.share_url)"
              class="btn btn-xs bg-orange-200 hover:bg-orange-300 text-black">
        <font-awesome-icon icon="fa-share" class=""/> Share
      </button>
      <button @click.stop="confirmAddToEpisode"
              class="btn btn-xs">Add To Episode</button>
      <button @click.stop="confirmDownload(recording)"
              class="btn btn-xs btn-info">Download</button>
      <div @click.stop="confirmSaveToPremium" class="btn btn-xs">Save to Premium Storage</div>
      <transition name="fade">
        <div v-if="state.showCopyMessage" class="copy-message">
          {{ state.copyMessage }}
        </div>
      </transition>
    </td>
  </tr>
  </tbody>
</template>

<script setup>
import { computed, reactive, ref } from 'vue'
import { useRecordingStore } from '@/Stores/RecordingStore';

const recordingStore = useRecordingStore();
const emit = defineEmits(['select-recording']);

const state = reactive({
  hoveredRow: null,
  showCopyMessage: false,
  copyMessage: '',
});

const recordings = computed(() => recordingStore.formattedRecordings);

const selectRecording = (recording) => {
  recordingStore.setSelectedRecording(recording);
  emit('select-recording');
};

const shareRecording = (shareUrl) => {
  navigator.clipboard.writeText(shareUrl).then(() => {
    state.copyMessage = 'Video share URL copied!';
    state.showCopyMessage = true;
    setTimeout(() => {
      state.showCopyMessage = false;
    }, 1000); // Hide after 1 second
  }).catch(err => {
    console.error('Failed to copy: ', err);
  });
};

const confirmAddToEpisode = () => {
  document.getElementById('confirmAddToEpisodeModal').showModal();
};

const confirmDownload = (recording) => {
  recordingStore.setSelectedRecording(recording);
  document.getElementById('confirmDownloadModal').showModal();
};

const confirmSaveToPremium = () => {
  document.getElementById('confirmSaveToPremiumModal').showModal();
};

const rowClass = computed(() => (recordingId) => ({
  'hover:bg-blue-100 cursor-pointer': state.hoveredRow === recordingId,
  'bg-gray-100': recordingStore.selectedRecording?.id === recordingId,
  'bg-green-100': recordingStore.nowPlayingRecordingId === recordingId,
}));

const formatDuration = (totalMilliseconds) => {
  return recordingStore.formatDuration(totalMilliseconds);
};
</script>

<style>
.copy-message {
  position: fixed;
  bottom: 50%;
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
