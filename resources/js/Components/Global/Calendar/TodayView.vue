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
        <h2 class="text-xl font-bold">{{ dateMessage }}</h2>
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
        <div v-for="(hour, index) in nextSixHours" :key="hour.toString()">
          <!-- Insert segment label before the first item of the segment -->
          <div v-if="index === 0 || getTimeSegment(hour).segment !== getTimeSegment(nextSixHours[index - 1]).segment"
               :class="getTimeSegment(hour).color"
               class="mb-4 p-2 text-black text-center font-semibold text-2xl rounded shadow">
            {{ getTimeSegment(hour).segment }}
          </div>
          <div :class="getTimeSegment(hour).color" class="p-4 rounded-lg shadow">
            <div class="font-semibold">{{ formatHour(hour) }}</div>
            <div class="text-gray-700 mt-2">No events planned.</div>
          </div>
          <!-- Dynamically insert the dateMessage for the next day if the hour is 11 PM -->
          <div v-if="scheduleStore.isElevenPM(hour) && nextSixHours[index + 1]"
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
  addHours,
  isToday,
  isYesterday,
  isTomorrow,
  startOfDay,
} from 'date-fns'
import { storeToRefs } from 'pinia'
import { computed } from 'vue'

const scheduleStore = useScheduleStore()
const { nextSixHours, dateMessage } = storeToRefs(scheduleStore);

function formatHour(date) {
  return format(date, 'h aaaa');
}

function getTimeSegment(hour) {
  const hourOfDay = hour.getHours();
  if (hourOfDay >= 4 && hourOfDay < 6) return { segment: 'Early Morning', color: 'bg-gray-200' };
  if (hourOfDay >= 6 && hourOfDay < 12) return { segment: 'Morning', color: 'bg-yellow-200' };
  if (hourOfDay >= 12 && hourOfDay < 17) return { segment: 'Afternoon', color: 'bg-green-200' };
  if (hourOfDay >= 17 && hourOfDay < 20) return { segment: 'Prime Time', color: 'bg-red-200' };
  if (hourOfDay >= 20 && hourOfDay < 23) return { segment: 'Late Prime Time', color: 'bg-purple-200' };
  if (hourOfDay >= 23 || hourOfDay < 1) return { segment: 'Late Night', color: 'bg-blue-200' };
  if (hourOfDay >= 1 && hourOfDay < 4) return { segment: 'Overnight', color: 'bg-indigo-200' };
  return { segment: '', color: '' }; // Default case
}

// A computed property to determine the starting segment for the displayed hours
const startingSegment = computed(() => nextSixHours.value.length > 0 ? getTimeSegment(nextSixHours.value[0]) : null);

function generateDateMessage(date) {
  // This method mirrors the logic in the store's getter but accepts any date.
  const startDay = startOfDay(date);
  const formattedDate = format(startDay, 'EEEE, MMMM do');
  if (isToday(startDay)) {
    return `Today - ${formattedDate}`;
  } else if (isYesterday(startDay)) {
    return `Yesterday - ${formattedDate}`;
  } else if (isTomorrow(startDay)) {
    return `Tomorrow - ${formattedDate}`;
  } else {
    return formattedDate;
  }
}

</script>

<style scoped>
/* Styles specific to today view */
</style>