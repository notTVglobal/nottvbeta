<template>
  <div>
    <DatePicker v-model="date" mode="dateTime" :attributes='attrs' :rules="rules"/>

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
});

const emits = defineEmits(['date-time-selected']);

let date = ref(props.date ? props.date : dayjs().tz(userStore.timezone).format('YYYY-MM-DDTHH:mm:ssZ'));

const calendar = ref(null);

// Customize time picker options to show only :00 and :30 minutes
const timePickerOptions = {
  step: 30, // Step in minutes, to show :00 and :30
  round: 30, // Round to nearest step
  start: '00:00', // Start time
  end: '23:30', // End time
};

const attrs = ref([
  {
    key: 'today',
    highlight: true,
    dates: new Date(),
  },
])

const rules = ref({
  minutes: {interval: 30},
})

// Watch for changes in selected dateTime emit it
watch(date, (newDate) => {
  emits('date-time-selected', {date: newDate});
});

onMounted(() => {
  if (!props.date) {
    date.value = dayjs().tz(userStore.timezone).format('YYYY-MM-DDTHH:mm:ssZ');
  }
});
</script>
