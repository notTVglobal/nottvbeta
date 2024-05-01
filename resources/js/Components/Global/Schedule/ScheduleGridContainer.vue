<template>

<!--  We need the CurrenTime component to keep our ScheduleStore currentTime up to date
      it has a SetInterval in it. -->
  <CurrentTime />

  <div class="mb-2 tracking-wide">
    <span class="text-sm uppercase text-purple-500">All times are listed in your timezone.</span>
  </div>

  <div class="schedule-grid">
    <div class="header-row">
      <!-- Time slots header -->
      <div class="time-cell bg-gray-900 px-3 py-2 text-center font-bold"
           v-for="interval in nextFourHoursWithHalfHourIntervals" :key="interval.dateTime">
        {{ interval.formatted }}
      </div>
    </div>
  </div>
  <div class="schedule-grid">
    <div v-if="scheduleStore.scheduleIsLoading && scheduleStore.nextFourHoursOfContent.length === 0"
         class="w-full flex justify-center text-center items-center">
      <span class="loading loading-ball loading-xl text-info"></span>
    </div>

    <div class="content-row">
      <!-- Scheduled shows -->
      <template v-for="item in nextFourHoursOfContent" :key="item.id">
        <div
            class="show-cell flex flex-col w-full h-full"
            :class="getCellClasses(item.type)"
            :style="gridPlacement(item.start_time, item.durationMinutes)"
            @click="handleShowClick(item)"
        >
          <div class="item-content flex flex-col items-center justify-center gap-y-2 flex-grow">
            <h3>{{ item.content.show?.name || 'No Show Name' }}</h3>
            <!-- Display the image if available -->
            <SingleImage v-if="item.content.show?.image"
                         :image="item.content.show?.image"
                         :alt="item.content.show?.name"
                         class="content-image"/>
            <!-- Fallback placeholder if no image -->
            <div v-else class="placeholder"></div>
          </div>
        </div>
      </template>
    </div>


    <!-- Status Row -->
    <div class="status-row">
      <!-- Status for each show -->
      <template v-for="(item, index) in nextFourHoursOfContent" :key="`status-${item.id}`">
        <div
            :class="getStatusCellClasses(index)" :style="gridPlacement(item.start_time, item.durationMinutes)"
        >
          <!-- Use the index to determine the status -->
          <span v-if="index === 0">NOW PLAYING</span>
          <span v-else-if="index === 1">COMING UP NEXT</span>

        </div>
      </template>
    </div>

  </div>

</template>
<script setup>
import { computed } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import dayjs from 'dayjs'
import { useScheduleStore } from '@/Stores/ScheduleStore'
import ScheduleGrid from '@/Components/Pages/Schedule/ScheduleGrid.vue'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'
import CurrentTime from '@/Components/Global/Schedule/CurrentTime.vue'

const scheduleStore = useScheduleStore()

// Computed property to ensure reactivity
const nextFourHours = computed(() => scheduleStore.nextFourHoursWithHalfHourIntervals)
const nextFourHoursOfContent = computed(() => scheduleStore.nextFourHoursOfContent)
const nextFourHoursWithHalfHourIntervals = computed(() => scheduleStore.nextFourHoursWithHalfHourIntervals)

const gridPlacement = (startTime, durationMinutes) => {
  // Convert startTime to a comparable format if necessary
  const startDateTime = new Date(startTime)
  const timeSlots = nextFourHoursWithHalfHourIntervals.value.map(interval => new Date(interval.dateTimeString))

  // Find the index of the time slot that matches the item's start time
  let startColumn = timeSlots.findIndex(slot => startDateTime >= slot && startDateTime < new Date(slot.getTime() + 30 * 60000))
  if (startColumn === -1) {
    console.error('Start time does not match any interval:', startTime)
    return {} // Fallback or error handling
  }

  // Adjust startColumn for 1-based indexing in CSS Grid
  startColumn += 1

  // Calculate span based on duration
  const span = Math.ceil(durationMinutes / 30)

  // Return CSS style object for grid placement
  return {
    'gridColumn': `${startColumn} / span ${span}`,
  }
}

// Determines the classes for a status cell
const getStatusCellClasses = (index) => {
  const classes = ['status-cell']
  if (index === 0) classes.push('now-playing')
  else if (index === 1) classes.push('coming-up-next')
  else classes.push('status-cell-empty') // For cells without specific content
  return classes
}


function getCellClasses(type) {
  const baseClass = 'column-width text-sm 2xl:text-md border border-0.5 border-green-300 hover:border-blue-500 cursor-pointer'
  switch (type) {
    case 'show':
      return `${baseClass} bg-gradient-show hover:bg-gradient-show-hover`
    case 'new_release':
      return `${baseClass} bg-gradient-new-release hover:bg-gradient-new-release-hover`
      // Add more cases as needed
    default:
      return baseClass
  }
}

function handleShowClick(item) {
  if (isNowPlaying(item.start_time, item.durationMinutes)) {
    // Redirect to the show's page if it's currently playing
    Inertia.visit(`/shows/${item.content.show.slug}/`)
  } else {
    // Open the reminder modal for shows that are not currently playing
    openModal('getReminderModal')
  }
}

// Checks if a given show is currently playing
function isNowPlaying(showStartTime, durationMinutes) {
  const now = dayjs()
  const startTime = dayjs(showStartTime)
  const endTime = startTime.add(durationMinutes, 'minute')
  console.log('ShowStartTime Time: ' + showStartTime)
  console.log('Duration Minutes: ' + durationMinutes)
  console.log('Now: ' + now)
  console.log('Start Time: ' + startTime)
  console.log('End Time: ' + endTime)
  return now.isAfter(startTime) && now.isBefore(endTime)
}


function openModal(modalName) {
  document.getElementById(modalName).showModal()
}
</script>

<style scoped>

.bg-gradient-show {
  background: linear-gradient(to right, #1f4037, #99f2c8); /* Example green gradient */
}

.bg-gradient-show-hover:hover {
  background: linear-gradient(to right, #66D3FA, #6E45E2); /* Example green gradient on hover */
}

.bg-gradient-new-release {
  background: linear-gradient(to right, #654ea3, #eaafc8); /* Example purple gradient */
}

.bg-gradient-new-release-hover:hover {
  background: linear-gradient(to right, #c2e59c, #64b3f4); /* Example purple gradient on hover */
}


.column-width {
  @apply w-16
}


.schedule-item {
  background: #f0f0f0;
  color: #000;
  padding: 10px;
  display: flex;
  flex-direction: column;
  gap: 10px;
  align-items: center;
}

.time-slot {
  text-align: center;
  padding: 10px 0;
  border-bottom: 1px solid #fff;
  grid-row: 1; /* Ensures all time slots are in the first row */
}

.time-cell {
  border: 1px solid #fff;
  text-align: center; /* Center text if desired */
}

.content {
  background: #f0f0f0;
  padding: 8px;
  border: 1px solid #ddd;
}

.placeholder {
  background: #ccc;
  width: 100%;
  height: 60px;
}


.schedule-grid {
  display: grid;
  grid-template-columns: repeat(8, 1fr); /* Keep this as it correctly sets up the columns */
  width: 100%;

}

.header-row {
  display: contents; /* This makes the header-row itself not generate a box, allowing .time-cell to be direct children of .schedule-grid */
}

.content-row {
  grid-column: 1 / -1; /* Stretch across all columns */
  display: grid;
  grid-template-columns: repeat(8, 1fr); /* This might be redundant if the parent grid already specifies column structure */
}

.schedule-cell {
  background: #333;
  color: #fff;
  text-align: center;
  padding: 8px;
}

.item-content {
  padding: 8px;
  background: linear-gradient(to right, rgba(68, 68, 68, 0.9), rgba(68, 68, 68, 0.7));
}

.item-content:hover {
  background: linear-gradient(to right, #06beb6, #48b1bf);
  /* Adjust the colors above to match your desired hover effect */
}

.content-image {
  width: 100%;
  height: auto;
  object-fit: cover;
}

.status-row {
  grid-column: 1 / -1; /* Stretch across all columns */
  grid-row: 3; /* If you have a status row, assign it to a new grid row */
  width: 100%; /* Ensure the row takes the full width of its container */
  text-align: center;
  font-size: 1rem; /* Adjust as needed */
  color: white; /* Adjust as needed */
  padding: 10px 0; /* Adjust as needed */
  background-color: rgba(0, 0, 0, 0.7); /* Adjust as needed for visibility */
}

.status-cell {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 8px;
  color: white;
  width: 100%;
  height: 100%;
  font-weight: bold;
  opacity: 0.8;
  transition: background-color 0.3s ease;
}

.status-cell span {
  display: block;
  padding: 4px 8px;
  border-radius: 4px;
  background-color: black;
}

/* Optional: If you want the empty cells to have a slight indication they are there */
.status-cell:empty::after {
  content: "";
  display: block;
  width: 100%;
  height: 100%;
  background: none; /* Adjust this to a very subtle color or keep transparent */
}

.now-playing {
  background-color: #4CAF50; /* Green for now playing */
  animation: pulseAnimation 2s infinite;
}

.coming-up-next {
  background-color: #FF9800; /* Orange for coming up next */
}


@keyframes pulseAnimation {
  0% {
    opacity: 0.75;
  }
  50% {
    opacity: 1;
  }
  100% {
    opacity: 0.75;
  }
}

/* Responsive visibility */
@media (min-width: 1280px) {
  /* 2xl */
  .xl\:hidden {
    display: none;
  }
}

@media (min-width: 1024px) {
  /* xl */
  .lg\:hidden {
    display: none;
  }
}

</style>