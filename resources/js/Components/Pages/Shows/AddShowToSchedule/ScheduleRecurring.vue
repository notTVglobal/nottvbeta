<template>
  <!-- Steps Header for recurring shows -->
  <ul v-if="currentStep <= 5 && currentStep !== 0" class="steps w-full">
    <li @click.prevent="goToStep(1)" class="step cursor-pointer"
        :class="{ 'step-primary': currentStep >= 1 && currentStep !== 6}">
      <div>&nbsp;</div>
      <div :class="{ 'text-primary': currentStep === 1 }">Choose Days</div>
      <div :class="{ 'text-primary': currentStep === 1 }">{{ abbreviatedDaysOfWeekOrdered }}&nbsp;</div>
    </li>
    <li @click.prevent="goToStep(2)" class="step cursor-pointer"
        :class="{ 'step-primary': currentStep >= 2 }">
      <div>&nbsp;</div>
      <div :class="{ 'text-primary': currentStep === 2 }">Start time</div>
      <div :class="{ 'text-primary': currentStep === 2 }">{{ formattedStartTime }}&nbsp;</div>
    </li>
    <li @click.prevent="goToStep(3)" class="step cursor-pointer"
        :class="{ 'step-primary': currentStep >= 3 }">
      <div>&nbsp;</div>
      <div :class="{ 'text-primary': currentStep === 3 }">Duration</div>
      <div :class="{ 'text-primary': currentStep === 3 }"> {{ formattedDuration }}&nbsp;</div>
    </li>
    <li @click.prevent="goToStep(4)" class="step cursor-pointer"
        :class="{ 'step-primary': currentStep >= 4 }">
      <div>&nbsp;</div>
      <div :class="{ 'text-primary': currentStep === 4 }">Start date</div>
      <div :class="{ 'text-primary': currentStep === 4 }">{{ formattedStartDate }}&nbsp;</div>
    </li>
    <li @click.prevent="goToStep(5)" class="step cursor-pointer"
        :class="{ 'step-primary': currentStep >= 5 }">
      <div>&nbsp;</div>
      <div :class="{ 'text-primary': currentStep === 5 }">End date</div>
      <div :class="{ 'text-primary': currentStep === 5 }">{{ formattedEndDate }}&nbsp;</div>
    </li>
  </ul>

  <div :class="{'mt-6 pt-6': currentStep !== 6}">

    <div v-if="currentStep === 1">
      <!-- Step 1 content -->
      <div class="mb-2">1. Choose days of the week.</div>
      <label v-for="day in ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']"
             :key="day"
             class="ml-4 cursor-pointer">
        <input type="checkbox" v-model="daysOfWeek" :value="day" @change="updateDaysOfWeek(day)" class="cursor-pointer checkbox"> <span
          class="pl-1">{{ day }}</span>
      </label>
    </div>
    <div v-if="currentStep === 2">
      <!-- Step 2 content -->
      <div class="mb-2">2. Choose start time</div>
      <div class="flex items-center gap-2 text-black bg-white dark:bg-gray-800 dark:text-white">
        <!-- Hour selection -->
        <select v-model="startTime.hour"
                @change="updateStartTime('durationHour', $event.target.value)"
                class="form-select select select-bordered text-black bg-white dark:bg-gray-800 dark:text-white">
          <option v-for="hour in hours" :key="hour" :value="hour">{{ hour }}</option>
        </select>

        <!-- Minute selection -->
        <select v-model="startTime.minute"
                @change="updateStartTime('durationMinute', $event.target.value)"
                class="form-select select select-bordered  text-black bg-white dark:bg-gray-800 dark:text-white">
          <option value="00">00</option>
          <option value="30">30</option>
        </select>

        <!-- AM/PM selection -->
        <select v-model="startTime.meridian"
                @change="updateStartTime('meridian', $event.target.value)"
                class="form-select select select-bordered text-black bg-white dark:bg-gray-800 dark:text-white ">
          <option value="AM">AM</option>
          <option value="PM">PM</option>
        </select>
      </div>
    </div>
    <div v-if="currentStep === 3">
      <!-- Step 3 content -->
      <div class="mb-2">3. Choose duration (maximum 3 hours)</div>
      <div class="flex items-center gap-2">
        <select v-model="durationHour"
                @change="updateDuration('durationHour', $event.target.value)"
                class="select select-bordered text-black bg-white dark:bg-gray-800 dark:text-white">
          <option value="0">0 hours</option>
          <option value="1">1 hour</option>
          <option value="2">2 hours</option>
          <option value="3">3 hours</option>
        </select>
        <select v-model="durationMinute"
                @change="updateDuration('durationMinute', $event.target.value)"
                class="select select-bordered text-black bg-white dark:bg-gray-800 dark:text-white">
          <option v-for="option in minuteOptions" :key="option" :value="option">{{ option }} minutes</option>
        </select>
      </div>
    </div>
    <div v-if="currentStep === 4">
      <!-- Step 4 content -->
      <div class="mb-2">4. Choose start date</div>
      <DatePicker :date="startDate"
                  :timezone="props.timezone"
                  :disabledDays="disabledDays"
                  @date-time-selected="handleStartDateSelected"/>
    </div>
    <div v-if="currentStep === 5">
      <!-- Step 5 content -->
      <div class="mb-2">5. Choose end date (cannot be longer than 3 months, so 3 months is pre-set)</div>
      <DatePicker :date="endDate"
                  :timezone="props.timezone"
                  :disabledDays="disabledDays"
                  @date-time-selected="handleEndDateSelected"/>
    </div>
  </div>
</template>
<script setup>
import { computed, ref, watch } from 'vue'
import DatePicker from '@/Components/Global/Calendar/DatePicker.vue'
import dayjs from 'dayjs'

const props = defineProps({
  currentStep: Number,
  form: Object,
  timezone: String,
});

const emits = defineEmits(['update-form', 'go-to-step']);

const daysOfWeek = ref(props.form.daysOfWeek || [])
const startTime = ref(props.form.startTime || { hour: '12', minute: '00', meridian: 'AM' })
const startDate = ref(props.form.startDate || '')
const endDate = ref(props.form.endDate || '')
const durationHour = ref(props.form.durationHour || '0')
const durationMinute = ref(props.form.durationMinute || '00')

watch(() => props.form, (newForm) => {
  daysOfWeek.value = newForm.daysOfWeek || []
  startTime.value = newForm.startTime || { hour: '12', minute: '00', meridian: 'AM' }
  startDate.value = newForm.startDate || ''
  endDate.value = newForm.endDate || ''
  durationHour.value = newForm.durationHour || '0'
  durationMinute.value = newForm.durationMinute || '00'
}, { immediate: true })

const hours = Array.from({ length: 12 }, (_, i) => i + 1); // [1, 2, ..., 12]

const formattedStartTime = computed(() => {
  if (!startTime.value || !startTime.value.hour || !startTime.value.minute || !startTime.value.meridian) {
    return 'No time selected'
  }

  let hour = parseInt(startTime.value.hour);
  const minute = startTime.value.minute.padStart(2, '0'); // Ensure minute is two digits
  const meridian = startTime.value.meridian;

  if (hour > 12) {
    hour = hour - 12;
  } else if (hour === 0) {
    hour = 12;
  }

  return `${hour}:${minute} ${meridian}`;
});

const formattedDuration = computed(() => {
  let display = `${durationHour.value} hour${durationHour.value === '1' ? '' : 's'}`
  if (durationHour.value === '0') {
    display = '30 minutes' // Display '30 minutes' for '0 hours' selection
  } else if (durationMinute.value === '30' && durationHour.value !== '0') {
    display += ' and 30 minutes' // Append 'and 30 minutes' for selections other than '0 hours'
  }
  return display
})

// const formattedDuration = computed(() => {
//   const hours = Number(durationHour.value);
//   const minutes = Number(durationMinute.value);
//   return `${hours} hours ${minutes} minutes`;
// })

const formattedStartDate = computed(() => {
  if (!startDate.value) return 'No date selected'
  return dayjs(startDate.value).tz(props.timezone).format('ddd MMM D YYYY') // Formats to "Wed Feb 21 2024"
})

const formattedEndDate = computed(() => {
  if (!endDate.value) return ''
  return dayjs(endDate.value).format('ddd MMM D YYYY') // Formats to "Wed Feb 21 2024"
})

// Define the order of days
const daysOrder = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']

// Mapping full day names to their abbreviations
const dayAbbreviations = {
  'Sunday': 'Su',
  'Monday': 'M',
  'Tuesday': 'Tu',
  'Wednesday': 'W',
  'Thursday': 'Th',
  'Friday': 'F',
  'Saturday': 'Sa',
}

// Define the mapping from day names to their numerical values (0 = Sunday, 6 = Saturday)
const dayNameToNumber = {
  'Sunday': 1,
  'Monday': 2,
  'Tuesday': 3,
  'Wednesday': 4,
  'Thursday': 5,
  'Friday': 6,
  'Saturday': 7,
}

// Computed property to get the abbreviated days of the week in the correct order
const abbreviatedDaysOfWeekOrdered = computed(() => {
  if (!daysOfWeek.value || !Array.isArray(daysOfWeek.value)) return 'No days selected'
  const sortedSelectedDays = daysOfWeek.value
      .map(day => ({day, index: daysOrder.indexOf(day)}))
      .sort((a, b) => a.index - b.index)
      .map(sortedDay => dayAbbreviations[sortedDay.day])
  return sortedSelectedDays.join(', ')
})

function goToStep(step) {
  emits('go-to-step', step);
}


function handleStartDateSelected(date) {
  // startDate.value = date.date; // Update the startDate ref
  // const updatedForm = { ...props.form, startDate: date.date };
  // emits('update-form', updatedForm);
  // updateEndDate(date);

  startDate.value = dayjs(date.date).format();
  console.log('Selected date:', startDate.value);
  console.log('Timezone:', props.timezone);
  emitUpdatedForm();
  updateEndDate(startDate.value);
}

// Watch for changes in props.form.startDate and update startDate ref accordingly
watch(() => props.form.startDate, (newVal) => {
  startDate.value = newVal;
  updateEndDate(newVal);
});

function handleEndDateSelected(date) {
  // endDate.value = date.date; // Update the endDate ref
  // const updatedForm = { ...props.form, endDate: date.date };
  // emits('update-form', updatedForm);

  endDate.value = date.date;
  emitUpdatedForm();
}

function updateEndDate(startDate) {
  if (!startDate || !daysOfWeek.value.length) return;

  const startDateDayjs = dayjs(startDate).tz(props.timezone);
  const threeMonthsLater = startDateDayjs.add(3, 'month').startOf('day');

  let nearestDay;
  let minDiff = Infinity;

  daysOfWeek.value.forEach(day => {
    const dayNumber = dayNameToNumber[day];
    let candidateDate = threeMonthsLater.day(dayNumber);

    // If candidateDate is before or equal to threeMonthsLater, find the next occurrence of the day
    if (candidateDate.isBefore(threeMonthsLater)) {
      candidateDate = candidateDate.add(1, 'week');
    }

    const diff = candidateDate.diff(threeMonthsLater, 'day');

    if (diff < minDiff) {
      minDiff = diff;
      nearestDay = candidateDate;
    }
  });

  if (nearestDay) {
    // endDate.value = nearestDay.toDate();
    // const updatedForm = { ...props.form, endDate: endDate.value };
    // emits('update-form', updatedForm);

    endDate.value = nearestDay.toDate();
    emitUpdatedForm();
  }
}


function updateDaysOfWeek(day) {
  const updatedDays = [...props.form.daysOfWeek];
  const index = updatedDays.indexOf(day);
  if (index === -1) {
    updatedDays.push(day);
  } else {
    updatedDays.splice(index, 1);
  }

  // Reset startDate and endDate when days of the week change
  startDate.value = '';
  endDate.value = '';

  const updatedForm = {
    ...props.form,
    daysOfWeek: updatedDays,
    startDate: '',
    endDate: '',
  };
  emits('update-form', updatedForm);
}

function updateStartTime(field, value) {
  const updatedForm = { ...props.form, startTime: { ...props.form.startTime, [field]: value } };
  emits('update-form', updatedForm);
}

function updateDuration(field, value) {
  if (field === 'durationHour') {
    durationHour.value = value
  } else if (field === 'durationMinute') {
    durationMinute.value = value
  }
  const updatedForm = { ...props.form, durationHour: durationHour.value, durationMinute: durationMinute.value }
  emits('update-form', updatedForm)
}

// Compute the available minute options based on the selected hour
const minuteOptions = computed(() => {
  if (durationHour.value === '0') {
    return ['30']; // Only '30' minutes if '0 hours' is selected
  } else if (durationHour.value === '3') {
    return ['00']; // Only '00' minutes if '3 hours' is selected
  } else {
    return ['00', '30']; // Both '00' and '30' minutes options available otherwise
  }
});

// const disabledDays = (date) => {
//   return date < new Date().setDate(new Date().getDate() + 1); // Disable dates before tomorrow
// };

// Function to update the duration display text based on current selections
const updateDurationDisplay = () => {
  let display = `${durationHour.value} hour${durationHour.value === '1' ? '' : 's'}`
  if (durationHour.value === '0') {
    display = '30 minutes' // Display '30 minutes' for '0 hours' selection
  } else if (durationMinute.value === '30' && durationHour.value !== '0') {
    display += ' and 30 minutes' // Append 'and 30 minutes' for selections other than '0 hours'
  }
  const updatedForm = { ...props.form, durationDisplay: display }
  emits('update-form', updatedForm)
}

// Watch the durationMinute for changes to update the display accordingly
watch(() => durationMinute.value, () => {
  updateDurationDisplay()
}, {immediate: true})

// Automatically adjust the minute selection when the hour changes
watch(() => durationHour.value, (newHour) => {
  if (newHour === '1' || newHour === '2' || newHour === '3') {
    durationMinute.value = '00' // Force to '00' if '3 hours' is selected
  } else if (newHour === '0') {
    durationMinute.value = '30' // Force to '30' if '0 hours' is selected
  }
  // Update the duration display based on the new selections
  updateDurationDisplay()
}, {immediate: true})

// Initialize the display text based on the default selections
updateDurationDisplay()

function emitUpdatedForm() {
  emits('update-form', {
    ...props.form,
    startDate: startDate.value,
    endDate: endDate.value,
    daysOfWeek: daysOfWeek.value,
    durationHour: durationHour.value,
    durationMinute: durationMinute.value,
    timezone: props.timezone
  });
}

// Compute the disabled days based on the selected days of the week
const disabledDays = computed(() => {
  if (!daysOfWeek.value || !Array.isArray(daysOfWeek.value)) return []
  const selectedDayNumbers = daysOfWeek.value
      .map(day => ({ day, index: daysOrder.indexOf(day) }))
      .map(day => dayNameToNumber[day.day])
  const disabledDayNumbers = Object.values(dayNameToNumber).filter(dayNum => !selectedDayNumbers.includes(dayNum))
  return [
    {
      repeat: {
        weekdays: disabledDayNumbers,
      },
    },
  ]
})

</script>