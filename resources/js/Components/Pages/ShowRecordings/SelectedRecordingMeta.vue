<template>
  <div>
    <div v-if="recordingStore.isLoading" class="flex w-fit mx-auto justify-center my-8">
      <span class="loading loading-spinner text-info text-lg"></span>
    </div>
    <div v-else>
      <div class="p-4 max-w-4xl mx-auto bg-white rounded-lg shadow-md">
        <div class="flex flex-col lg:flex-row lg:space-x-4 mt-2">
          <form @submit.prevent="updateRecording" class="space-y-4 flex-1">
            <div>
              <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
              <input id="title" v-model="meta.title" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" />
            </div>
            <div>
              <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
              <TabbableTextarea v-model="meta.notes"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
                                name="notes"
                                id="notes"
                                rows="10" cols="30"
              />
            </div>
            <div class="flex flex-col justify-start items-start">
              <div class="form-control">
                <label class="cursor-pointer label">
                  <input type="checkbox" v-model="meta.good" class="checkbox checkbox-info text-md"/>
                  <span class="label-text ml-2 block text-sm font-medium text-gray-700">Good</span>
                </label>
              </div>
              <div class="form-control">
                <label class="cursor-pointer label">
                  <input type="checkbox" v-model="meta.ng" class="checkbox checkbox-info text-md"/>
                  <span class="label-text ml-2 block text-sm font-medium text-gray-700">NG</span>
                </label>
              </div>
            </div>
            <div>
              <label for="updated_by" class="block text-sm font-medium text-gray-700">Last Updated By</label>
              <input id="updated_by" v-model="meta.updated_by" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" readonly />
            </div>
            <div>
              <label for="updated_at" class="block text-sm font-medium text-gray-700">Last Updated At</label>
              <input id="updated_at" v-model="meta.updated_at" type="datetime-local" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" readonly />
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">Save</button>
          </form>
          <div class="mt-4 lg:mt-0 lg:ml-4 max-w-lg bg-gray-50 p-4 rounded-lg shadow-sm flex-1">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-lg font-semibold">Recording Details</h3>
              <button @click.prevent="playRecording" class="py-2 px-4 bg-green-600 text-white font-semibold rounded-md shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">Play</button>
            </div>
            <div v-if="selectedRecording">
              <p><strong>Comment:</strong> {{ selectedRecording.comment }}</p>
              <p><strong>Stream Name:</strong> {{ selectedRecording.stream_name }}</p>
              <p><strong>Path:</strong> {{ selectedRecording.path }}</p>
              <p><strong>File Extension:</strong> {{ selectedRecording.file_extension }}</p>
              <p><strong>Mime Type:</strong> {{ selectedRecording.mime_type }}</p>
              <p><strong>Start DateTime:</strong> {{ selectedRecording.start_dateTime }}</p>
              <p><strong>End DateTime:</strong> {{ selectedRecording.end_dateTime }}</p>
              <p><strong>Bytes Recorded:</strong> {{ selectedRecording.bytes_recorded }}</p>
              <p><strong>Seconds Spent Recording:</strong> {{ selectedRecording.seconds_spent_recording }}</p>
              <p><strong>Total Milliseconds Recorded:</strong> {{ selectedRecording.total_milliseconds_recorded }}</p>
              <p><strong>Milliseconds First Packet:</strong> {{ selectedRecording.milliseconds_first_packet }}</p>
              <p><strong>Milliseconds Last Packet:</strong> {{ selectedRecording.milliseconds_last_packet }}</p>
              <p><strong>Reason for Exit:</strong> {{ selectedRecording.reason_for_exit }}</p>
              <p><strong>Human Readable Reason for Exit:</strong> {{ selectedRecording.human_readable_reason_for_exit }}</p>
              <p><strong>Mist Stream Wildcard ID:</strong> {{ selectedRecording.mist_stream_wildcard_id }}</p>
              <p><strong>Model Type:</strong> {{ selectedRecording.model_type }}</p>
              <p><strong>Model ID:</strong> {{ selectedRecording.model_id }}</p>
              <p class="break-all"><strong>Download URL:</strong> <a :href="selectedRecording.download_url" target="_blank">{{ selectedRecording.download_url }}</a></p>
              <p class="break-all"><strong>Share URL:</strong> <a :href="selectedRecording.share_url" target="_blank">{{ selectedRecording.share_url }}</a></p>
              <p class="break-all"><strong>Playback Stream Name:</strong> {{ selectedRecording.playback_stream_name }}</p>
              <p><strong>Expires At:</strong> {{ selectedRecording.expires_at }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useRecordingStore } from '@/Stores/RecordingStore';
import { useUserStore } from '@/Stores/UserStore';
import TabbableTextarea from '@/Components/Global/TextEditor/TabbableTextarea.vue';

const recordingStore = useRecordingStore();
const userStore = useUserStore();

const selectedRecording = ref(null);
const meta = ref({
  title: '',
  notes: '',
  updated_by: '',
  updated_at: '',
  good: false,
  ng: false,
});

const updateRecording = async () => {
  await recordingStore.updateRecording(meta.value);
};

const playRecording = () => {
  document.getElementById('confirmRecordingPlaybackModal').showModal();
};

// Watch for changes in the selected recording
watch(() => recordingStore.selectedRecording, (newRecording) => {
  if (newRecording) {
    selectedRecording.value = newRecording;
    meta.value = {...newRecording.meta};
    if (meta.value.updated_at) {
      // Format updated_at with user timezone and format string
      meta.value.updated_at = userStore.formatDateInUserTimezone(meta.value.updated_at, "YYYY-MM-DDTHH:mm");
    }
  }
}, { immediate: true });
</script>
