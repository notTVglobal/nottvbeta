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
    <tr v-for="recording in showRecordings" :key="recording.id">
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
      <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-1">
        <button class="btn btn-xs">Add To Episode</button>
        <button class="btn btn-xs">Download</button>
        <button class="btn btn-xs">Save to Premium Storage</button>
      </td>
    </tr>
    </tbody>

  </table>

</template>

<script setup>
import { useUserStore } from '@/Stores/UserStore'

const userStore = useUserStore()

defineProps({
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

</script>
