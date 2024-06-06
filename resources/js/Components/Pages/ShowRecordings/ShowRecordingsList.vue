<template>
  <div>
    <div class="w-full bg-yellow-200 px-2 py-1">
      <span class="font-semibold uppercase">⚠️ NOTICE: </span> The first time you playback a recording it will take a
      long time to load. This only happens the first time!
    </div>
    <table class="min-w-full divide-y divide-gray-200">
      <RecordingsListHeader/>
      <RecordingsListBody @open-modal="openModal" @select-recording="scrollToRecordingDetails"/>
    </table>
    <div class="flex flex-row flex-wrap w-full justify-center mt-6">
      <RecordingsPaginator
          :totalPages="pagination.lastPage"
          :currentPage="pagination.currentPage"
          @update="handlePageChange"
      />
    </div>

    <div class="flex flex-col w-full justify-center mt-2" id="recordingDetailsContainer">
      <div v-if="selectedRecording">
        <SelectedRecordingMeta/>
      </div>
      <div v-else class="pl-6">
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
import { computed, nextTick, onMounted, ref } from 'vue'
import { useRecordingStore } from '@/Stores/RecordingStore'
import RecordingsListHeader from '@/Components/Pages/ShowRecordings/RecordingsListHeader.vue'
import RecordingsListBody from '@/Components/Pages/ShowRecordings/RecordingsListBody.vue'
import RecordingsPaginator from '@/Components/Pages/ShowRecordings/RecordingsPaginator.vue'
import ShowRecordingsModals from '@/Components/Pages/ShowRecordings/ShowRecordingsModals.vue'
import SelectedRecordingMeta from '@/Components/Pages/ShowRecordings/SelectedRecordingMeta.vue'

const recordingStore = useRecordingStore()

const handlePageChange = (page) => {
  recordingStore.fetchRecordings(page);
  recordingStore.clearSelectedRecording();
}

const pagination = computed(() => recordingStore.pagination)

const selectedRecording = computed(() => recordingStore.selectedRecording)
const nowPlayingRecordingId = computed(() => recordingStore.nowPlayingRecordingId)

const openModal = (modalId) => {
  document.getElementById(modalId).showModal()
}

const beginDownload = () => {
  recordingStore.downloadRecording()
  document.getElementById('downloadStarted').showModal()
}

const play = () => {
  recordingStore.openVideo()
}

const scrollToRecordingDetails = () => {
  nextTick(() => {
    requestAnimationFrame(() => {
      const topDiv = document.getElementById('recordingDetailsContainer');
      if (topDiv) {
        topDiv.scrollIntoView({ behavior: 'smooth' });
      } else {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }
    });
  });
}

onMounted(() => {
  recordingStore.reset()
  recordingStore.fetchRecordings()
})
</script>
