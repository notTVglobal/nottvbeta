<template>
  <div>
    <DatePicker
        v-model="date"
        mode="date"
        :timezone="effectiveTimezone"
        view="weekly"
        :disabled-dates="disabledDays"
        expanded
    />

  </div>
</template>

<script setup>
import { ref, defineProps, defineEmits, watch } from 'vue'
import { DatePicker } from 'v-calendar'
import 'v-calendar/style.css'
import { useUserStore } from '@/Stores/UserStore'
import dayjs from 'dayjs'
import utc from 'dayjs/plugin/utc'
import timezone from 'dayjs/plugin/timezone'

const userStore = useUserStore()

dayjs.extend(utc)
dayjs.extend(timezone)

const props = defineProps({
  date: null,
  timezone: String,
  disabledDays: {
    type: Array,
    default: () => [], // Default to an empty array if not provided
  },
});

const emits = defineEmits(['date-time-selected']);

const effectiveTimezone = ref(props.timezone || userStore.timezone)

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

// Watch for changes in the timezone prop and update the effective timezone
watch(
    () => props.timezone,
    (newTimezone) => {
      console.log('Timezone prop changed:', newTimezone);
      effectiveTimezone.value = newTimezone || userStore.timezone;
      // Update selectedDate to reflect the new timezone
      date.value = dayjs(date.value).tz(effectiveTimezone.value).startOf('minute').format('YYYY-MM-DDTHH:mm:ssZ');
    }
);
</script>
