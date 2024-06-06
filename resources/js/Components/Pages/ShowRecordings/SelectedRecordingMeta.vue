<template>
  <div class="p-4 max-w-md mx-auto bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-4">{{ selectedRecording?.title }}</h2>
    <form @submit.prevent="updateRecording" class="space-y-4">
      <div>
        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
        <textarea id="notes" v-model="meta.notes" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"></textarea>
      </div>
      <div>
        <label for="updated_by" class="block text-sm font-medium text-gray-700">Updated By</label>
        <input id="updated_by" v-model="meta.updated_by" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" />
      </div>
      <div>
        <label for="updated_at" class="block text-sm font-medium text-gray-700">Updated At</label>
        <input id="updated_at" type="datetime-local" v-model="meta.updated_at" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" />
      </div>
      <div class="flex items-center">
        <input id="good" type="checkbox" v-model="meta.good" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" />
        <label for="good" class="ml-2 block text-sm font-medium text-gray-700">Good</label>
      </div>
      <div class="flex items-center">
        <input id="ng" type="checkbox" v-model="meta.ng" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" />
        <label for="ng" class="ml-2 block text-sm font-medium text-gray-700">NG</label>
      </div>
      <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">Save</button>
    </form>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useRecordingStore } from '@/Stores/RecordingStore';

const recordingStore = useRecordingStore();
const selectedRecording = ref(null);
const meta = ref({
  notes: '',
  updated_by: '',
  updated_at: '',
  good: false,
  ng: false,
});

const updateRecording = async () => {
  await recordingStore.updateRecording(selectedRecording.value.id, meta.value);
};

// Watch for changes in the selected recording
watch(
    () => recordingStore.selectedRecording,
    (newRecording) => {
      if (newRecording) {
        selectedRecording.value = newRecording;
        meta.value = { ...newRecording.meta };
      }
    },
    { immediate: true }
);
</script>
