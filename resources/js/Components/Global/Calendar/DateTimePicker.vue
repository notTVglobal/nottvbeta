<template>
  <div>
    <DatePicker v-model="selectedDate"
                mode="dateTime"
                :timezone="effectiveTimezone"
                :attributes='attrs'
                :rules="rules"
    />

  </div>
</template>

<script setup>
import { ref, defineProps, defineEmits, watch, onMounted } from 'vue'
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
})

const emits = defineEmits(['date-time-selected'])

const effectiveTimezone = ref(props.timezone || userStore.timezone)

const initializeDate = () => {
  const roundedNow = dayjs().minute(Math.floor(dayjs().minute() / 30) * 30).second(0).format();
  return props.date
      ? dayjs(props.date).tz(effectiveTimezone.value).startOf('minute').format('YYYY-MM-DDTHH:mm:ssZ')
      : dayjs(roundedNow).tz(effectiveTimezone.value).format('YYYY-MM-DDTHH:mm:ssZ');
};

const selectedDate = ref(initializeDate())

const attrs = ref([
  {
    key: 'today',
    dot: true,
    dates: dayjs().tz(effectiveTimezone.value).format(),
  },
])

const rules = ref({
  minutes: {interval: 30},
})

// Watch for changes in selected dateTime and emit it
watch(selectedDate, (newDate) => {
  // Set the timezone without changing the time
  let convertedNewDate = dayjs(newDate).tz(effectiveTimezone.value, true).format()

  console.log('DateTimePicker watch Selected date: ' + convertedNewDate)
  emits('date-time-selected', { date: convertedNewDate })
})

// Watch for changes in the timezone prop and update the effective timezone
watch(
    () => props.timezone,
    (newTimezone) => {
      console.log('Timezone prop changed:', newTimezone);
      effectiveTimezone.value = newTimezone || userStore.timezone;
      // Update selectedDate to reflect the new timezone
      selectedDate.value = dayjs(selectedDate.value).tz(effectiveTimezone.value).startOf('minute').format('YYYY-MM-DDTHH:mm:ssZ');
    }
);

onMounted(() => {
  console.log('Effective Timezone on mount: ', effectiveTimezone.value);
  console.log('onMounted Selected Date: ' + selectedDate.value)
});
</script>

