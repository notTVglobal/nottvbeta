<template>
  <!-- Today view layout -->
  <div class="today-view container mx-auto px-4 py-8 flex flex-col">
    <div class="flex justify-between items-center mb-4">
      <button
          @click="scheduleStore.changeDay(-1)"
          class="bg-gray-100 hover:bg-gray-200 text-black p-2 rounded shadow"
      >
        &lt; Previous Day
      </button>
      <h2 class="text-3xl font-bold text-black">{{ dateMessage }}</h2>
      <button
          @click="scheduleStore.changeDay(1)"
          class="bg-gray-100 hover:bg-gray-200 text-black p-2 rounded shadow"
      >
        Next Day &gt;
      </button>
    </div>
    <button
        @click="scheduleStore.shiftHours(-6)"
        class="mb-4 bg-gray-100 hover:bg-gray-200 text-black py-2 rounded shadow"
    >
      &#8593; Back 6 Hours
    </button>

    <div class="flex flex-col gap-4 flex-grow">
      <div v-for="(hour, index) in scheduleStore.nextSixHours" :key="hour.toString()">

        <!-- Time Segment Label -->
        <div v-if="index === 0 || getTimeSegment(hour).segment !== getTimeSegment(scheduleStore.nextSixHours[index - 1]).segment"
             :class="getTimeSegment(hour).color"
             class="mb-4 p-2 text-black text-center font-bold text-2xl rounded shadow">
          {{ getTimeSegment(hour).segment }}
        </div>

        <!-- Content for the current hour -->
        <template v-for="item in upcomingContent">
          <div v-if="isWithinCurrentHour(item, hour)" :key="item.id"
               :class="getTimeSegment(new Date(item.start_time)).color" class="p-4 rounded-lg shadow">
            <div class="flex flex-row">
              <div class="font-bold text-black">{{ formatHour(new Date(item.start_time)) }}</div>
              <div class="ml-4">
                <button @click.prevent="goToContentPage(item)">
                  <SingleImage :image="item?.content?.image" :alt="item?.content?.name" class="w-20 h-20"/>
                </button>
              </div>
              <div class="ml-4">
                <div class="text-gray-800 text-2xl tracking-wider mt-auto">
                  <button @click.prevent="goToContentPage(item)" class="text-left">
                    <span v-if="item.type === 'show'">{{ item?.content?.show?.name }}</span>
                    <span v-if="item.type === 'movie'">{{ item?.content?.name }}</span>
                  </button>
                </div>
                <div class="mt-2">
                  <div class="text-gray-700">
                  <span class="ml-1 text-xs font-semibold tracking-wide uppercase">
                    <span v-if="item.type === 'show'" class="text-green-500 bg-gray-900 px-2 py-1 rounded">show</span>
                    <span v-if="item.type === 'movie'" class="text-pink-500 bg-gray-900 px-2 py-1 rounded">movie</span>
                  </span>
                    <span v-if="item?.content?.show?.category?.name || item?.content?.category?.name"
                          class="ml-1 font-semibold text-xs uppercase tracking-wider text-yellow-600 bg-gray-900 px-2 py-1 rounded">
                    <span v-if="item.type === 'show' && item?.content?.show?.category?.name"
                          class="">{{ item?.content?.show?.category?.name }}</span>
                    <span v-if="item.type === 'movie' && item?.content?.subCategory?.name"
                          class="">{{ item?.content?.category?.name }}</span>
                  </span>
                    <span v-if="item?.content?.show?.subCategory?.name || item?.content?.subCategory?.name"
                          class="ml-1 text-xs font-semibold tracking-wide text-yellow-500 bg-gray-900 px-2 py-1 rounded">
                    <span v-if="item.type === 'show'" class="">{{ item?.content?.show?.subCategory?.name }}</span>
                    <span v-if="item.type === 'movie'" class="">{{ item?.content?.subCategory?.name }}</span>
                  </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </template>


        <!-- Fallback if no content is found for the current hour -->
        <div v-if="!isContentAvailableForHour(hour)" :class="getTimeSegment(hour).color"
             class="p-4 rounded-lg shadow">
          <div class="font-semibold">{{ formatHour(hour) }}</div>
          <div>Nothing scheduled.</div>
        </div>

        <!-- Dynamically insert the dateMessage for the next day if the hour is 11 PM -->
        <div v-if="scheduleStore.isElevenPM(hour) && scheduleStore.nextSixHours[index + 1]"
             class="mt-4 p-2 bg-blue-800 text-white rounded shadow">
          {{ generateDateMessage(addHours(hour, 1)) }}
        </div>
      </div>
    </div>

    <button
        @click="scheduleStore.shiftHours(6)"
        class="mt-4 bg-gray-100 hover:bg-gray-200 text-black py-2 rounded shadow"
    >
      &#8595; Forward 6 Hours
    </button>
  </div>
</template>

<script setup>
// Today view logic
// import { ref, computed } from 'vue'
import { useScheduleStore } from '@/Stores/ScheduleStore'
import {
  format,
  startOfHour,
  addHours,
  isToday,
  isYesterday,
  isTomorrow,
  startOfDay,
  isWithinInterval,
  isSameDay,
} from 'date-fns'
import { storeToRefs } from 'pinia'
import { computed, onMounted, ref, watch } from 'vue'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'
import { Inertia } from '@inertiajs/inertia'

const scheduleStore = useScheduleStore()
const { upcomingContent, dateMessage } = storeToRefs(scheduleStore);

const selectedDay = ref(scheduleStore.selectedDay);
const weeklyContent = computed(() => scheduleStore.weeklyContent);

watch(selectedDay, (newValue) => {
  scheduleStore.setSelectedDay(newValue);
  // If necessary, trigger other actions when selectedDay changes
});

function isWithinCurrentHour(item, hour) {
  const startOfCurrentHour = startOfHour(hour)
  const endOfCurrentHour = addHours(startOfCurrentHour, 1)
  const contentStartTime = new Date(item.start_time)
  return contentStartTime >= startOfCurrentHour && contentStartTime < endOfCurrentHour;
}

// Assuming `upcomingContent` is a computed property that already filters
// content within a 6-hour window from `scheduleStore.viewingWindowStart`
function isContentAvailableForHour(hour) {
  // Convert the given hour to the start and end of that hour block
  const startOfCurrentHour = startOfHour(hour)
  const endOfCurrentHour = addHours(startOfCurrentHour, 1)

  // Check if any item in `upcomingContent` starts within this hour block
  return upcomingContent.value.some(item => {
    const contentStartTime = new Date(item.start_time)
    return contentStartTime >= startOfCurrentHour && contentStartTime < endOfCurrentHour
  })
}

function formatHour(date) {
  return format(date, 'h aaaa')
}

function getTimeSegment(hour) {
  const hourOfDay = hour.getHours()
  if (hourOfDay >= 4 && hourOfDay < 6) return {segment: 'Early Morning', color: 'bg-gray-200'}
  if (hourOfDay >= 6 && hourOfDay < 12) return {segment: 'Morning', color: 'bg-yellow-200'}
  if (hourOfDay >= 12 && hourOfDay < 17) return {segment: 'Afternoon', color: 'bg-green-200'}
  if (hourOfDay >= 17 && hourOfDay < 20) return {segment: 'Prime Time', color: 'bg-red-200'}
  if (hourOfDay >= 20 && hourOfDay < 23) return {segment: 'Late Prime Time', color: 'bg-purple-200'}
  if (hourOfDay >= 23 || hourOfDay < 1) return {segment: 'Late Night', color: 'bg-blue-200'}
  if (hourOfDay >= 1 && hourOfDay < 4) return {segment: 'Overnight', color: 'bg-indigo-200'}
  return {segment: '', color: ''} // Default case
}

// A computed property to determine the starting segment for the displayed hours
const startingSegment = computed(() => scheduleStore.nextSixHours.value.length > 0 ? getTimeSegment(scheduleStore.nextSixHours.value[0]) : null)

function generateDateMessage(date) {
  // This method mirrors the logic in the store's getter but accepts any date.
  const startDay = startOfDay(date)
  const formattedDate = format(startDay, 'EEEE, MMMM do')
  if (isToday(startDay)) {
    return `Today - ${formattedDate}`
  } else if (isYesterday(startDay)) {
    return `Yesterday - ${formattedDate}`
  } else if (isTomorrow(startDay)) {
    return `Tomorrow - ${formattedDate}`
  } else {
    return formattedDate
  }
}

const goToContentPage = (item) => {
  if (item.type === 'show') {
    Inertia.visit(`/shows/${item.content.show.slug}`)
  } else if (item.type === 'movie') {
    Inertia.visit(`/movies/${item.content.slug}`)
  }
}

onMounted(async () => {
  // await scheduleStore.fetchTodaysContent()
  await scheduleStore.preloadWeeklyContent()
})

</script>

<style scoped>
/* Styles specific to today view */
</style>