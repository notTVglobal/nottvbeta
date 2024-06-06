<template>
  <div>
    <dialog id="confirmRecordingPlaybackModal" class="modal">
      <div class="modal-box w-full items-center text-center text-black bg-white dark:bg-gray-800 dark:text-white">
        <form method="dialog">
          <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg"></h3>
        <p class="py-4 text-xl mt-4">Would you like to play the recording<br/> from
          <span class="font-semibold">{{ selectedRecording?.start_date_local }}</span>?</p>
        <p class="my-2 text-left" v-if="selectedRecording && selectedRecording.path"><span
            class="font-semibold">Path: </span>{{ selectedRecording.path }}</p>
        <p class="my-2 text-left" v-if="selectedRecording && selectedRecording.comment">
          <span class="font-semibold">Comment: </span>
          <span>{{ selectedRecording.comment }}</span>
        </p>
        <button class="mt-4 btn" @click="play">Play</button>
      </div>

      <form method="dialog" class="modal-backdrop">
        <button>close</button>
      </form>
    </dialog>

    <dialog id="confirmAddToEpisodeModal" class="modal">
      <div class="modal-box text-center text-black bg-white dark:bg-gray-800 dark:text-white">
        <form method="dialog">
          <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg pt-8">Add Recording To Episode</h3>
        <p class="py-4">We are working on this feature!</p>
        <div class="modal-action">
          <form method="dialog">
            <button class="btn">Okay</button>
          </form>
        </div>
      </div>
    </dialog>

    <dialog id="confirmDownloadModal" class="modal">
      <div class="modal-box text-center text-black bg-white dark:bg-gray-800 dark:text-white">
        <form method="dialog">
          <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg pt-8">Confirm Download</h3>
        <p class="py-4">Are you sure you want to download the recording?</p>
        <div class="modal-action">
          <form method="dialog" class="w-full flex justify-center">
            <button @click="beginDownload" class="btn btn-info w-20">Yes</button>
          </form>
        </div>
      </div>
    </dialog>

    <dialog id="downloadStarted" class="modal">
      <div class="modal-box text-center text-black bg-white dark:bg-gray-800 dark:text-white">
        <form method="dialog">
          <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg pt-8">Download Started</h3>
        <p class="py-4">Your recording is now downloading!</p>
        <div class="modal-action">
          <form method="dialog" class="w-full flex justify-center">
            <button class="btn">Okay</button>
          </form>
        </div>
      </div>
    </dialog>

    <dialog id="confirmSaveToPremiumModal" class="modal">
      <div class="modal-box text-center text-black bg-white dark:bg-gray-800 dark:text-white">
        <form method="dialog">
          <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg pt-8">Save To Premium Storage</h3>
        <p class="py-4">We are working on this feature!</p>
        <div class="modal-action">
          <form method="dialog">
            <button class="btn">Okay</button>
          </form>
        </div>
      </div>
    </dialog>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRecordingStore } from '@/Stores/RecordingStore';

const recordingStore = useRecordingStore();

const selectedRecording = computed(() => recordingStore.selectedRecording);

const beginDownload = () => {
  recordingStore.downloadRecording();
};

const play = () => {
  recordingStore.openVideo();
  document.getElementById('confirmRecordingPlaybackModal').close()
};
</script>
