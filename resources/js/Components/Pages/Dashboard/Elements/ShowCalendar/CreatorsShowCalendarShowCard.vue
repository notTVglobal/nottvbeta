<template>
  <div :key="`show-${day}-${time}`" class="flex flex-col rounded">
  <div v-if="randomShow(day, time)" class="bg-blue-500 hover:bg-indigo-500 hover:cursor-pointer text-white h-full flex flex-col p-2 rounded-lg shadow transition ease-in-out duration-150" :class="{ 'bg-red-500': isNowPlaying(dayIndex, timeIndex), 'bg-blue-100': !isNowPlaying(dayIndex, timeIndex) }">

    <!-- Show details -->
    <div class="flex-grow">
      <div class="text-orange-500 text-xs font-semibold uppercase tracking-wider">{{ randomShow(day, time).category }}</div>
      <div class="text-yellow-500 text-xs font-semibold uppercase tracking-wider mb-2">{{ randomShow(day, time).category }}</div>

      <div class="font-semibold">{{ randomShow(day, time).name }}</div>

    </div>

    <img :src="randomShow(day, time).image" alt="Show Image" class="w-full self-end rounded my-2 hover:opacity-75 transition ease-in-out duration-150">
    <!-- Conditionally display "Now Playing" for the first item -->
    <div v-if="isNowPlaying(dayIndex, timeIndex)" class="text-lg font-bold text-yellow-500">Now Playing</div>
    <div v-else class="mt-2 text-xs font-thin">{{ formatHour(randomShow(day, time).scheduled_release_dateTime) }}</div>
  </div>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue';
import dayjs from 'dayjs';
const props = defineProps({
  day: String,
  time: String,
  dayIndex: Number,
  timeIndex: Number,
})

const now = dayjs();
// Format functions for display
const formatDate = (date) => dayjs(date).format('dddd, MMM D');
const formatHour = (date) => dayjs(date).format('dddd, h A');

// Reactive state to hold shows data
const showsState = ref([]);

// Determine if the current slot is for "Now Playing"
function isNowPlaying(dayIndex, timeIndex) {
  // Assuming the first time slot of the first day is "Now Playing"
  return dayIndex === 0 && timeIndex === 0;
}

// Function to fetch a random show for demonstration
// In a real application, this would be replaced with logic to filter shows based on day and time
function randomShow(day, time) {
  if (showsState.value.length > 0) {
    const randomIndex = Math.floor(Math.random() * showsState.value.length);
    return showsState.value[randomIndex];
  }
  return null;
}
</script>