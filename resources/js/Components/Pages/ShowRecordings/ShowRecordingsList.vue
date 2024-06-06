<template>
  <div>
    <div class="w-full bg-yellow-200 px-2 py-1">
      <span class="font-semibold uppercase">⚠️ NOTICE: </span> The first time you playback a recording it will take a long time to load. This only happens the first time!
    </div>
    <table class="min-w-full divide-y divide-gray-200">
      <RecordingsListHeader />
      <RecordingsListBody @open-modal="openModal" />
    </table>
    <div class="flex flex-row flex-wrap w-full justify-center">
      <RecordingsPaginator
          :totalPages="pagination.lastPage"
          :currentPage="pagination.currentPage"
          @update="handlePageChange"
      />
    </div>
    <div class="flex flex-col w-full justify-center mt-2">
      <div v-if="recordingStore.selectedRecording">
        <div class="flex">
          <span class="font-bold w-48">Path:</span>
          <span>{{ recordingStore.selectedRecording.path }}</span>
        </div>
        <div class="flex">
          <span class="font-bold w-48">Share URL:</span>
          <span>{{ recordingStore.selectedRecording.share_url }}</span>
        </div>
        <div class="flex">
          <span class="font-bold w-48">Download URL:</span>
          <span>{{ recordingStore.selectedRecording.download_url }}</span>
        </div>
        <div class="flex">
          <span class="font-bold w-48">Playback Stream Name:</span>
          <span>{{ recordingStore.selectedRecording.playback_stream_name }}</span>
        </div>
        <div class="flex">
          <span class="font-bold w-48">Input:</span>
          <input class="w-full"></input>
        </div>
      </div>
      <div v-else>
        <span>No recording selected.</span>
      </div>
    </div>
    <ShowRecordingsModals
        :selectedRecording="selectedRecording"
        :nowPlayingRecordingId="nowPlayingRecordingId"
        @beginDownload="beginDownload"
        @play="play"
    />
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useRecordingStore } from '@/Stores/RecordingStore';
import RecordingsListHeader from '@/Components/Pages/ShowRecordings/RecordingsListHeader.vue';
import RecordingsListBody from '@/Components/Pages/ShowRecordings/RecordingsListBody.vue';
import RecordingsPaginator from '@/Components/Pages/ShowRecordings/RecordingsPaginator.vue';
import ShowRecordingsModals from '@/Components/Pages/ShowRecordings/ShowRecordingsModals.vue';

const recordingStore = useRecordingStore();

const handlePageChange = (page) => {
  recordingStore.fetchRecordings(page);
};

const pagination = computed(() => recordingStore.pagination);

const selectedRecording = computed(() => recordingStore.selectedRecording);
const nowPlayingRecordingId = computed(() => recordingStore.nowPlayingRecordingId);

const openModal = (modalId) => {
  document.getElementById(modalId).showModal();
};

const beginDownload = () => {
  recordingStore.downloadRecording();
  document.getElementById('downloadStarted').showModal();
};

const play = () => {
  recordingStore.openVideo();
};

onMounted(() => {
  recordingStore.fetchRecordings();
});
</script>
