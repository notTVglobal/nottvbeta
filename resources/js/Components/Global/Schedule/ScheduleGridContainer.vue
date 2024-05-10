<template>

  <!--  We need the CurrenTime component to keep our ScheduleStore currentTime up to date it has a SetInterval in it. -->
  <CurrentTime/>

  <div class="mb-2 tracking-wide">
    <span class="text-sm uppercase text-purple-500">All times are listed in your timezone.</span>
  </div>

  <div class="w-full">
    <div class="schedule-grid" :style="{ 'grid-template-columns': gridColumns }">
      <div class="header-row" :style="{ 'grid-template-columns': gridColumns }">
        <!-- Time slots header -->
        <div class="time-cell bg-gray-900 px-3 py-2 text-center font-bold"
             v-for="interval in nextFourHoursWithHalfHourIntervals" :key="interval.dateTime">
          {{ interval.formatted }}
        </div>
      </div>
      <div v-if="isLoading && nextFourHoursOfContent.length === 0"
           class="w-full flex justify-center text-center items-center">
        <span class="loading loading-ball loading-xl text-info"></span>
      </div>
    </div>
    <div class="schedule-grid" :style="{ 'grid-template-columns': gridColumns }">
      <!-- Render time banners -->
      <div v-for="banner in scheduleStore.preparedTimeBanners" :key="banner.id"
           :style="gridItemStyle(banner)" class="time-banner align-center">
        {{ banner.name }}
      </div>
    </div>
    <div class="schedule-grid" :style="{ 'grid-template-columns': gridColumns }">


      <!-- Loop through combinedShows directly -->
      <div v-for="item in scheduleStore.nextFourHoursOfContent"
           :key="item.id"
           :style="gridItemStyle(item)"
           class="show-cell"
           :class="{'hover:cursor-pointer' : !item.placeholder}"
           @click="handleShowClick(item)">
        <div
            class="item-content px-4 flex flex-col justify-between items-center w-full h-full overflow-hidden"
            :class="{
        'bg-gradient-to-r from-gray-900 to-gray-700': !item.placeholder,
        'bg-gradient-to-r from-tan-800 to-tan-600': item.placeholder,  // Assume tan-800 and tan-600 are defined in your tailwind config
        'gradient-on-hover': !item.placeholder
    }">
          <div class="show-info flex-grow flex flex-col items-center justify-center">
            <h3 class="show-title my-4 w-full text-center text-lg font-semibold break-words"
                :class="{'gradient-on-hover': !item.placeholder}">
              {{ item.content.name || 'No Show Name' }}</h3>
            <!--            <p>{{ item.content.id }}</p>-->
            <!--            <p>Row: {{ item.gridRow }}</p>-->
            <SingleImage v-if="item.content.image"
                         :image="item.content.image"
                         :alt="item.content.name"
                         :class="`w-full h-auto max-h-1/2 object-cover transition-opacity duration-300 hover:opacity-80`"/>
            <div v-if="!item.placeholder"
                 class="show-time w-full text-center text-sm p-2 mt-2"
                 :class="{'gradient-on-hover': !item.placeholder}">
              <p>{{ formatTime(item.startTime, true) }}</p>
              <p>{{ formatDuration(item.durationMinutes) }}</p>
            </div>
          </div>
        </div>
      </div>

    </div>

    <div class="schedule-grid text-center" :style="{ 'grid-template-columns': gridColumns }">

      <div v-if="nowPlayingShow" :style="statusGridItemStyle(nowPlayingShow)"
           class="now-playing text-black font-semibold">
        <span>NOW PLAYING</span>
      </div>

      <div v-if="comingUpNextShow" :style="statusGridItemStyle(comingUpNextShow)"
           class="coming-up-next text-black font-semibold">
        <span>COMING UP NEXT</span>
      </div>

    </div>

    <div class="infinite-scroll-container">
      <div v-for="(show, index) in displayedShows" :key="show.id"
           class="next-show-highlight p-5 border border-gray-300 bg-gradient-to-r from-gray-900 to-gray-700 text-center hover-gradient"
           :class="{ 'last-item': index === displayedShows.length - 1 }">
        <div class="bg-green-500 text-white py-2">
          <h2>Playing Soon</h2>
        </div>
        <div class="show-details mt-4 mx-auto max-w-4xl">
          <h3 @click="handleShowClick(show)" class="text-3xl mb-1 hover:text-blue-300 hover:cursor-pointer">
            {{ show.content.name }}</h3>
          <p class="text-lg">{{ formatLongDate(show.startTime) }}</p>
          <p class="text-lg">{{ formatTime(show.startTime, true) }} - {{ formatTime(show.endTime, true) }}</p>
          <div class="w-full flex justify-center items-center mt-4 hover:cursor-pointer" @click="handleShowClick(show)">
            <SingleImage v-if="show.content.image"
                         :image="show.content.image"
                         :alt="show.content.name"
                         class="w-3/4 md:w-1/2 lg:w-1/3 h-auto object-cover mx-auto transition-opacity duration-300 hover:opacity-80"/>
          </div>
          <p class="text-lg">{{ formatDuration(show.durationMinutes) }}</p>
        </div>
      </div>
      <!-- Loading Indicator -->
      <div v-if="isLoading" class="w-full text-center mt-4">
      <span class="loading loading-dots loading-lg text-info">
      </span>
      </div>
      <div v-element-visibility="onElementVisibility"></div> <!-- This element triggers the visibility event -->
    </div>

  </div>
</template>
<script setup>
import { computed, onBeforeMount, onMounted, onUnmounted, ref, watch, watchEffect } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { vElementVisibility } from '@vueuse/components'
import dayjs from 'dayjs'
import advancedFormat from 'dayjs/plugin/advancedFormat' // for using 'a' for AM/PM format
import { useScheduleStore } from '@/Stores/ScheduleStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import ScheduleGrid from '@/Components/Pages/Schedule/ScheduleGrid.vue'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'
import CurrentTime from '@/Components/Global/Schedule/CurrentTime.vue'
import { throttle } from '@/Utilities/Throttle'

const scheduleStore = useScheduleStore()
const appSettingStore = useAppSettingStore()
const userStore = useUserStore()

dayjs.extend(advancedFormat)

let initialLoadHandled = false
let initialFetchCompleted = false

const isVisible = ref(false)
const displayedShowsCount = ref(6)
// Computed property to ensure reactivity
const isLoading = computed(() => scheduleStore.isLoading)
const nextFourHoursWithHalfHourIntervals = computed(() => scheduleStore.nextFourHoursWithHalfHourIntervals)
const nextFourHoursOfContent = computed(() => scheduleStore.nextFourHoursOfContent)

// Function to handle element visibility
function onElementVisibility(state) {
  isVisible.value = state;
}

// Function to load more shows
const loadMoreShows = async () => {
  if (isVisible.value && !isLoading.value) {
    isLoading.value = true;
    // console.log("Loading more shows");

    // Fetch more schedules
    await scheduleStore.fetchMoreSchedules();

    displayedShowsCount.value += 6;
    isLoading.value = false;
  }
};

// Throttle the loadMoreShows function
const throttledLoadMoreShows = throttle(loadMoreShows, 200);


// Watcher for isVisible.value
watch(isVisible, (newValue) => {
  if (newValue && initialFetchCompleted) {
    throttledLoadMoreShows();
  }
});

const allPlaceholders = computed(() => {
  return scheduleStore.nextFourHoursOfContent.every(item => item.placeholder)
})

const upcomingShows = computed(() => {
  const now = dayjs(scheduleStore.baseTime);

  // Log baseTime and schedules for debugging
  // console.log('Base Time:', now);
  // console.log('Schedules:', scheduleStore.schedules);

  // Create a set of show start times from nextFourHoursOfContent
  const nextFourHoursShowTimes = new Set(scheduleStore.nextFourHoursOfContent.map(show => show.startTime));
  // console.log('Next Four Hours Show Times:', Array.from(nextFourHoursShowTimes));

  // Filter out shows that are part of nextFourHoursOfContent by matching startTime
  const filteredShows = scheduleStore.schedules.filter(show => {
    const isAfterNow = dayjs(show.startTime).isAfter(now);
    const isNotPlaceholder = !show.placeholder;
    const isNotInNextFourHours = !nextFourHoursShowTimes.has(show.startTime);
    const shouldInclude = isAfterNow && isNotPlaceholder && isNotInNextFourHours;

    // console.log(`Show ID: ${show.id}, Start Time: ${show.startTime}, Include: ${shouldInclude}`);
    return shouldInclude;
  });

  // Sort filtered shows by start time
  return filteredShows.sort((a, b) => dayjs(a.startTime).diff(dayjs(b.startTime)));
});

const displayedShows = computed(() => upcomingShows.value.slice(0, displayedShowsCount.value))

function isNowPlaying(startTime, duration) {
  const now = dayjs()
  const start = dayjs(startTime)
  const end = start.add(duration, 'minutes')
  return now.isAfter(start) && now.isBefore(end)
}


const nowPlayingShow = computed(() => {
  return scheduleStore.nextFourHoursOfContent.find(show => show.nowPlaying)
})

const comingUpNextShow = computed(() => {
  return scheduleStore.nextFourHoursOfContent.find(  show => show.comingUpNext)
})

onMounted(async () => {
  scheduleStore.resetAll()
  const now = dayjs();
  const startDate = now.subtract(4, 'hour').toISOString();
  const endDate = now.add(7, 'day').toISOString();
  await scheduleStore.fetchSchedules(startDate, endDate);
  initialFetchCompleted = true

  // Watcher for schedules to ensure initial data load
  watch(
      () => scheduleStore.schedules,
      (newSchedules) => {
        if (newSchedules && newSchedules.length > 0 && !initialLoadHandled && initialFetchCompleted) {
          scheduleStore.updateNextFourHours();
          initialLoadHandled = true;
        }
      },
      { immediate: true },
  );

  // Watch for changes in screen size indicators
  watch(
      [() => appSettingStore.isVerySmallScreen, () => appSettingStore.isSmallScreen],
      ([newVerySmall, newSmall], [oldVerySmall, oldSmall]) => {
        if ((newVerySmall !== oldVerySmall || newSmall !== oldSmall) && initialFetchCompleted) {
          // console.log(`Screen size change detected: VerySmallScreen: ${newVerySmall}, SmallScreen: ${newSmall}`)
          scheduleStore.fetchSchedules()
        }
      },
      {immediate: false},  // Optionally run on initial setup
  )

  // Update the next four hours when baseTime changes
  watch(
      () => scheduleStore.baseTime,
      (newTime, oldTime) => {
        if (newTime !== oldTime && initialFetchCompleted) { // This check may be redundant but adds clarity
          // console.log(`Base time updated from ${oldTime} to ${newTime}`)
          scheduleStore.updateNextFourHours()
        }
      },
      {immediate: true},
  )
});

// Method to format time with conditional AM/PM display
function formatTime(time, showMeridiem = false) {
  return dayjs(time).format(`h:mm ${showMeridiem ? 'a' : ''}`)
}

function formatLongDate(date) {
  return dayjs(date).format('dddd MMM D, YYYY')
}

// Method to format duration into a readable format
function formatDuration(minutes) {
  const hours = Math.floor(minutes / 60)
  const remainderMinutes = minutes % 60
  if (hours === 0) return `${remainderMinutes} minutes`
  return `${hours} hour${hours > 1 ? 's' : ''} ${remainderMinutes > 0 ? remainderMinutes + ' minutes' : ''}`
}

// Define the function to calculate grid style directly
function gridItemStyle(item) {
  const style = {
    gridColumn: `${item.gridStart} / span ${item.gridSpan}`,
    gridRow: `row ${item.gridRow}`,
  }
  // console.log(style)  // Log to see what styles are being returned
  return style
}

// Dedicated function to handle status row grid styling
function statusGridItemStyle(item) {
  if (!item) return {}

  // Log to debug the grid positions being applied
  // console.log(`Status Item - Grid Start: ${item.gridStart}, Grid Span: ${item.gridSpan}`)

  return {
    gridColumn: `${item.gridStart} / span ${item.gridSpan}`,
    gridRow: `row 1`, // Assuming status rows are always in the first grid row for visibility
  }
}

// Computed property to determine the number of columns
const gridColumns = computed(() => {
  let numColumns
  if (appSettingStore.isVerySmallScreen) {
    numColumns = Math.floor((scheduleStore.verySmallScreenSlotHours * 60) / scheduleStore.slotIntervalMinutes)
  } else if (appSettingStore.isSmallScreen) {
    numColumns = Math.floor((scheduleStore.smallScreenSlotHours * 60) / scheduleStore.slotIntervalMinutes)
  } else {
    numColumns = Math.floor((scheduleStore.mediumScreenSlotHours * 60) / scheduleStore.slotIntervalMinutes)
  }
  document.documentElement.style.setProperty('--text-size', numColumns < 4 ? '0.8em' : '1em')
  document.documentElement.style.setProperty('--text-small', numColumns < 4 ? '0.7em' : '0.8em')

  return `repeat(${numColumns}, minmax(0, 1fr))` // Returns the CSS grid-template-columns value
})

const gridPlacement = (gridStart, gridSpan) => {
  return {
    gridColumnStart: gridStart,
    gridColumnEnd: `span ${gridSpan}`,
    gridRowStart: 'auto',
    gridRowEnd: 'span 1', // Assuming each item occupies one row height-wise
  }
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
  let url = '' // Initialize url variable

  switch (item.type) {
    case 'show':
      url = `/shows/${item.content.slug}/`
      break
    case 'movie':
      url = `/movies/${item.content.slug}/`
      break
    case 'showEpisode':
      url = `/shows/${item.show.slug}/episodes/${item.slug}`
      break
    default:
      // Handle default case or do nothing
  }

  Inertia.visit(url) // Visit the dynamically created URL

}


function updateNowPlayingAndComingUpNext() {
  // Logic to update nowPlaying and comingUpNext based on the current time and show data
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

function openModal(modalName) {
  document.getElementById(modalName).showModal()
}
</script>

<style scoped>

.hover-gradient:hover {
  background: linear-gradient(to right, rgba(30, 58, 138, 0.2), rgba(30, 64, 175, 0.2));
}

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

.time-banner {
  background-color: rgba(0, 123, 255, 0.5); /* Example styling */
  text-align: center;
  border: 1px solid white; /* White border */
  padding: 10px;
}

.show-cell {
  display: flex; /* Ensure this is set to flex to control child elements with flex properties */
  flex-direction: column; /* Align children in a column */
  justify-content: center; /* Align items to the top */
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
  width: 100%;
  grid-gap: 10px;
}

.grid-item {
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid #ccc;
}


.status-row {
  display: grid;
  width: 100%;
  align-items: center;
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

/* Custom hover effect for the parent that affects children */
.show-cell:hover .gradient-on-hover {
  background-image: linear-gradient(to right, #06beb6, #48b1bf);
}

.now-playing, .coming-up-next {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 10px;
  border: 1px solid #ccc;
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