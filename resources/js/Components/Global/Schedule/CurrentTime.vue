<template>
  <div class="tracking-wide">
    <p>CURRENT TIME: {{ scheduleStore.currentTime }} {{userStore.timezoneAbbreviation}}</p>
    <p v-if="userStore.isAdmin" >CURRENT TIME (for testing): <input type="time" v-model="formattedTime" @input="handleTimeInput"
                                          class="text-black"></p></div>
</template>

<script setup>
import { computed, onMounted, onUnmounted, ref, watch } from 'vue'
import { useUserStore } from '@/Stores/UserStore'
import { useScheduleStore } from '@/Stores/ScheduleStore'
import dayjs from 'dayjs';
import utc from 'dayjs/plugin/utc';  // for UTC support
import timezone from 'dayjs/plugin/timezone';  // for timezone support

const userStore = useUserStore()
const scheduleStore = useScheduleStore()

dayjs.extend(utc);
dayjs.extend(timezone);

// const baseTime = ref(new Date());
// const currentTime = ref(baseTime.value.toISOString().substring(11, 16));
let intervalId = null
let initialUpdateDone = false;  // Flag to track if the first update has been done

// const updateShows = () => {
//   const [hour, minute] = currentTime.value.split(':');
//   baseTime.value.setHours(parseInt(hour), parseInt(minute), 0, 0);
//   // You might want to force a re-render or re-compute of visible shows here
// };

// Function to update current time
function updateCurrentTime() {
  const timezone = userStore.timezone || 'UTC';  // Default to 'UTC' if no timezone is set

  // Use dayjs to handle the timezone conversion
  const currentTime = dayjs().tz(timezone).format('YYYY-MM-DD HH:mm:ss');

  scheduleStore.setBaseTime(currentTime);
  console.log("Time updated:", currentTime);
}

// Function to start auto-update interval
function startAutoUpdateTime(updateImmediately = true) {
  clearInterval(intervalId);  // Clear any existing interval first
  if (updateImmediately && !initialUpdateDone) {
    updateCurrentTime();  // Optionally update time immediately
    initialUpdateDone = true;  // Set the flag after the first update
  }
  intervalId = setInterval(() => {
    updateCurrentTime();  // Continue updating every minute
  }, 60000);
  console.log('Interval started, ID:', intervalId);
}

// Function to clear the interval
function stopAutoUpdateTime() {
  if (intervalId) {
    clearInterval(intervalId);
    console.log('Interval stopped, ID:', intervalId);
    intervalId = null;  // Reset the interval ID
  }
}

onMounted(() => {
  // Calculate milliseconds until the next minute to align updates
  const now = new Date();
  const msUntilNextMinute = (60 - now.getSeconds()) * 1000 - now.getMilliseconds();

  // Delay the start of regular updates to align with the start of the next minute
  setTimeout(() => {
    startAutoUpdateTime();  // Start regular updates at the next minute
  }, msUntilNextMinute);
});

onUnmounted(() => {
  stopAutoUpdateTime()
})

const formattedTime = computed({
  get() {
    const hours = scheduleStore.baseTime.getHours().toString().padStart(2, '0')
    const minutes = scheduleStore.baseTime.getMinutes().toString().padStart(2, '0')
    return `${hours}:${minutes}`
  },
  set(value) {
    const [hours, minutes] = value.split(':').map(Number)
    const newTime = new Date(scheduleStore.baseTime)
    newTime.setHours(hours, minutes)
    scheduleStore.setBaseTime(newTime)
  },
})

function handleTimeInput(event) {
  stopAutoUpdateTime()  // Properly stop the updating when manual input is provided
}


</script>