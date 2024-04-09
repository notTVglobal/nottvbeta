<script>

// DELETE THIS SCRIPT TAG
// This is only for notes about building this page.

// The schedule page will allow free users to look back 72 hours and look ahead 72 hours.
// On a mobile device it's easy enough to create a forever scroll for looking forward.
// The easter egg will be scrolling UP to look back at the past 72 hours.
// Free users can watch any of the content in the past 72 hours for free.
// Users are given credits each month to watch premium notTV content.
// They may purchase more credits, or subscribe for unlimited access.
// They may also use credits to purchase licenses to content from creators.
// Credits may also be spent in the shop.

</script>

<template>
  <Head title="Schedule"/>

  <div class="place-self-center flex flex-col gap-y-3 w-full overscroll-x-none pb-64">
    <div id="topDiv" class="flex justify-end px-5">
      <div class="text-3xl font-semibold pt-4">Schedule</div>
    </div>
    <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>
<!--    <div class="mx-6">-->
<!--      <div class="alert alert-info">-->
<!--        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6">-->
<!--          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"-->
<!--                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>-->
<!--        </svg>-->
<!--        <span></span>-->
<!--      </div>-->
<!--    </div>-->
<!--    <ul class="w-64 ml-12 my-8">-->
<!--      <li class="p-2 bg-green-800">Scheduled Shows</li>-->
<!--      <li class="p-2 bg-purple-800">New Releases</li>-->
<!--      <li class="p-2 bg-blue-800">Live Events</li>-->
<!--      <li class="p-2 bg-yellow-800">News</li>-->
<!--      &lt;!&ndash;            <li class="p-2"><font-awesome-icon icon="fa-leaf" class="text-red-600 text-3xl pr-2"/> Canadian Content</li>&ndash;&gt;-->
<!--      &lt;!&ndash;            <li class="p-2"><font-awesome-icon icon="fa-flag-usa" class="text-red-600 text-3xl pr-2"/> American Content</li>&ndash;&gt;-->

<!--    </ul>-->

    <div class="ml-5 px-2">
      <span class="text-sm uppercase text-purple-500">All times are listed in your timezone.</span>
    </div>

    <div class="schedule-grid">
      <!-- Header Row for Times -->
      <div class="header-row">
        <template v-for="(time, index) in nextFourHours" :key="`header-${index}`">
          <div class="schedule-cell">{{ time }}</div>
        </template>
      </div>
      <div v-for="slot in timeSlots" :key="slot">{{ slot }}</div>
      <!-- Content Row -->
      <div class="content-row">
<!--        <template v-for="(item, index) in nextFourHoursOfContent" :key="`content-${index}`">-->
<!--          <div-->
<!--              class="item-cell"-->
<!--              :style="{-->
<!--        'grid-column': calculateGridColumn(item.start_time, item.durationMinutes, nextFourHours),-->
<!--      }"-->
<!--          >-->
<!--            <h3>{{ item.content.show?.name || 'No Show Name' }}</h3>-->
<!--            &lt;!&ndash; Display the image if available &ndash;&gt;-->
<!--&lt;!&ndash;            <img v-if="item.content.show?.image" :src="getImageUrl(item.content.show.image)" :alt="item.content.show?.name" />&ndash;&gt;-->
<!--            <SingleImage :image="item.content.show.image" />-->
<!--            &lt;!&ndash; Placeholder if no image &ndash;&gt;-->
<!--&lt;!&ndash;            <div v-else class="placeholder"></div>&ndash;&gt;-->
<!--          </div>-->
<!--        </template>-->
      </div>

    </div>






    <table class="table-fixed mx-5">
      <thead class="bg-gray-900">
      <tr class="border-b border-0.5 border-white">
        <!-- Loop for each hour, including its half-hour mark -->
        <template v-for="(time, index) in nextFourHours" :key="index">
          <!-- Hour mark -->
          <th :class="getColumnVisibilityClass(index * 2)">
            {{ formatTime(new Date(time)) }}
          </th>
          <!-- Half-hour mark -->
          <th :class="getColumnVisibilityClass(index * 2 + 1)">
            {{ formatHalfHour(new Date(time)) }}
          </th>
        </template>
      </tr>
      </thead>
      <tbody>
      <!-- Loop through each content item -->
      <tr v-for="(item, index) in nextFourHoursOfContent" :key="index" class="border-b border-0.5 border-white">
        <!-- Calculate the colspan based on durationMinutes -->
        <td :colspan="calculateColspan(item.durationMinutes)" @click="openModal(`goToNowPlayingModal`)"
            :class="getCellClasses(item.type)">
          <div class="flex flex-col">
            <span class="text-center pb-2">{{ item.content.show?.name || 'No Show Name' }}</span>
            <!-- Optionally display an image if available -->
<!--            <div v-if="content.show?.image" class="w-full h-64" :style="{ backgroundImage: `url(${content.show.image.cdn_endpoint}${content.show.image.folder}/${content.show.image.name})`, backgroundSize: 'cover' }"></div>-->
            <SingleImage v-if="item.type === 'show'" :image="item?.content?.show?.image"
                         :alt="item?.content?.show?.name" :class="`max-w-full h-auto object-cover`"/>
            <SingleImage v-else :image="item?.content?.image" :alt="item?.content?.name" :class="`max-w-xs h-auto object-cover`"/>
            <!-- Placeholder if no image is available -->
            <div v-else class="w-full h-64 bg-gray-400"></div>
          </div>
        </td>
      </tr>
      </tbody>

    </table>

    <PopUpModal :id="`goToNowPlayingModal`">
      <template v-slot:header>Now Playing</template>
      <template v-slot:main><span class="text-orange-500">This modal is temporary. This will take you to the now playing show or episode page.</span>
      </template>
    </PopUpModal>
    <PopUpModal :id="`getReminderModal`">
      <template v-slot:header>Set Reminder</template>
      <template v-slot:main><span class="text-orange-500">This modal is temporary. Set a reminder when this show starts and/or subscribe to the show to get all notifications when new episodes are released or the show goes live.</span>
      </template>
    </PopUpModal>

    <div class="bg-gray-600 rounded-lg shadow m-10 p-4">


      <TodayView />
    </div>


  </div>
</template>

<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useScheduleStore } from "@/Stores/ScheduleStore"
import Message from "@/Components/Global/Modals/Messages"
import PopUpModal from "@/Components/Global/Modals/PopUpModal"
import TodayView from '@/Components/Global/Calendar/TodayView.vue'
import { computed } from 'vue'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'

usePageSetup('schedule')

const appSettingStore = useAppSettingStore()
const scheduleStore = useScheduleStore()

let props = defineProps({
  can: Object,
})

// Computed property to ensure reactivity
const nextFourHours = computed(() => scheduleStore.nextFourHoursWithHalfHourIntervals);
const nextFourHoursOfContent = computed(() => scheduleStore.nextFourHoursOfContent);
console.log(nextFourHoursOfContent.value);
const timeSlots = computed(() => {
  // Assuming nextFourHoursWithHalfHourIntervals is a reactive source
  return nextFourHoursOfContent.value.map(slot => new Date(slot));
});

console.log(timeSlots.value);

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

const calculateStartColumn = (startTime) => {
  const showStart = new Date(startTime);
  const gridStart = nextFourHours[0];

  // Find the index of the time slot that matches or immediately precedes the show's start time
  const columnIndex = nextFourHours.findIndex(time => showStart < time) - 1;

  return columnIndex >= 0 ? columnIndex + 1 : 1; // Ensure it falls within the grid columns
};
//
// const calculateSpan = (startTime, durationMinutes) => {
//   const startColumn = calculateStartColumn(startTime);
//   const span = Math.ceil(durationMinutes / 30); // Determine span based on duration
//
//   // Adjust the span to ensure it doesn't extend beyond the grid
//   return startColumn + span - 1 <= 8 ? span : 8 - startColumn + 1;
// };

const getItemStyle = (item) => {
  const startColumn = calculateStartColumn(item.start_time);
  const span = calculateSpan(item.durationMinutes);

  return {
    gridColumnStart: startColumn,
    gridColumnEnd: `span ${span}`,
    gridRowStart: 2, // All items start in the second row
  };
};

// Method to format the full hour
function formatTime(date) {
  return date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
}

// Method to add 30 minutes to the given date and format it
function formatHalfHour(date) {
  const halfHourLater = new Date(date.getTime() + 30 * 60 * 1000);
  return halfHourLater.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
}

// Determine visibility classes based on column index and if it's a half-hour
function getColumnVisibilityClass(index, isHalfHour) {
  // Adjust index for half-hour columns
  const adjustedIndex = isHalfHour ? index * 2 + 1 : index * 2;

  if (adjustedIndex >= 6) { // Last two columns visible only on 2xl screens
    return 'hidden 2xl:table-cell';
  } else if (adjustedIndex >= 3) { // Columns 4, 5, 6 visible on xl screens and above
    return 'hidden xl:table-cell';
  } else { // Columns 1, 2, 3 are always visible
    return '';
  }
}

function calculateColspan(durationMinutes) {
  // Assuming each hour (and its half-hour mark) is represented by two columns
  // and that each content item's duration in minutes can determine its span
  return Math.ceil(durationMinutes / 30);
}

function getCellClasses(type) {
  const baseClass = 'column-width p-2 text-sm 2xl:text-md border border-0.5 border-green-300 hover:border-blue-500 cursor-pointer';
  switch (type) {
    case 'show':
      return `${baseClass} bg-green-800 hover:bg-green-600`;
    case 'new_release':
      return `${baseClass} bg-purple-800 hover:bg-purple-600`;
      // Add more cases as needed
    default:
      return baseClass;
  }
}

function openModal(modalName) {
  document.getElementById(modalName).showModal()
}

// Example test call
// console.log(calculateGridColumn('2024-04-09 13:00:00', 90, nextFourHours.value));


</script>

<style scoped>

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
  grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); /* Adjust based on your minimum column width */
  gap: 4px;
}

.header-row, .content-row {
  display: contents; /* Make these divs act as part of the grid layout without creating extra rows */
}

.schedule-cell {
  background: #333;
  color: #fff;
  text-align: center;
  padding: 8px;
}

.item-content {
  padding: 8px;
  background: #444; /* Example background, adjust as needed */
}

.content-image {
  width: 100%;
  height: auto;
  object-fit: cover;
}

/* Responsive visibility */
@media (min-width: 1280px) { /* 2xl */
  .xl\:hidden { display: none; }
}

@media (min-width: 1024px) { /* xl */
  .lg\:hidden { display: none; }
}

</style>
