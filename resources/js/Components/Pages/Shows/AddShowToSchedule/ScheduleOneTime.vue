<template>
  <!-- Steps Header for one-time shows -->
  <ul v-if="currentStep <= 5 && currentStep !== 0" class="steps w-full">
    <li @click.prevent="goToStep(1)" class="step cursor-pointer"
        :class="{ 'step-primary': currentStep >= 1 && currentStep !== 6}">
      <div>&nbsp;</div>
      <div :class="{ 'text-primary': currentStep === 1 }">Choose Start Day/Time</div>
      <div :class="{ 'text-primary': currentStep === 1 }">{{ formattedStartDate }}&nbsp;</div>
      <div :class="{ 'text-primary': currentStep === 1 }">{{ formattedStartTime }}&nbsp;</div>
    </li>
    <li @click.prevent="goToStep(2)" class="step cursor-pointer"
        :class="{ 'step-primary': currentStep >= 2 }">
      <div>&nbsp;</div>
      <div :class="{ 'text-primary': currentStep === 2 }">Set Duration</div>
      <div :class="{ 'text-primary': currentStep === 2 }"> {{ durationDisplay }}&nbsp;</div>
      <div>&nbsp;</div>
    </li>
  </ul>

  <div class="mt-6 pt-6">

    <div v-if="currentStep === 1" class="flex flex-row justify-center">
      <!-- Step 1 content -->
      <div class="flex flex-col">
        <div class="mb-2">1. Choose start date and time.</div>
        <DateTimePicker
            :date="startDate"
            :timezone="props.timezone"
            @date-time-selected="handleStartDateSelected"/>
      </div>
    </div>
  </div>

  <div v-if="currentStep === 2" class="flex flex-row justify-center">
    <!-- Step 3 content -->
    <div class="flex flex-col">
      <div class="mb-2">3. Choose duration (maximum 3 hours)</div>
      <div class="flex items-center gap-2">
        <select v-model="durationHour"
                @change="updateDurationHour($event.target.value)"
                class="select select-bordered bg-white dark:bg-gray-800 dark:text-white">
          <option value="0">0 hours</option>
          <option value="1">1 hour</option>
          <option value="2">2 hours</option>
          <option value="3">3 hours</option>
        </select>
        <select v-model="durationMinute"
                @change="updateDurationMinute($event.target.value)"
                class="select select-bordered bg-white dark:bg-gray-800 dark:text-white">
          <option v-for="option in minuteOptions" :key="option" :value="option">{{ option }} minutes</option>
        </select>
      </div>
    </div>
  </div>
</template>
<script setup>
import { computed, ref, watch } from 'vue'
import DateTimePicker from '@/Components/Global/Calendar/DateTimePicker.vue'
import dayjs from 'dayjs'
import utc from 'dayjs/plugin/utc' // Required for UTC support
import timezone from 'dayjs/plugin/timezone' // Required for timezone support

dayjs.extend(utc)
dayjs.extend(timezone)

const props = defineProps({
  currentStep: Number,
  form: Object,
  timezone: String,
})

const emits = defineEmits(['update-form', 'go-to-step'])

const startDate = ref(props.form.startDate)
const durationHour = ref(props.form.durationHour)
const durationMinute = ref(props.form.durationMinute)

watch(() => props.form, (newForm) => {
  startDate.value = newForm.startDate
  durationHour.value = newForm.durationHour
  durationMinute.value = newForm.durationMinute
}, {immediate: true})

const formattedStartDate = computed(() => {
  if (!startDate.value) return 'No date selected'
  return dayjs(startDate.value).format('ddd MMM D YYYY') // Formats to "Wed Feb 21 2024"
})

// Assuming form.startDate is in 'YYYY-MM-DD' format or a Date object
const formattedStartTime = computed(() => {
  if (!startDate.value) return 'No time selected'
  // Directly parse and format the date in local time without converting timezones
  // console.log('formattedStartTimeForOneTime time in: ' + form.startDate)
  // console.log('formattedStartTimeForOneTime time out: ' + timeIn)
  return dayjs(startDate.value).format('hh:mm A') // This should match the local time equivalent of the input
})

const durationDisplay = computed(() => {
  return `${durationHour.value} hours ${durationMinute.value} minutes`
})

function goToStep(step) {
  emits('go-to-step', step)
}

function handleStartDateSelected(date) {
  startDate.value = dayjs(date.date).format();
  console.log('Selected date:', startDate.value);
  console.log('Timezone:', props.timezone);
  emitUpdatedForm();
}

function updateDurationHour(hour) {
  durationHour.value = hour
  emitUpdatedForm()
}

function updateDurationMinute(minute) {
  durationMinute.value = minute
  emitUpdatedForm()
}

function emitUpdatedForm() {
  console.log('emitUpdatedForm Selected date:', startDate.value);
  emits('update-form', {
    ...props.form,
    startDate: startDate.value,
    durationHour: durationHour.value,
    durationMinute: durationMinute.value,
    durationDisplay: durationDisplay.value,
    timezone: props.timezone
  })
}

// Compute the available minute options based on the selected hour
const minuteOptions = computed(() => {
  if (durationHour.value === '0') {
    return ['30'] // Only '30' minutes if '0 hours' is selected
  } else if (durationHour.value === '3') {
    return ['00'] // Only '00' minutes if '3 hours' is selected
  } else {
    return ['00', '30'] // Both '00' and '30' minutes options available otherwise
  }
})

</script>