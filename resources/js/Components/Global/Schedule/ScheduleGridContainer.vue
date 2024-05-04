<template>

  <!--  We need the CurrenTime component to keep our ScheduleStore currentTime up to date
        it has a SetInterval in it. -->
  <CurrentTime/>

  <div class="mb-2 tracking-wide">
    <span class="text-sm uppercase text-purple-500">All times are listed in your timezone.</span>
  </div>

  <div class="schedule-grid" :style="{ 'grid-template-columns': gridTemplateColumns }">
    <div class="header-row" :style="{ 'grid-template-columns': gridTemplateColumns }">
      <!-- Time slots header -->
      <div class="time-cell bg-gray-900 px-3 py-2 text-center font-bold"
           v-for="interval in nextFourHoursWithHalfHourIntervals" :key="interval.dateTime">
        {{ interval.formatted }}
      </div>
    </div>
    <div v-if="scheduleIsLoading && nextFourHoursOfContent.length === 0"
         class="w-full flex justify-center text-center items-center">
      <span class="loading loading-ball loading-xl text-info"></span>
    </div>


        <!-- Loop through combinedShows directly -->
        <div v-for="item in scheduleStore.nextFourHoursOfContent"
             :key="item.id"
             :style="gridItemStyle(item)"
             class="show-cell"
             @click="handleShowClick(item)">
          <div class="item-content">
            <h3>{{ item.content.name || 'No Show Name' }}</h3>
            <p>{{ item.startTime }} - {{ item.durationMinutes }} minutes</p>
            <p>{{ item.content.id }}</p>
            <p>Row: {{ item.gridRow }}</p>
            <SingleImage v-if="item.content.image"
                         :image="item.content.image"
                         :alt="item.content.name"/>
          </div>
        </div>




    <!-- Loop through each row -->
<!--    <template v-for="(row, rowIndex) in nextFourHoursOfContent" :key="`row-${rowIndex}`">-->
<!--      <div class="content-row">-->
<!--        &lt;!&ndash; Loop through each item in the row &ndash;&gt;-->
<!--        <template v-for="item in row" :key="item.id">-->
<!--          <div-->
<!--              class="show-cell flex flex-col w-full h-full"-->
<!--              :class="getCellClasses(item.type)"-->
<!--              :style="gridPlacement(item.gridStart, item.gridSpan)"-->
<!--              @click="handleShowClick(item)"-->
<!--          >-->
<!--            <div class="item-content flex flex-col items-center justify-center gap-y-2 flex-grow">-->
<!--              <h3>{{ item.content?.name || 'No Show Name' }}</h3>-->
<!--              <p>{{ item.startTime }} - {{ item.durationMinutes }} minutes</p>-->
<!--              <p></p>-->
<!--              <p>{{ item.content?.id }}</p>-->
<!--              <p>{{ item.gridRow }}</p>-->
<!--              <SingleImage v-if="item.content?.image"-->
<!--                           :image="item.content?.image"-->
<!--                           :alt="item.content?.name"-->
<!--                           :class="`w-full h-auto object-cover`"/>-->
<!--            </div>-->
<!--          </div>-->
<!--        </template>-->
<!--      </div>-->
<!--    </template>-->

<!--    <div class="status-row">-->
<!--      <div v-if="nowPlayingShow">-->
<!--        <div :class="getStatusCellClasses(nowPlayingShow.gridStart, true, false)"-->
<!--             :style="gridPlacement(nowPlayingShow.gridStart, nowPlayingShow.gridSpan)">-->
<!--          <span>NOW PLAYING</span>-->
<!--        </div>-->
<!--      </div>-->
<!--      <div v-if="comingUpNextShow">-->
<!--        <div :class="getStatusCellClasses(comingUpNextShow.gridStart, false, true)"-->
<!--             :style="gridPlacement(comingUpNextShow.gridStart, comingUpNextShow.gridSpan)">-->
<!--          <span>COMING UP NEXT</span>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->



  </div>

</template>
<script setup>
import { computed, onMounted, watch, watchEffect } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { debounce } from 'lodash';
import dayjs from 'dayjs'
import { useScheduleStore } from '@/Stores/ScheduleStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import ScheduleGrid from '@/Components/Pages/Schedule/ScheduleGrid.vue'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'
import CurrentTime from '@/Components/Global/Schedule/CurrentTime.vue'

const scheduleStore = useScheduleStore()
const appSettingStore = useAppSettingStore()
const userStore = useUserStore()

let initialLoadHandled = false;

watch(() => scheduleStore.timeSlots, (newTimeSlots, oldTimeSlots) => {
  if (newTimeSlots && newTimeSlots.length > 0 && !initialLoadHandled) {
    console.log('Time slots are ready, updating next four hours.');
    scheduleStore.updateNextFourHours();
    initialLoadHandled = true;
  }
}, { immediate: true });

// watch(() => scheduleStore.currentHalfHour, (newTimeSlots, oldTimeSlots) => {
//   if (newTimeSlots && newTimeSlots.length > 0) {
//     console.log('Time slots are ready, updating next four hours.');
//     scheduleStore.updateNextFourHours();
//   }
// });

// const debouncedUpdate = debounce(() => {
//   scheduleStore.updateNextFourHours();
// }, 100); // Adjust debounce time as needed
//
// Watch for baseTime changes to update the schedule
watch(
    () => scheduleStore.baseTime,
    (newBaseTime, oldBaseTime) => {
      if (newBaseTime !== oldBaseTime && scheduleStore.timeSlots && scheduleStore.timeSlots.length > 0) { // This check may be redundant but adds clarity
        console.log(`Base time updated from ${oldBaseTime} to ${newBaseTime}`);
        scheduleStore.updateNextFourHours();
      }
    },
    { immediate: true }
);
//

// Watch for changes in screen size indicators
watch(
    [() => appSettingStore.isVerySmallScreen, () => appSettingStore.isSmallScreen],
    ([newVerySmall, newSmall], [oldVerySmall, oldSmall]) => {
      if (newVerySmall !== oldVerySmall || newSmall !== oldSmall) {
        console.log(`Screen size change detected: VerySmallScreen: ${newVerySmall}, SmallScreen: ${newSmall}`);
        scheduleStore.updateNextFourHours();
      }
    },
    { immediate: true }  // Optionally run on initial setup
)

// watch(
//     () => scheduleStore.baseTime,
//     (newTime, oldTime) => {
//       console.log(`Time updated from ${oldTime} to ${newTime}`);
//       scheduleStore.updateNextFourHours();
//     },
//     { immediate: true }
// );


watchEffect(() => {
  console.log('nextFourHoursOfContent:', scheduleStore.nextFourHoursOfContent);
});

// Define the function to calculate grid style directly
function gridItemStyle(item) {
  const style = {
    gridColumn: `${item.gridStart} / span ${item.gridSpan}`,
    gridRow: `row ${item.gridRow}`,
  };
  console.log(style);  // Log to see what styles are being returned
  return style;
}


// Computed property to ensure reactivity
const nextFourHoursWithHalfHourIntervals = computed(() => scheduleStore.nextFourHoursWithHalfHourIntervals);
const nextFourHoursOfContent = computed(() => scheduleStore.nextFourHoursOfContent);
const scheduleIsLoading = computed(() => scheduleStore.scheduleIsLoading);

const gridPlacement = (gridStart, gridSpan) => {
  return {
    gridColumnStart: gridStart,
    gridColumnEnd: `span ${gridSpan}`,
    gridRowStart: 'auto',
    gridRowEnd: 'span 1', // Assuming each item occupies one row height-wise
  }
}

const gridTemplateColumns = computed(() => {
  const cols = appSettingStore.isVerySmallScreen ? 4 :
      appSettingStore.isSmallScreen ? 6 : 8;
  return `repeat(${cols}, 1fr)`;
});


// const gridPlacement = (startTime, durationMinutes) => {
//   // Convert startTime to a comparable format if necessary
//   const startDateTime = new Date(startTime)
//   const timeSlots = nextFourHoursWithHalfHourIntervals.value.map(interval => new Date(interval.dateTimeString))
//
//   // Find the index of the time slot that matches the item's start time
//   let startColumn = timeSlots.findIndex(slot => startDateTime >= slot && startDateTime < new Date(slot.getTime() + 30 * 60000))
//   if (startColumn === -1) {
//     console.error('Start time does not match any interval:', startTime)
//     return {} // Fallback or error handling
//   }
//
//   // Adjust startColumn for 1-based indexing in CSS Grid
//   startColumn += 1
//
//   // Calculate span based on duration
//   const span = Math.ceil(durationMinutes / 30)
//
//   // Return CSS style object for grid placement
//   return {
//     'gridColumn': `${startColumn} / span ${span}`,
//   }
// }

// // Determines the classes for a status cell
// const getStatusCellClasses = (index) => {
//   const classes = ['status-cell']
//   if (index === 0) classes.push('now-playing')
//   else if (index === 1) classes.push('coming-up-next')
//   else classes.push('status-cell-empty') // For cells without specific content
//   return classes
// }

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
  if (isNowPlaying(item.startTime, item.durationMinutes)) {
    // Redirect to the show's page if it's currently playing
    Inertia.visit(`/shows/${item.content.slug}/`)
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
  console.log('Now Playing Show Start Time: ' + showStartTime)
  console.log('Duration Minutes: ' + durationMinutes)
  return now.isAfter(startTime) && now.isBefore(endTime)
}

// Helper function to determine the appropriate classes based on the gridStart and certain conditions
const getStatusCellClasses = (gridStart, isFirst, isSecond) => {
  const classes = ['status-cell'] // Base class for all status cells
  if (isFirst && gridStart === 1) {
    // 'Now Playing' is only assigned if it's the first item and it starts at the first grid column
    classes.push('now-playing')
  } else if (isSecond && gridStart !== 1) {
    // 'Coming Up Next' is only assigned to the second item and it should not start at the first grid column
    classes.push('coming-up-next')
  } else {
    // Default class for other cells or when no specific condition is met
    classes.push('status-cell-empty')
  }
  return classes
}
// Ensure the data structure is what you expect
console.log('All items in store:', scheduleStore.nextFourHoursOfContent)

//
// const actualShows = computed(() => {
//   // Flatten the nested arrays, filter out placeholders, and ignore specific content names
//   return scheduleStore.nextFourHoursOfContent.flat().filter(item =>
//       !item.placeholder && item.content.name !== "Nothing scheduled." && item.content.name !== "Blank Spot"
//   );
// });
//
// const nowPlayingShow = computed(() => {
//   // Traverse through nested arrays to find the currently playing show
//   for (const row of scheduleStore.nextFourHoursOfContent) {
//     for (const show of row) {
//       if (!show.placeholder &&
//           show.content.name !== "Nothing scheduled." &&
//           show.content.name !== "Blank Spot" &&
//           isNowPlaying(show.startTime, show.durationMinutes) &&
//           show.gridStart === 1) {
//         console.log('Now Playing Show:', show);
//         return show;  // Return the first matching show
//       }
//     }
//   }
//   console.log('No show is currently playing.');
//   return null;  // Return null if no show is currently playing
// });
//
//
// const comingUpNextShow = computed(() => {
//   let foundPlaying = false;
//   // Traverse through nested arrays to find the upcoming show
//   for (const row of scheduleStore.nextFourHoursOfContent) {
//     for (const show of row) {
//       if (!show.placeholder &&
//           show.content.name !== "Nothing scheduled." &&
//           show.content.name !== "Blank Spot") {
//         if (foundPlaying && show.gridStart > 1) {
//           console.log('Coming Up Next Show:', show);
//           return show; // Return the first relevant upcoming show
//         }
//         if (isNowPlaying(show.startTime, show.durationMinutes)) {
//           foundPlaying = true; // Flag found when the currently playing show is identified
//         }
//       }
//     }
//   }
//   console.log('No upcoming show found.');
//   return null; // Return null if no upcoming show is found
// });


// watch(nowPlayingShow, (newVal, oldVal) => {
//   console.log('Now Playing Show changed from:', oldVal, 'to:', newVal);
// });
//
// watch(comingUpNextShow, (newVal, oldVal) => {
//   console.log('Coming Up Next Show changed from:', oldVal, 'to:', newVal);
// });

// Optional: Watch the entire content array if changes are frequent and need to trigger reevaluations
watch(() => scheduleStore.nextFourHoursOfContent, () => {
  console.log('Content changed, recomputing shows...');
}, { deep: true });


//
// // Watch for changes in actualShows and log or react accordingly
// watch(actualShows, (newShows, oldShows) => {
//   console.log("Actual shows have updated:", newShows);
//   // Additional reactions can be performed here
// });
//
// // Optionally, watch for changes in previousItemGridEnd if needed
// watch(previousItemGridEnd, (newEnd, oldEnd) => {
//   console.log("Previous item grid end has updated:", newEnd);
//   // React to changes in the end of the first show, if necessary
// });


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

.show-cell {
  display: flex; /* Ensure this is set to flex to control child elements with flex properties */
  flex-direction: column; /* Align children in a column */
  justify-content: center; /* Center children vertically */
  align-items: center; /* Center children horizontally */
  border: 1px solid #ccc;

  background-color: #f8f8f8;
  width: 100%; /* Ensures cell uses full width of its grid column */
  height: 100%; /* Ensures cell uses full height */
}

.time-cell {
  border: 1px solid #fff;
  text-align: center; /* Center text if desired */
  padding: 10px;
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
  display: contents; /* This makes the row container disappear, directly using the grid defined in parent */
}

.schedule-cell {
  background: #333;
  color: #fff;
  text-align: center;
  padding: 8px;
}

.grid-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
  grid-gap: 10px;
}
.grid-item {
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid #ccc;
}

.item-content {
  padding: 8px;
  background: linear-gradient(to right, rgba(68, 68, 68, 0.9), rgba(68, 68, 68, 0.7));
  width: 100%; /* Ensures content fills the full width of its parent */
  height: 100%; /* Ensures content fills the full height of its parent */
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  flex-grow: 1; /* This will now effectively make the item content expand as needed */
}

.item-content:hover {
  background: linear-gradient(to right, #06beb6, #48b1bf);
  /* Adjust the colors above to match your desired hover effect */
}

.status-row {
  display: contents; /* This makes the row container disappear, directly using the grid defined in parent */

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
  text-align: center;
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