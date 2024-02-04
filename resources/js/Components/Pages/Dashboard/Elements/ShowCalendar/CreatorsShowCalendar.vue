<template>
  <div class="container mx-auto px-4 py-8 bg-blue-200 rounded-lg mb-8">
    <h1 class="text-center font-bold pb-6 mb-6 border-b-2 border-gray-500">Show Schedule</h1>
    <!-- Define a grid with 1 row for headers and dynamic rows for time slots -->
    <div class="grid grid-cols-[auto_repeat(5,_minmax(0,_1fr))] gap-4">
      <!-- Empty corner cell -->
      <div></div>

      <!-- Day Headers -->
      <div v-for="day in days" :key="day" class="bg-blue-800 text-white p-4 rounded-lg font-bold text-center">{{ day }}</div>

      <!-- Time Slots & Shows -->
      <!-- For each time slot, create a row -->
      <template v-for="(time, timeIndex) in times" :key="`time-${timeIndex}`">
        <!-- Time Label -->
        <div class="bg-blue-100 p-4 rounded-lg text-center">{{ time }}</div>

        <!-- Shows for each day at this time -->
        <template v-for="(day, dayIndex) in days" :key="`show-${day}-${time}`"  >

            <!-- Simulate fetching a show for this day and time -->
          <div class="flex flex-col rounded">
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
      </template>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import dayjs from 'dayjs';
import CreatorsShowCalendarShowCard
  from '@/Components/Pages/Dashboard/Elements/ShowCalendar/CreatorsShowCalendarShowCard.vue' // Assume dayjs is installed for date manipulation

const now = dayjs();

// Format functions for display
const formatDate = (date) => dayjs(date).format('dddd, MMM D');
const formatHour = (date) => dayjs(date).format('dddd, h A');

// Reactive state to hold shows data
const showsState = ref([]);

// Asynchronous function to load the JSON file
async function loadScheduleShows() {
  try {
    const response = await import('@/Json/scheduleShow.json');
    showsState.value = response.default.shows; // Load shows from JSON
  } catch (error) {
    console.error("Failed to load schedule shows:", error);
  }
}

// Load the data when the component mounts
onMounted(loadScheduleShows);

const days = ref(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']);
const times = ref(['8 AM', '9 AM', '10 AM', '11 AM', '12 PM', '1 PM']);

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
