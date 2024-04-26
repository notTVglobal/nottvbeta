<template>
  <Head title="Schedule"/>

  <div id="topDiv" class="place-self-center flex flex-col overscroll-x-none pb-64 h-screen">
    <div class="min-h-screen w-full justify-end px-5 bg-gray-900 text-gray-50 mt-16 overflow-y-scroll">

      <PublicNavigationMenu/>
      <PublicResponsiveNavigationMenu/>
      <div class="container mx-auto px-4 gap-y-3 rounded sm:rounded-lg shadow">

        <header class="flex justify-end">
  <div class="relative w-32 h-32 mr-8">
    <div class="absolute top-3 left-0 w-full h-full flex justify-center items-center z-20"><h1
        class="text-4xl font-bold text-white bg-black bg-opacity-80 px-4 py-1 text-center">Broadcast<br/>Schedule
    </h1></div>
    <div class="absolute top-3 left-0 w-full h-full flex justify-center items-center z-10"><img
        src="/storage/images/Ping.png" alt="notTV Ping"/></div>

</div>
        </header>



      <div class="ml-5 px-2 mb-2">
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
      <ScheduleGrid/>


      <div class="schedule-grid">
        <div class="header-row">
          <!-- Time slots header -->
          <div class="time-cell bg-gray-900 px-3 py-2 text-center font-bold"
               v-for="interval in nextFourHoursWithHalfHourIntervals" :key="interval.dateTime">
            {{ interval.formatted }}
          </div>
        </div>

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

      <PopUpModal :id="`goToNowPlayingModal`">
        <template v-slot:header>Now Playing</template>
        <template v-slot:main><span class="text-orange-500">This modal is temporary. This will take you to the now playing show or episode page.</span>
        </template>
      </PopUpModal>
      <PopUpModal :id="`getReminderModal`">
        <template v-slot:header>Set Reminder</template>
        <template v-slot:main><span class="text-orange-500">This modal is temporary. Set a reminder when this show starts and/or subscribe to the show to get all notifications when new episodes are released or the show goes live. <br/><br/><span
            class="font-semibold text-yellow-600">NOTE: Monthly and Yearly contributors get first access to new features.</span></span>
        </template>
      </PopUpModal>

      <div class="bg-gray-600 rounded-lg shadow m-10 p-4">


        <TodayView/>


      </div>
      </div>
      <Footer/>
    </div>
  </div>
</template>

<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useUserStore } from '@/Stores/UserStore'
import { useScheduleStore } from '@/Stores/ScheduleStore'
import Message from '@/Components/Global/Modals/Messages'
import PopUpModal from '@/Components/Global/Modals/PopUpModal'
import TodayView from '@/Components/Global/Calendar/TodayView.vue'
import { computed, onBeforeMount, onMounted } from 'vue'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'
import dayjs from 'dayjs'
import { Inertia } from '@inertiajs/inertia'
import ScheduleGrid from '@/Components/Pages/Schedule/ScheduleGrid.vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import Footer from '@/Components/Global/Layout/Footer.vue'

const appSettingStore = useAppSettingStore()
const scheduleStore = useScheduleStore()
const userStore = useUserStore()
const videoPlayerStore = useVideoPlayerStore()

let props = defineProps({})

appSettingStore.currentPage = `schedule`
appSettingStore.setPrevUrl()



// Function to handle scrolling
const scrollToTop = () => {
  requestAnimationFrame(() => {
    const topDiv = document.getElementById('topDiv')
    if (topDiv) {
      // Smooth scroll to the 'topDiv' element
      topDiv.scrollIntoView({behavior: 'smooth'})
    } else {
      // Fallback: smooth scroll to the top of the page
      window.scrollTo({top: 0, behavior: 'smooth'})
    }
  })
}
scrollToTop() // Optionally scroll to top when the component mounts

onMounted(() => {
  if (videoPlayerStore.player) {
    console.log('player is initialized...')
    console.log('disposing player...')
    setTimeout(() => {
      videoPlayerStore.disposePlayer()
    }, 1000) // Delay the disposal by 1000 milliseconds (1 second)
  }
})


// Computed property to ensure reactivity
const nextFourHours = computed(() => scheduleStore.nextFourHoursWithHalfHourIntervals)
const nextFourHoursOfContent = computed(() => scheduleStore.nextFourHoursOfContent)
const nextFourHoursWithHalfHourIntervals = computed(() => scheduleStore.nextFourHoursWithHalfHourIntervals)
const timeIntervals = computed(() => scheduleStore.nextFourHoursWithHalfHourIntervals)
const shows = computed(() => scheduleStore.nextFourHoursOfContent)

// Determine the "Now Playing" and "Coming Up Next" statuses
const now = dayjs()
let nowPlayingIndex = -1
let comingUpNextIndex = -1

// Provide these indexes for template usage
// const isNowPlaying = (index) => index === nowPlayingIndex;
const isComingUpNext = (index) => index === comingUpNextIndex

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


// const isComingUpNext = (shows) => {
//   const now = dayjs();
//   let nowPlayingIndex = -1;
//
//   // First, find the index of the show that's currently playing, if any
//   shows.value.forEach((show, index) => {
//     const showStart = dayjs(show.start_time);
//     const showEnd = showStart.add(show.durationMinutes, 'minute');
//     if (now.isAfter(showStart) && now.isBefore(showEnd)) {
//       nowPlayingIndex = index;
//     }
//   });
//
//   // The show coming up next would be the first show that starts after the current time
//   // and is not the currently playing show
//   for (let i = nowPlayingIndex + 1; i < shows.length; i++) {
//     const nextShowStart = dayjs(shows[i].start_time);
//     if (now.isBefore(nextShowStart)) {
//       return i; // Return the index of the coming up next show
//     }
//   }
//
//   return -1; // Return -1 if there's no show coming up next
// };

const nextShowIndex = isComingUpNext(shows)
console.log('Coming up next show index:', nextShowIndex)


// const nextFourHoursOfContent = computed(() => {
//   return scheduleStore.nextFourHoursOfContent.map(item => {
//     const { startColumn, span } = calculateGridPlacement(item.start_time, item.durationMinutes, nextFourHours.value);
//     // Adjust startColumn for CSS Grid (1-indexed)
//     const gridColumnStart = startColumn + 1;
//     item.gridColumn = `${gridColumnStart} / span ${span}`;
//     return item;
//   });
// });

// console.log(nextFourHoursOfContent.value);
const timeSlots = computed(() => {
  // Assuming nextFourHoursWithHalfHourIntervals is a reactive source
  return nextFourHoursOfContent.value.map(slot => new Date(slot))
})
//
// console.log(timeSlots.value);

function calculateStartColumn(startTime, timeSlots) {
  // Convert startTime to a Date object for comparison
  const startDateTime = new Date(startTime)
  // Find the first time slot that matches or exceeds the startDateTime
  const columnIndex = timeSlots.findIndex(timeSlot => {
    const timeSlotDate = new Date(timeSlot)
    return startDateTime.getTime() <= timeSlotDate.getTime()
  })
  // Return the column index + 1 (CSS grid lines start at 1, not 0)
  return columnIndex + 1
}

function calculateSpan(startTime, durationMinutes, timeSlots) {
  const startDateTime = new Date(startTime)
  const endDateTime = new Date(startDateTime.getTime() + durationMinutes * 60000) // Convert duration to milliseconds and add

  let startIndex = -1
  let endIndex = -1

  // Loop through timeSlots to find start and end indexes
  for (let i = 0; i < timeSlots.length; i++) {
    const slot = new Date(timeSlots[i])
    if (startIndex === -1 && startDateTime <= slot) {
      startIndex = i
    }
    if (endDateTime <= slot) {
      endIndex = i
      break
    }
  }

  // If the end index was not found, it means the event lasts beyond the last time slot
  if (endIndex === -1) {
    endIndex = timeSlots.length
  }

  // The span is the difference between the end and start indexes
  return endIndex - startIndex
}

const timeStringToMinutes = (timeStr) => {
  const [hour, minute] = timeStr.match(/\d+/g)
  return parseInt(hour) * 60 + parseInt(minute)
}

// const calculateGridPlacement = (showStartTime, showDuration, timeIntervals) => {
//   // Convert the show start time and intervals to minutes for comparison
//   const showStartMinutes = timeStringToMinutes(showStartTime);
//   const intervalStartMinutes = timeIntervals.map(interval => timeStringToMinutes(interval));
//
//   // Find the starting column by finding the closest interval start time
//   let startColumn = intervalStartMinutes.findIndex(time => time >= showStartMinutes);
//   startColumn = startColumn === -1 ? timeIntervals.length - 1 : startColumn; // Fallback to the last column if not found
//
//   // Calculate span based on duration (rounded up to cover partial intervals)
//   const span = Math.ceil(showDuration / 30);
//
//   return { startColumn, span };
// };

// Sample usage
// const timeIntervals = ["05:00 PM", "05:30 PM", "06:00 PM", "06:30 PM", "07:00 PM", "07:30 PM", "08:00 PM", "08:30 PM"];
// const showStartTime = "17:00"; // "05:00 PM"
// const showDuration = 60; // 60 minutes

// const { startColumn, span } = calculateGridPlacement(showStartTime, showDuration, timeIntervals);

// shows.value.forEach(show => {
//   const { startColumn, span } = calculateGridPlacement(show.start_time, show.durationMinutes, timeIntervals.value);
//   // Apply the calculated start column and span to your grid layout logic
// });
//
// function calculateGridPlacement(showStartTime, showDuration, timeIntervals) {
//   // Convert showStartTime to the user's timezone and format for comparison
//   const showStartInUserTZ = userStore.convertUtcToUserTimezone(showStartTime);
//
//   // Find the index of the interval that matches the show's start time
//   const startColumn = timeIntervals.findIndex(interval => showStartInUserTZ === interval.dateTimeString);
//
//   // Calculate how many 30-minute intervals the show spans
//   const intervals = Math.ceil(showDuration / 30);
//
//   if (startColumn === -1) {
//     console.error('Start time does not match any interval:', showStartInUserTZ);
//     // Handle the error case appropriately
//     return { startColumn: 1, span: intervals }; // Default or error handling
//   }
//
//   const span = startColumn + intervals > timeIntervals.length ? timeIntervals.length - startColumn : intervals;
//
//   return { startColumn: startColumn + 1, span }; // +1 for CSS grid's 1-based indexing
// }


// This function assumes that `showStartTime` is already in the same format as your time intervals.
// If not, you may need to implement a conversion function to align the time formats.

function convertTo24HourFormat(time) {
  // Assume input is "HH:MM PM/AM" and convert to 24-hour format "HH:MM"
  // This is a placeholder for actual conversion logic
  return time
}


// function calculateGridColumn(startTime, durationMinutes, timeSlots) {
//   // Find the index of the slot that matches the item's start time
//   const startSlotIndex = timeSlots.value.findIndex(timeSlot =>
//       new Date(startTime) >= new Date(timeSlot) &&
//       new Date(startTime) < new Date(new Date(timeSlot).getTime() + 30 * 60000)
//   ) + 1; // Grid lines start at 1
// }
//   function calculateSpan(startTime, durationMinutes) {
//     if (!timeSlots.value || timeSlots.value.length === 0) {
//       console.error('timeSlots is not defined or empty');
//       return 0; // or some fallback value
//     }
//
//     // Now safe to use timeSlots.value.findIndex
//     const startIndex = timeSlots.value.findIndex(/* your logic here */);
//     // Further logic...
//   }


// Example processing of schedule into grid items
// const processedSchedule = computed(() => {
//   return shows.map(show => {
//     const startCol = calculateStartColumn(show.startTime);
//     const span = calculateSpan(show.duration, show.startTime);
//
//     return {
//       ...show,
//       startCol,
//       span,
//     };
//   }).sort((a, b) => a.priority - b.priority); // Ensure sorting by priority
// });

// const calculateStartColumn = (startTime) => {
//   const showStart = new Date(startTime);
//   const gridStart = new Date(nextFourHours.value[0]);
//
//   // Calculate start column based on half-hour increments from gridStart
//   const diffHours = (showStart - gridStart) / (1000 * 60 * 60);
//   return Math.ceil(diffHours * 2) + 1; // +1 because CSS Grid lines start at 1
// };
//
// const calculateSpan = (durationMinutes) => {
//   return Math.ceil(durationMinutes / 30); // Span based on 30-minute intervals
// };

// const calculateStartColumn = (startTime) => {
//   const showStart = new Date(startTime);
//   const gridStart = nextFourHours[0];
//
//   // Find the index of the time slot that matches or immediately precedes the show's start time
//   const columnIndex = nextFourHours.findIndex(time => showStart < time) - 1;
//   console.log('BAHH ' + columnIndex);
//
//   return columnIndex >= 0 ? columnIndex + 1 : 1; // Ensure it falls within the grid columns
// };
//
// const calculateSpan = (startTime, durationMinutes) => {
//   const startColumn = calculateStartColumn(startTime);
//   const span = Math.ceil(durationMinutes / 30); // Determine span based on duration
//
//   // Adjust the span to ensure it doesn't extend beyond the grid
//   return startColumn + span - 1 <= 8 ? span : 8 - startColumn + 1;
// };

const getItemStyle = (item) => {
  const startColumn = calculateStartColumn(item.start_time)
  const span = calculateSpan(item.durationMinutes)

  return {
    gridColumnStart: startColumn,
    gridColumnEnd: `span ${span}`,
    gridRowStart: 2, // All items start in the second row
  }
}

// Method to format the full hour
function formatTime(date) {
  return date.toLocaleTimeString('en-US', {hour: 'numeric', minute: '2-digit', hour12: true})
}

// Method to add 30 minutes to the given date and format it
function formatHalfHour(date) {
  const halfHourLater = new Date(date.getTime() + 30 * 60 * 1000)
  return halfHourLater.toLocaleTimeString('en-US', {hour: 'numeric', minute: '2-digit', hour12: true})
}

// Determine visibility classes based on column index and if it's a half-hour
function getColumnVisibilityClass(index, isHalfHour) {
  // Adjust index for half-hour columns
  const adjustedIndex = isHalfHour ? index * 2 + 1 : index * 2

  if (adjustedIndex >= 6) { // Last two columns visible only on 2xl screens
    return 'hidden 2xl:table-cell'
  } else if (adjustedIndex >= 3) { // Columns 4, 5, 6 visible on xl screens and above
    return 'hidden xl:table-cell'
  } else { // Columns 1, 2, 3 are always visible
    return ''
  }
}

function calculateColspan(durationMinutes) {
  // Assuming each hour (and its half-hour mark) is represented by two columns
  // and that each content item's duration in minutes can determine its span
  return Math.ceil(durationMinutes / 30)
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

function openModal(modalName) {
  document.getElementById(modalName).showModal()
}

</script>
<script>
import NoLayout from '@/Layouts/NoLayout'

export default {
  layout: NoLayout,
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
  grid-template-columns: repeat(8, 1fr); /* Adjust based on your actual time slots */

}

.header-row, .content-row, .status-row {
  display: contents; /* Make these divs act as part of the grid layout without creating extra rows */
  width: 100%; /* Ensure they span the full width of the grid */
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
