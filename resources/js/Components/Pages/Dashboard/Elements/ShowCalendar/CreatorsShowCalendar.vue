<template>


  <div v-if="userStore.isMobile" class="container mx-auto px-4 py-8 bg-blue-200 rounded-lg mb-8">
    <h1 class="text-center font-bold pb-6 mb-6 border-b-2 border-gray-500">Show Schedule</h1>
    <div class="w-full grid grid-cols-1 gap-4"
         style="grid-template-rows: auto repeat(6, minmax(100px, auto));">
      <!-- Placeholder for the time labels column (hidden on small screens, shown on medium and up) -->
      <div class="hidden md:block"></div>
      <div class="bg-gray-200 text-2xl font-semibold font-serif p-4 rounded-lg text-center md:hidden">
        {{ days[0] }}
      </div>
      <!-- Time labels and schedule items -->
      <template v-for="(time, timeIndex) in times" :key="`time-${timeIndex}`">
        <!--        <div-->
        <!--            class="bg-gray-200 h-full flex items-center justify-center text-2xl font-semibold font-serif p-4 rounded-lg md:hidden"-->
        <!--        >{{ time }}-->
        <!--        </div>-->
        <!-- Schedule items for each day -->
        <template v-for="(daySchedule, dayIndex) in adjustedSchedule" :key="`day-${dayIndex}-time-${timeIndex}`">
          <div v-if="dayIndex === 0"
               class="rounded-lg text-center bg-transparent flex flex-col items-center md:items-start">
            <!-- Display time only for today's schedule on small screens -->
            <div
                class="bg-gray-200 h-full flex items-center justify-center text-2xl font-semibold font-serif p-4 mb-4 rounded-lg md:hidden"
            >{{ time }}
            </div>
            <CalendarCard v-if="daySchedule[timeIndex]"
                          :content="daySchedule[timeIndex]?.content"
                          :type="daySchedule[timeIndex]?.type"
                          :startTime="formatHour(daySchedule[timeIndex]?.start_time)"
                          :isLive="dayIndex === 0 && timeIndex === 0"/>
          </div>
        </template>
      </template>
    </div>
  </div>

  <div v-else class="container mx-auto px-4 py-8 bg-blue-200 rounded-lg mb-8">
    <h1 class="text-center font-bold pb-6 mb-6 border-b-2 border-gray-500">Show Schedule</h1>
    <div class="w-full grid grid-cols-3 xl:grid-cols-[8rem_repeat(5,_minmax(0,_1fr))] gap-4"
         style="grid-template-rows: auto repeat(6, minmax(100px, auto));">
      <!-- Placeholder for the time labels column -->
      <div></div>
      <div class="xl:col-auto bg-gray-200 text-2xl font-semibold font-serif p-4 rounded-lg text-center" v-for="(day, index) in days"
           :key="day">{{ day }}

      </div>
      <!-- Time labels and schedule items -->
      <template v-for="(time, timeIndex) in times" :key="`time-${timeIndex}`">
        <div
            class="xl:col-auto bg-gray-200 h-full flex items-center justify-center text-2xl font-semibold font-serif p-4 rounded-lg"
            style="width: 8rem;">{{ time }}
        </div>
        <!-- Schedule items for each day -->
        <template v-for="(daySchedule, dayIndex) in adjustedSchedule" :key="`day-${dayIndex}-time-${timeIndex}`">
          <div v-if="dayIndex === 0 && timeIndex === 0"
               class="col-span-3 xl:col-auto rounded-lg text-center"
               :class="{'border-4 border-red-500': dayIndex === 0 && timeIndex === 0}">
            <CalendarCard v-if="daySchedule[timeIndex]"
                          :content="daySchedule[timeIndex]?.content"
                          :type="daySchedule[timeIndex]?.type"
                          :startTime="formatHour(daySchedule[timeIndex]?.start_time)"
                          :isLive="true"/>
          </div>
          <div v-else class="w-full rounded-lg text-center bg-transparent">
            <!-- Regular content for other items -->
            <!--            {{ daySchedule[timeIndex].type }}: {{ daySchedule[timeIndex].show?.name || daySchedule[timeIndex].movie?.name }}-->

            <CalendarCard v-if="daySchedule[timeIndex]"
                          :content="daySchedule[timeIndex]?.content"
                          :type="daySchedule[timeIndex]?.type"
                          :startTime="formatHour(daySchedule[timeIndex]?.start_time)"/>

          </div>

        </template>
      </template>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted, computed, onUnmounted, watch } from 'vue'
import { useUserStore } from '@/Stores/UserStore'
import { useScheduleStore } from '@/Stores/ScheduleStore'
import dayjs from 'dayjs'
import utc from 'dayjs/plugin/utc'
import timezone from 'dayjs/plugin/timezone'
import CalendarCard from '@/Components/Pages/Dashboard/Elements/ShowCalendar/CalendarCard'

dayjs.extend(utc)
dayjs.extend(timezone)

const userStore = useUserStore()
const scheduleStore = useScheduleStore()

const now = dayjs()
let intervalId
const triggerUpdate = ref(0) // Reactive property for triggering reactivity
const userTimezone = ref('UTC') // Default to 'UTC'

// Assuming userStore is reactive, watch it for changes
watch(() => userStore.timezone, (newTimezone) => {
  userTimezone.value = newTimezone || 'UTC'
  triggerUpdate.value++
}, {immediate: true})

// Format functions for display
const formatDate = (date) => dayjs.utc(date).tz(userTimezone.value).format('dddd, MMM D')
const formatHour = (date) => dayjs.utc(date).tz(userTimezone.value).format('ddd, h A')

// Reactive state to hold shows data
// To access the shows in your template, you can use a computed property or directly reference scheduleStore.shows
const showsState = computed(() => scheduleStore.fiveDaySixHourSchedule)


function findShowForSlot(day, time) {
  return adjustedSchedule.value.find((schedule) => {
    const scheduleDay = dayjs(schedule.start_time).format('dddd')
    const scheduleTime = dayjs(schedule.start_time).format('h A')
    return scheduleDay === day && scheduleTime === time
  })
}

// showsState is a ref that holds our fetched schedule data
const adjustedSchedule = computed(() => {
  return showsState.value.map(schedule => {
    // Use a default timezone or wait until userStore.timezone is loaded

    console.log(userStore.timezone)
    const adjustedStartTime = dayjs.utc(schedule.start_time).tz(userTimezone.value).format('h:mm A')
    const adjustedEndTime = dayjs.utc(schedule.end_time).tz(userTimezone.value).format('h:mm A')

    return {
      ...schedule,
      start_time: adjustedStartTime,
      end_time: adjustedEndTime,
      // Add other schedule adjustments as needed
    }
  })
})

// Generate times array dynamically
const generateTimes = () => {
  const times = []
  for (let i = 0; i < 6; i++) { // Next 6 hours
    times.push(dayjs().add(i, 'hour').format('h A')) // Increment by one hour
  }
  return times
}

// Generate an array of today and the next 4 days
const generateDays = () => {
  const days = []
  for (let i = 0; i < 5; i++) { // Today + next 4 days
    days.push(dayjs().add(i, 'day').format('dddd')) // Format as day of the week
  }
  return days
}

// Computed property to regenerate times dynamically
const times = computed(() => {
  console.log('Updating times due to trigger:', triggerUpdate.value) // This ensures the computed prop recalculates
  console.log(times)
  return generateTimes()
})

// Using a computed property to dynamically generate the days
const days = computed(() => {
  console.log('Updating days due to trigger:', triggerUpdate.value) // Ensures reactivity
  console.log(days)

  return generateDays()
})

console.log({ adjustedSchedule, times });


onMounted(() => {
  scheduleStore.fetchFiveDaySixHourSchedule() // Load shows when component mounts

  intervalId = setInterval(() => { // Set an interval to update the trigger every hour
    triggerUpdate.value++
  }, 1000 * 60 * 60) // 1 hour
})

onUnmounted(() => {
  clearInterval(intervalId)
})

// Determine if the current slot is for "Now Playing"
function isNowPlaying(dayIndex, timeIndex) {
  // Assuming the first time slot of the first day is "Now Playing"
  return dayIndex === 0 && timeIndex === 0
}

</script>
