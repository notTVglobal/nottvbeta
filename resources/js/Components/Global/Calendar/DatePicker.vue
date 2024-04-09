<template>
  <div>
    <DatePicker v-model="date" mode="date" view="weekly" :disabled-dates="disabledDays" expanded/>

  </div>
</template>

<script setup>
import { ref, defineProps, defineEmits, watch, computed } from 'vue'
import { DatePicker } from 'v-calendar'
import 'v-calendar/style.css'

const props = defineProps({
  date: null,
  disabledDays: {
    type: Array,
    default: () => [], // Default to an empty array if not provided
  },
});

const emits = defineEmits();

let date = ref(props.date);
const calendar = ref(null);
//
// // Compute allowedDayNumbers only if allowedDays is provided and not empty
// const allowedDayNumbers = computed(() => {
//   if (props.disabledDays.length > 0) {
//     const dayMap = { 'Sunday': 0, 'Monday': 1, 'Tuesday': 2, 'Wednesday': 3, 'Thursday': 4, 'Friday': 5, 'Saturday': 6 };
//     return props.disabledDays.map(day => dayMap[day]);
//   }
//   return null; // Return null to indicate no restrictions
// });
//
// // Compute dateAttributes based on whether allowedDayNumbers is null
// const dateAttributes = computed(() => {
//   if (allowedDayNumbers.value !== null) {
//     return [
//       {
//         key: 'disabled',
//         dates: date => !allowedDayNumbers.value.includes(date.getDay()),
//       },
//       {
//         key: 'tomorrow',
//         highlight: true,
//         dates: new Date(new Date().setDate(new Date().getDate() + 1)),
//       },
//     ];
//   }
//   // Return an empty array or any default attributes when no allowedDays are provided
//   return [];
// });

// Watch for changes in selected dateTime emit it
watch([date], ([newDate]) => {
  emits('date-time-selected', {date: newDate});
});
</script>
