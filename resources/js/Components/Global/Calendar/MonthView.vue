<template>
  <div class="month-view">
    <!-- Month view layout -->
    <div class="container mx-auto px-4 py-8">
      <div class="flex justify-between items-center mb-4">
        <button @click="scheduleStore.subtractMonth" class="p-2 bg-gray-200 rounded-full hover:bg-gray-300">
          <!-- Previous Month -->
          <span>&lt;</span>
        </button>
        <span>{{ currentMonthName }} {{ currentYear }}</span>
        <button @click="scheduleStore.addMonth" class="p-2 bg-gray-200 rounded-full hover:bg-gray-300">
          <!-- Next Month -->
          <span>&gt;</span>
        </button>
      </div>
      <div class="grid grid-cols-7 gap-4 text-center">
        <!-- Day of the week headings -->
        <div class="font-bold" v-for="day in daysOfWeek" :key="day">{{ day }}</div>
        <!-- Days -->
        <div
            class="py-2 rounded-full hover:bg-blue-100 cursor-pointer"
            v-for="(day, index) in daysInMonth"
            :key="index"
            :class="{'bg-blue-200': isSelectedDay(day), 'text-gray-500': !isCurrentMonth(day)}"
            @click="selectDay(day)"
        >
          {{ day.getDate() }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// Month view logic
import { useScheduleStore } from '@/Stores/ScheduleStore';
import { getMonth, isSameDay, isToday as isTodayDate } from 'date-fns';
import { storeToRefs } from 'pinia'

const scheduleStore = useScheduleStore();
// const { currentMonthName, currentYear, daysInMonth, subtractMonth, addMonth, currentMonthIndex } = storeToRefs(scheduleStore);
const { currentMonthName, currentYear, daysInMonth } = storeToRefs(scheduleStore);

// const now = new Date();
// const currentMonth = ref(now);

const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

// Method to handle day selection
function selectDay(day) {
  scheduleStore.setSelectedDay(day); // Assuming you've added setSelectedDay action to your store
}

function isToday(day) {
  return isTodayDate(day);
}

function isSelectedDay(day) {
  return isSameDay(day, scheduleStore.selectedDay); // Use isSameDay for comparison
}

function isCurrentMonth(day) {
  // Since you're already tracking currentMonth in the store, ensure this function uses it directly
  return getMonth(day) === getMonth(scheduleStore.currentMonth); // Directly compare to the store's currentMonth
}

// const startDayOfMonth = computed(() => startOfMonth(currentMonth.value));
// const endDayOfMonth = computed(() => endOfMonth(currentMonth.value));

// const daysInMonth = computed(() =>
//     eachDayOfInterval({
//       start: startDayOfMonth.value,
//       end: endDayOfMonth.value,
//     })
// );

// const currentMonthName = computed(() => format(currentMonth.value, 'MMMM'));
// const currentYear = computed(() => getYear(currentMonth.value));

// function subtractMonth() {
//   currentMonth.value = subMonths(currentMonth.value, 1);
// }
//
// function addMonth() {
//   currentMonth.value = addMonths(currentMonth.value, 1);
// }

// function isToday(day) {
//   return isTodayDate(day);
// }
//
// function isCurrentMonth(day) {
//   return getMonth(day) === currentMonthIndex.value; // Use `.value` because `storeToRefs` makes it a ref
// }
</script>

<style scoped>
/* Styles specific to month view */
</style>