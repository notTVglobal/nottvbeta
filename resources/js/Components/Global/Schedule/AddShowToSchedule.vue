<template>
  <dialog id="addShowToScheduleModal" class="modal text-black">
    <div class="modal-box w-11/12 max-w-5xl text-black bg-white dark:bg-gray-800 dark:text-white">
      <button v-if="currentStep === 6"
              @click.prevent="closeModal"
              :disabled="currentStep !== totalSteps"
              class="btn justify-end">Close
      </button>

      <div class="flex flex-row justify-between">
        <div>
          <h2 v-if="currentStep !== 6" class="font-bold text-xl">
            <slot name="form-title">Default Form Title</slot>
            <span class="font-medium">({{ selectedTimezone }})</span>
          </h2>
        </div>
        <div>
          <button v-if="currentStep <= 5"
                  class="btn"
                  @click.prevent="closeModalAndReset">Cancel
          </button>
        </div>
      </div>

      <div>
        <h4 v-if="currentStep !== 6" class="font-medium text-lg">
          <slot name="form-description">Default Form Description</slot>
        </h4>
      </div>

      <div class="flex flex-col space-y-2 px-12 mt-6">
        <form @submit.prevent="submit">

          <div v-if="currentStep === 0" class="mt-6">
            <!-- Step 0 content -->
            <!-- Part 1: Confirm Timezone -->

            <div v-if="!timezoneConfirmed">
              <div class="mb-2 pb-6 text-primary text-center">Confirm Timezone</div>
              <div class="flex flex-row justify-center">
                <select id="timezone-select" v-model="selectedTimezone" @change="updateTimezone"
                        class="ml-2 rounded-lg select select-bordered bg-white dark:bg-gray-800 dark:text-white">
                  <option v-for="timezone in userStore.timezones" :key="timezone" :value="timezone">{{
                      timezone
                    }}
                  </option>
                </select>
              </div>
              <div class="flex flex-row justify-center pt-6">
                <button @click.prevent="confirmTimezone" class="btn btn-primary">Confirm Timezone</button>
              </div>
            </div>


            <!-- Part 2: Choose Schedule Type -->
            <div v-else>
              <div class="mb-2 text-primary text-center">Choose Schedule Type</div>
              <div class="flex justify-center space-x-4 mt-4">
                <button @click.prevent="selectScheduleType('one-time')"
                        class="btn btn-primary h-40 w-60 bg-indigo-500 hover:bg-indigo-700 text-white rounded-lg flex flex-col p-4">
                  <span class="text-lg">One-time Event</span>
                </button>
                <button @click.prevent="selectScheduleType('recurring')"
                        class="btn btn-primary h-40 w-60 bg-indigo-500 hover:bg-indigo-700 text-white rounded-lg flex flex-col p-4">
                  <span class="text-lg">Recurring Show</span>
                </button>
              </div>
              <div @click.prevent="timezoneConfirmed = false" class="mt-4 btn btn-sm">< change timezone</div>
            </div>


          </div>


          <div v-if="form.scheduleType === 'one-time'" class="py-6">
            <!-- Steps Header for one-time shows -->
            <ul v-if="currentStep <= 5 && currentStep !== 0" class="steps w-full">
              <li @click.prevent="goToStep(1)" class="step cursor-pointer"
                  :class="{ 'step-primary': currentStep >= 1 && currentStep !== 6}">
                <div>&nbsp;</div>
                <div :class="{ 'text-primary': currentStep === 1 }">Choose Start Day/Time</div>
                <div :class="{ 'text-primary': currentStep === 1 }">{{ formattedStartDate }}&nbsp;</div>
                <div :class="{ 'text-primary': currentStep === 1 }">{{ formattedStartTimeForOneTime }}&nbsp;</div>
              </li>
              <li @click.prevent="goToStep(2)" class="step cursor-pointer"
                  :class="{ 'step-primary': currentStep >= 2 }">
                <div>&nbsp;</div>
                <div :class="{ 'text-primary': currentStep === 2 }">Set Duration</div>
                <div :class="{ 'text-primary': currentStep === 2 }"> {{ form.durationDisplay }}&nbsp;</div>
                <div>&nbsp;</div>
              </li>
            </ul>

            <div class="mt-6 pt-6">

              <div v-if="currentStep === 1" class="flex flex-row justify-center">
                <!-- Step 1 content -->
                <div class="flex flex-col">
                  <div class="mb-2">1. Choose start date and time.</div>
                  <DateTimePicker :date="form.startDate" @date-time-selected="handleStartDateSelected"/>
                </div>
              </div>
            </div>

            <div v-if="currentStep === 2" class="flex flex-row justify-center">
              <!-- Step 3 content -->
              <div class="flex flex-col">
                <div class="mb-2">3. Choose duration (maximum 3 hours)</div>
                <div class="flex items-center gap-2">
                  <select v-model="form.durationHour"
                          class="select select-bordered bg-white dark:bg-gray-800 dark:text-white">
                    <option value="0">0 hours</option>
                    <option value="1">1 hour</option>
                    <option value="2">2 hours</option>
                    <option value="3">3 hours</option>
                  </select>
                  <select v-model="form.durationMinute"
                          class="select select-bordered bg-white dark:bg-gray-800 dark:text-white">
                    <option v-for="option in minuteOptions" :key="option" :value="option">{{ option }} minutes</option>
                  </select>
                </div>
              </div>
            </div>

          </div>

          <div v-if="form.scheduleType === 'recurring'" class="py-6">
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
                <div :class="{ 'text-primary': currentStep === 3 }"> {{ form.durationDisplay }}&nbsp;</div>
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


            <div class="mt-6 pt-6">

              <div v-if="currentStep === 1">
                <!-- Step 1 content -->
                <div class="mb-2">1. Choose days of the week.</div>
                <label v-for="day in ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']"
                       :key="day"
                       class="ml-4 cursor-pointer">
                  <input type="checkbox" v-model="form.daysOfWeek" :value="day" class="cursor-pointer checkbox"> <span
                    class="pl-1">{{ day }}</span>
                </label>
              </div>
              <div v-if="currentStep === 2">
                <!-- Step 2 content -->
                <div class="mb-2">2. Choose start time</div>
                <div class="flex items-center gap-2 text-black bg-white dark:bg-gray-800 dark:text-white">
                  <!-- Hour selection -->
                  <select v-model="form.startTime.hour"
                          class="form-select select select-bordered text-black bg-white dark:bg-gray-800 dark:text-white">
                    <option v-for="hour in hours" :key="hour" :value="hour">{{ hour }}</option>
                  </select>

                  <!-- Minute selection -->
                  <select v-model="form.startTime.minute"
                          class="form-select select select-bordered  text-black bg-white dark:bg-gray-800 dark:text-white">
                    <option value="00">00</option>
                    <option value="30">30</option>
                  </select>

                  <!-- AM/PM selection -->
                  <select v-model="form.startTime.meridian"
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
                  <select v-model="form.durationHour"
                          class="select select-bordered text-black bg-white dark:bg-gray-800 dark:text-white">
                    <option value="0">0 hours</option>
                    <option value="1">1 hour</option>
                    <option value="2">2 hours</option>
                    <option value="3">3 hours</option>
                  </select>
                  <select v-model="form.durationMinute"
                          class="select select-bordered text-black bg-white dark:bg-gray-800 dark:text-white">
                    <option v-for="option in minuteOptions" :key="option" :value="option">{{ option }} minutes</option>
                  </select>
                </div>
              </div>
              <div v-if="currentStep === 4">
                <!-- Step 4 content -->
                <div class="mb-2">4. Choose start date (tomorrow or later)</div>
                <DatePicker :date="form.startDate" :disabledDays="disabledDays"
                            @date-time-selected="handleStartDateSelected"/>
              </div>
              <div v-if="currentStep === 5">
                <!-- Step 5 content -->
                <div class="mb-2">5. Choose end date (cannot be longer than 3 months, so 3 months is pre-set)</div>
                <DatePicker :date="form.endDate" :disabledDays="disabledDays"
                            @date-time-selected="handleEndDateSelected"/>
              </div>
            </div>
          </div>

          <StepSixCongratulations v-if="currentStep === 6 && Object.keys(form.errors).length === 0" class="p-4">
            <template #header>Congratulations!</template>
            <template #subHeader>You've successfully scheduled your show on notTV!</template>
          </StepSixCongratulations>

          <div v-if="currentStep === 6 && Object.keys(form.errors).length > 0" class="p-4 text-red-700 w-full">
            <ul>
              <li v-for="(errorMessages, key) in form.errors" :key="key">
                <span v-if="Array.isArray(errorMessages)">{{ key }}:</span>
                <ul v-if="Array.isArray(errorMessages)">
                  <li v-for="(message, index) in errorMessages" :key="index">{{ message }}</li>
                </ul>
                <span v-else>{{ errorMessages }}</span>
              </li>
            </ul>
          </div>


          <div class="flex flex-row justify-between mt-12">
            <button v-if="(currentStep <= 5 && currentStep !== 0) || (currentStep === 6 && form.errors.length > 0)"
                    @click.prevent="goToPreviousStep"
                    :disabled="currentStep === 0"
                    class="btn">Back
            </button>
            <div></div>
            <div v-if="stepError" class="px-3 text-red-700" v-html="stepError"/>
            <div></div>
            <button v-if="currentStep <=5 && currentStep !== 0"
                    @click.prevent="goToNextStep"
                    :disabled="currentStep === totalSteps"
                    class="btn btn-primary text-white">Next
            </button>
            <button v-if="currentStep === 6"
                    @click.prevent="closeModal"
                    :disabled="currentStep !== totalSteps"
                    class="btn justify-self-end">Close
            </button>
          </div>


        </form>
      </div>
    </div>
  </dialog>

  <dialog id="confirmAddShowModal" class="modal">
    <div class="modal-box text-center bg-white dark:bg-gray-800 dark:text-white">
      <h3 class="font-bold text-lg">Are you sure you want to add your show to the schedule?</h3>
      <div class="modal-action flex flex-row justify-center">
        <button class="btn btn-success text-white px-6" @click.prvent="submit">Yes!</button>
        <button class="btn" @click.prvent="closeConfirmAddShowModal">Go back</button>
      </div>
    </div>
  </dialog>

</template>
<script setup>
import { Inertia } from '@inertiajs/inertia'
import { computed, onMounted, onUnmounted, reactive, ref, watch } from 'vue'
import { getCurrentInstance } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import dayjs from 'dayjs'
import utc from 'dayjs/plugin/utc' // Required for UTC support
import timezone from 'dayjs/plugin/timezone' // Required for timezone support
import { useUserStore } from '@/Stores/UserStore'
import { useScheduleStore } from '@/Stores/ScheduleStore'
import { useShowStore } from '@/Stores/ShowStore'
import { useNotificationStore } from "@/Stores/NotificationStore"
import DateTimePicker from '@/Components/Global/Calendar/DateTimePicker.vue'
import DatePicker from '@/Components/Global/Calendar/DatePicker.vue'
import StepSixCongratulations from '@/Components/Global/Schedule/StepSixCongratulations.vue'

import Label from '@/Jetstream/Label.vue'
import Button from '@/Jetstream/Button.vue'

const userStore = useUserStore()
const scheduleStore = useScheduleStore()
const showStore = useShowStore()
const notificationStore = useNotificationStore()

dayjs.extend(utc)
dayjs.extend(timezone)

let page = usePage().props

let props = defineProps({
  id: String,
  show: Object,
  // errors: Object,
})

// Access the global properties
const { proxy } = getCurrentInstance()

const startConfetti = () => {
  proxy.$confetti.start()
}

const stopConfetti = () => {
  proxy.$confetti.stop()
}

// const errors = ref(props.errors);

let endDate = ''
const formEndDate = ref('')
const selectedEndDate = ref(null)
const timezoneConfirmed = ref(false)
const modalVisible = ref(false)

const currentStep = ref(0)
const totalSteps = ref(6)
const stepError = ref('') // To store the error message for the current step


// Define the initial form state
const initialFormState = {
  scheduleType: '', // 'one-time' or 'recurring'
  daysOfWeek: [],
  startTime: {
    hour: '12',
    minute: '00',
    meridian: 'AM',
  },
  duration: '',
  durationHour: '0', // Initialize as '0' to represent the default selection
  durationMinute: '30', // Default to '30' minutes for '0' hours
  durationDisplay: '30 minutes', // Default display text
  startDate: '',
  endDate: '',
  errors: {},
};

const form = reactive({ ...initialFormState }); // on modal load, reset form.

// Function to reset the form
const resetForm = () => {
  Object.assign(form, initialFormState);
};

// Function to clear errors
const clearErrors = () => {
  form.errors = {};
};

function confirmTimezone() {
  timezoneConfirmed.value = true
}

function selectScheduleType(type) {
  form.scheduleType = type
  // Proceed to the next step based on the selection
  goToNextStep()
}

function goToNextStep() {
  // Clear any existing error message
  stepError.value = ''

  if (form.scheduleType === 'recurring') {
    if (currentStep.value === 1 && form.daysOfWeek.length === 0) {
      // If no days are selected and the current step is 1, set an error message
      stepError.value = 'Please select at least one day of the week.'
    } else if (currentStep.value === 4 && !form.startDate) {
      // If no start date is selected and the current step is 4, set an error message
      stepError.value = 'Please select a start date.'
    } else if (currentStep.value === 4 && dayjs(form.startDate).isBefore(dayjs().add(24, 'hour'))) {
      // If the start date is within the next 24 hours when the current step is 4, set an error message
      stepError.value = 'Start date must be at least 24 hours in the future.'
    } else if (currentStep.value === 5 && dayjs(form.endDate).isAfter(dayjs(form.startDate).add(3, 'months').add(1, 'week'))) {
      // Allow the end date to be up to one week beyond exactly three months from the start date
      // const latestEndDate = dayjs(form.startDate).add(3, 'months').add(1, 'week').format('ddd MMM D YYYY')
      stepError.value = `End date must be within 3 months and 1 week of the start date. <\/br>The latest possible end date is ${provisionalEndDate.value}. <\/br>This limitation is for the Beta Version of notTV only.`
    } else if (currentStep.value === 5) {
      document.getElementById('confirmAddShowModal').showModal()
    } else if (currentStep.value < totalSteps.value) {
      // Proceed to the next step if there are no errors
      currentStep.value++
    }
  } else if (form.scheduleType === 'one-time') {
    if (currentStep.value === 1 && !form.startDate) {
      // If no start date is selected and the current step is 1, set an error message
      stepError.value = 'Please select a start date.'
    } else if (currentStep.value === 1 && dayjs(form.startDate).isBefore(dayjs().add(1, 'day').startOf('day'))) {
      // If the start date is today or earlier when the current step is 1, set an error message
      stepError.value = 'Start date must be later than today.'
    } else if (currentStep.value === 2) {
      document.getElementById('confirmAddShowModal').showModal()
    } else if (currentStep.value < totalSteps.value) {
      // Proceed to the next step if there are no errors
      currentStep.value++
    }
  }

}

function goToPreviousStep() {
  if (form.scheduleType === 'recurring' && currentStep.value > 0) {
    currentStep.value--
  } else if (form.scheduleType === 'one-time' && currentStep.value > 2) {
    currentStep.value = 2
  } else if (form.scheduleType === 'one-time') {
    currentStep.value--
  }
}

function goToStep(num) {
  // Clear any existing error message
  stepError.value = ''
  if (form.scheduleType === 'recurring') {
    if (currentStep.value === 1 && form.daysOfWeek.length === 0) {
      // If no days are selected and the current step is 1, set an error message
      stepError.value = 'Please select at least one day of the week.'
    } else if ((currentStep.value === 4 && !form.startDate) || (num === 5 && !form.startDate)) {
      // If no start date is selected and the current step is 4, set an error message
      stepError.value = 'Please select a start date.'
    } else if ((currentStep.value === 4 && dayjs(form.startDate).isBefore(dayjs().add(1, 'day').startOf('day'))) || (num === 5 && dayjs(form.startDate).isBefore(dayjs().add(1, 'day').startOf('day')))) {
      // If the start date is today or earlier when the current step is 4, set an error message
      stepError.value = 'Start date must be later than today.'
    } else
      currentStep.value = num
  } else if (form.scheduleType === 'one-time') {
    if (currentStep.value === 1 && !form.startDate) {
      // If no start date is selected and the current step is 1, set an error message
      stepError.value = 'Please select a start date.'
    } else if (currentStep.value === 1 && dayjs(form.startDate).isBefore(dayjs().add(1, 'day').startOf('day'))) {
      // If the start date is today or earlier when the current step is 1, set an error message
      stepError.value = 'Start date must be later than today.'
    } else
      currentStep.value = num
  }

}

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
  // Sort the selected days based on their index in daysOrder
  const sortedSelectedDays = form.daysOfWeek
      .map(day => ({day, index: daysOrder.indexOf(day)}))
      .sort((a, b) => a.index - b.index)
      .map(sortedDay => dayAbbreviations[sortedDay.day]) // Map to abbreviations

  return sortedSelectedDays.join(', ')
})

// Watch for changes in the selected days of the week
watch(() => form.daysOfWeek, (newDays) => {
  // If currently on the first step and at least one day is selected, clear the error message
  if (currentStep.value === 1 && newDays.length > 0) {
    stepError.value = ''
  }
}, {immediate: true})


// Generate hours (1-12 for AM/PM format)
const hours = Array.from({length: 12}, (_, i) => (i + 1).toString().padStart(2, '0'))

// A computed property to format the time for display
let formattedStartTime = computed(() => {
  // Only format the time if all parts have been selected
  if (form.startTime.hour && form.startTime.minute && form.startTime.meridian) {
    return `${form.startTime.hour}:${form.startTime.minute} ${form.startTime.meridian}`
  } else {
    return '' // Default message
  }
})

// Compute the available minute options based on the selected hour
const minuteOptions = computed(() => {
  if (form.durationHour === '0') {
    return ['30'] // Only '30' minutes if '0 hours' is selected
  } else if (form.durationHour === '3') {
    return ['00'] // Only '00' minutes if '3 hours' is selected
  } else {
    return ['00', '30'] // Both '00' and '30' minutes options available otherwise
  }
})

// Function to update the duration display text based on current selections
const updateDurationDisplay = () => {
  let display = `${form.durationHour} hour${form.durationHour === '1' ? '' : 's'}`
  if (form.durationHour === '0') {
    display = '30 minutes' // Display '30 minutes' for '0 hours' selection
  } else if (form.durationMinute === '30' && form.durationHour !== '0') {
    display += ' and 30 minutes' // Append 'and 30 minutes' for selections other than '0 hours'
  }
  form.durationDisplay = display // Update the display text in the form state
}

// Watch the durationMinute for changes to update the display accordingly
watch(() => form.durationMinute, () => {
  updateDurationDisplay()
}, {immediate: true})

// Automatically adjust the minute selection when the hour changes
watch(() => form.durationHour, (newHour) => {
  if (newHour === '1' || newHour === '2' || newHour === '3') {
    form.durationMinute = '00' // Force to '00' if '3 hours' is selected
  } else if (newHour === '0') {
    form.durationMinute = '30' // Force to '30' if '0 hours' is selected
  }
  // Update the duration display based on the new selections
  updateDurationDisplay()
}, {immediate: true})

// Initialize the display text based on the default selections
updateDurationDisplay()

// Assuming form.startDate is in 'YYYY-MM-DD' format or a Date object
const formattedStartTimeForOneTime = computed(() => {
  if (!form.startDate) return ''
  // Directly parse and format the date in local time without converting timezones
  const timeIn = dayjs(form.startDate).format('hh:mm A')
  console.log('formattedStartTimeForOneTime time in: ' + form.startDate)
  console.log('formattedStartTimeForOneTime time out: ' + timeIn)
  return timeIn // This should match the local time equivalent of the input


})

const formattedStartDate = computed(() => {
  if (!form.startDate) return ''
  return dayjs(form.startDate).format('ddd MMM D YYYY') // Formats to "Wed Feb 21 2024"
})

let formattedEndDate = computed(() => {
  if (!form.endDate) return ''
  return dayjs(form.endDate).format('ddd MMM D YYYY') // Formats to "Wed Feb 21 2024"
})

// Compute the disabled days based on the selected days of the week
const disabledDays = computed(() => {
  const selectedDayNumbers = form.daysOfWeek.map(day => dayNameToNumber[day])
  const disabledDayNumbers = Object.values(dayNameToNumber).filter(dayNum => !selectedDayNumbers.includes(dayNum))
  // Return the structure expected by the DatePicker component for disabling days
  return [
    {
      repeat: {
        weekdays: disabledDayNumbers,
      },
    },
  ]
})

const provisionalEndDate = ref('')
// Handle date selection from DatePicker
const handleStartDateSelected = ({date}) => {
  stepError.value = '' // Clear any existing error messages
  const dayjsDate = dayjs(date)
  // form.startDate = dayjsDate.tz(userStore.canadianTimezone, true).format(); // Update the start date
  form.startDate = date // Update the start date
  console.log('handleStartDate form.startDate: ' + form.startDate)
  console.log('handleStartDate raw date: ' + date)

  // Calculate a rough endDate 3 months from the startDate
  endDate = dayjs(date).add(3, 'months')

  // If endDate's weekday differs from startDate's, adjust to the next occurrence of the same weekday
  const startWeekday = dayjs(date).day()
  while (endDate.day() !== startWeekday) {
    endDate = endDate.add(1, 'day')
  }

  // If the endDate is more than a week away from being exactly 3 months, adjust by subtracting days to get closer to the 3-month mark
  if (endDate.diff(dayjs(date).add(3, 'months'), 'week') > 1) {
    endDate = endDate.subtract(endDate.diff(dayjs(date).add(3, 'months'), 'days') % 7, 'days')

  }

  form.endDate = endDate
  console.log('handleStartDate form.endDate: ' + form.endDate)
  provisionalEndDate.value = form.endDate.format('ddd MMM D YYYY') // Update the endDate in the form
  console.log('provisionalEndDate: ' + provisionalEndDate.value)
}

// Handles end date selection
const handleEndDateSelected = ({date}) => {
  console.log('handleEndDate: ' + date)
  // form.endDate = dayjs(date).tz(userStore.timezone) // Directly set the end date from the selection
  form.endDate = date // Directly set the end date from the selection
  console.log('handleEndDate form.endDate: ' + form.endDate)
  console.log('NEW handleEndDate form.endDate: ' + selectedEndDate.value)
  // You might want to add validation or adjustment logic here as well
}

const submit = async () => {
  closeConfirmAddShowModal()

  try {
    const payload = showStore.preparePayload(form)
    console.log('==================================================')
    console.log('PAYLOAD:', payload)

    const response = await showStore.addShowToSchedule(payload)
    console.log('Success:', response)
    goToStep(6)
    startConfetti()
  } catch (error) {
    console.error('Error submitting form:', error)
    goToStep(6)
    if (error.response && error.response.data) {
      form.errors = error.response.data
      notificationStore.setToastNotification(form.errors, 'error', 10000)
    } else {
      form.errors = error.message || 'An unknown error occurred'
      notificationStore.setToastNotification(form.errors, 'error', 10000)
    }
  }
}

// async function submit() {
//   closeConfirmAddShowModal()
//   let formattedDuration = ''
//
//   console.log('==================================================')
//
//   // If we reach here, user confirmed. Proceed with submission.
//   if (form.scheduleType === 'one-time') {
//
//     // 1. Start date/time
//       // Parse the startDate as a Day.js object
//       console.log('SUBMIT start date in: ' + form.startDate)
//       // form.startDate = dayjs(form.startDate).tz(userStore.canadianTimezone, true).format()
//       // console.log('SUBMIT start date out: ' + form.startDate)
//       let startDate = dayjs(form.startDate).tz(userStore.canadianTimezone, true);
//       form.startDate = startDate.format()
//       console.log('SUBMIT start date formatted: ' + form.startDate);
//
//     // 2. Duration
//       // Ensure duration hours and minutes are treated as numbers
//       let durationHours = Number(form.durationHour);
//       let durationMinutes = Number(form.durationMinute);
//
//       form.duration = (durationHours * 60) + durationMinutes;
//
//     // 3. End date/time
//
//       // Adjust the endDate by setting the correct hour and minute, then adding the duration
//       endDate = startDate
//           .add(durationHours, 'hour')
//           .add(durationMinutes, 'minute');
//
//       // If you need to adjust for a specific timezone without changing the local time
//         // Note: The true flag in tz() might not be necessary depending on your exact needs for timezone handling
//         form.endDate = dayjs(endDate).tz(userStore.canadianTimezone, true).format();
//         console.log('SUBMIT end date out: ' + form.endDate);
//
//
//     // 4. Other values are null
//       form.startTime = null // not used for one-time
//       form.daysOfWeek = null // not used for one-time
//
//
//   }
//
//
//   if (form.scheduleType === 'recurring') {
//
//
//     // 1. Start date in, add start time
//       console.log('SUBMIT startDate in: ' + form.startDate);
//
//       // Assuming form.startTime.hour, form.startTime.minute are in correct format and form.startTime.meridian is either 'AM' or 'PM'
//       let hour = parseInt(form.startTime.hour) % 12;// Convert to 12-hour format
//       if (form.startTime.meridian === 'PM') hour += 12; // Convert PM to 24-hour format
//
//       // Parse the startDate and set the time
//       let startDate = dayjs(form.startDate).hour(hour).minute(form.startTime.minute);
//       form.startDate = dayjs(startDate).tz(userStore.canadianTimezone, true).format()
//     console.log('whats the start date? ' + form.startDate)
//       let newEndTime = dayjs(form.startDate).add(form.durationHour, 'hours').add(form.durationMinute, 'minutes')
//     form.endTime = newEndTime.format('HH:mm:ss')
//     console.log('NEW END TIME: ' + form.endTime);
//     // Extract the date part of form.endDate
//     let endDateOnly = dayjs(form.endDate).format('YYYY-MM-DD');
// // Combine endDateOnly with form.endTime to update the form.endDate
//     form.endDate = dayjs(endDateOnly + ' ' + form.endTime).format('YYYY-MM-DD HH:mm:ss');
//
//     let newFormattedEndDateTime = dayjs(form.endDate).tz(userStore.canadianTimezone, true).format()
//     console.log('NEW END DATETIME: ' + newFormattedEndDateTime);
//       form.endDate = newFormattedEndDateTime
//     console.log('CONFIRM END DATETIME: ' + form.endDate);
//     // Calculating endDate based on startDate and the duration
//     // let endDate = startDate.add(totalDurationMinutes, 'minute');
//
//     // Setting form.endDate and logging
//     // let endDate = form.endDate.format()
//     // form.endDate = endDate.format();
//
//
//     // 2. Start time (HH:MM:SS)
//       // formattedStartTime = startDate.format('HH:mm:ss');
//       form.startTime = startDate.format('HH:mm:ss'); // Use the desired format
//       console.log('SUBMIT start time formatted in dayjs: ' + form.startTime);
//
//     // 3. Duration
//       console.log('SUBMIT duration minute in: ' + form.durationMinute);
//       console.log('SUBMIT duration hour in: ' + form.durationHour);
//
//     // Correctly calculating total duration in minutes
//     let totalDurationMinutes = (parseInt(form.durationHour) * 60) + parseInt(form.durationMinute);
//     console.log('SUBMIT formatted duration in minutes: ' + totalDurationMinutes);
//
//       // // Calculate total duration in minutes
//       formattedDuration = (Number(form.durationHour) * 60) + Number(form.durationMinute);
//       form.duration = formattedDuration
//
//
//       // console.log('SUBMIT formatted duration in minutes: ' + form.duration);
//
//     // 4. End date, add end time (end date with HH:MM:SS = start time + duration)
//     //   // Ensure the initial timestamp is correctly parsed as a Day.js date object
//     //       if (!selectedEndDate) {
//     //         form.endDate = startDate.add(totalDurationMinutes, 'minutes')
//     //         console.log('no selected end date')
//     //       } else {
//     //         form.endDate = dayjs(selectedEndDate.value).add(totalDurationMinutes, 'minutes')
//     //         console.log('Selected end date!')
//     //       }
//
//     // Log the original values to ensure they're what you expect
//     // console.log('Original duration hour:', form.durationHour);
//     // console.log('Original duration minute:', form.durationMinute);
//     //       const durationHours = parseInt(form.durationHour, 10);
//     //       const durationMinutes = parseInt(form.durationMinute, 10);
//     // // Log the parsed values to confirm they've been correctly interpreted
//     // console.log('Parsed duration hour (as integer):', durationHours);
//     // console.log('Parsed duration minute (as integer):', durationMinutes);
//     //
//     //       form.endDate = dayjs(form.endDate)
//     //           .add(durationHours, 'hours')
//     //           .add(durationMinutes, 'minutes')
//     //           .format()
//     //       // form.duration = totalDurationMinutes
//     //       console.log('SUBMIT end date in: ' + form.endDate);
//
//           //
//           // let endDate = dayjs(form.endDate).hour(hour).minute(form.startTime.minute);
//           // console.log('SUBMIT end date formatted in dayjs: ' + endDate);
//
//       // Convert hour and minute from form.startTime to numbers
//       //     let newHour = Number(form.startTime.hour) % 12;
//       //     if (form.startTime.meridian === 'PM') newHour += 12; // Adjust for 24-hour format if PM
//
//       // Ensure duration hours and minutes are treated as numbers
//       //     let durationHours = Number(form.durationHour);
//       //     let durationMinutes = Number(form.durationMinute);
//
//       // Adjust the endDate by setting the correct hour and minute, then adding the duration
//       //     let endDate = dayjs(endDate)
//       //         .add(totalDurationMinutes, 'minutes')
//     //
//     // endDate.value = dayjs(endDate.value)
//     //     .add(totalDurationMinutes, 'minutes')
//     //
//     // console.log('NEW end date WITH MINUTES ADDED: ' + endDate.value);
//
//               // .add(durationMinutes, 'minute');
//
//       // If you need to adjust for a specific timezone without changing the local time
//       // Note: The true flag in tz() might not be necessary depending on your exact needs for timezone handling
//     // Adding duration directly to startDate to avoid confusion and ensure accuracy
//
//     //
//     // form.endDate = dayjs(endDate.value).format();
//     //       console.log('NEW ADJUSTED == form.endDate: ' + form.endDate);
//
//
//     // 5. Days of week
//
//
//
//   }
//
//   // Prepare the payload for the API based on the schedule type
//   const handleAddShow = async () => {
//     const payload = {
//       contentType: 'show',
//       contentId: props.show.id,
//       scheduleType: form.scheduleType,
//       startTime: form.startTime,
//       duration: form.duration,
//       // startDate: form.startDate,
//       startDate: form.startDate,
//       endDate: form.endDate,
//       daysOfWeek: form.scheduleType === 'recurring' ? form.daysOfWeek : [],
//       timezone: userStore.canadianTimezone,
//       // Include other relevant form data here
//     }
//
//     console.log('==================================================')
//     console.log('PAYLOAD: Content Type: ' + payload.contentType)
//     console.log('PAYLOAD: Content ID: ' + payload.contentId)
//     console.log('PAYLOAD: Formatted Start Date: ' + payload.startDate)
//     console.log('PAYLOAD: Formatted Start Time: ' + payload.startTime)
//     console.log('PAYLOAD: Formatted Duration in minutes: ' + payload.duration)
//     console.log('PAYLOAD: Formatted End Date: ' + payload.endDate)
//     console.log('PAYLOAD: Formatted Days of Week: ' + payload.daysOfWeek)
//     console.log('PAYLOAD: User Timezone: ' + userStore.canadianTimezone)
//
//     // Adjust the start and end time based on the selected time and meridian
//     // const adjustedStartTime = dayjs(`${form.startDate} ${payload.startTime}`, 'YYYY-MM-DD hh:mm A').toISOString()
//     // const durationInMinutes = parseInt(form.durationHour) * 60 + parseInt(form.durationMinute)
//     // const adjustedEndTime = dayjs(adjustedStartTime).add(durationInMinutes, 'minute').toISOString()
//
//     try {
//       // Replace '/api/schedule' with your actual API endpoint
//       const response = await axios.post('/api/schedule/addToSchedule', payload);
//
//       // Handle success - process response.data as needed
//       console.log('Success:', response.data);
//       goToStep(6);
//       startConfetti();
//       scheduleStore.savingToSchedule = true
//     } catch (error) {
//       // Handle errors
//       console.error('Error submitting form:', error);
//       goToStep(6); // Navigate to the error display step
//       // Populate `form.errors` with the error details for display
//       // Axios wraps the response error in `error.response`
//       if (error.response && error.response.data) {
//         form.errors.value = error.response.data;
//       } else {
//         form.errors.value = error.message || 'An unknown error occurred';
//       }
//     }
//   }
// }

function closeModal() {
  document.getElementById('addShowToScheduleModal').close()
  // Reset the form fields to their initial values
  resetForm()
  // Clear all validation errors
  clearErrors()
  stopConfetti()
  currentStep.value = 0
  // Inertia.visit(`/shows/${props.show.slug}/manage`)
}

function closeConfirmAddShowModal() {
  document.getElementById('confirmAddShowModal').close()
}

const closeModalAndReset = () => {
  currentStep.value = 0
  closeModal()
  // Inertia.visit(`/shows/${props.show.slug}/manage`)
}

// Watcher for currentStep to display Confetti
// watch(currentStep, (newVal) => {
//   if (newVal === 6) {
//     startConfetti()
//   }
// })

// Initialize selectedTimezone with the current value from userStore
const selectedTimezone = ref(userStore.canadianTimezone)

// Watch for changes in userStore's timezone and update selectedTimezone accordingly
watch(() => userStore.canadianTimezone, (newTimezone) => {
  selectedTimezone.value = newTimezone
  // dayjs.tz.setDefault(userStore.timezone);
})


// Function to handle the keydown event
const handleKeydown = (event) => {
  if (event.key === 'Escape') {
    console.log('ESC pressed, modal is open')
    stopConfetti()
    currentStep.value = 0
    Inertia.redirect(`/shows/${props.show.slug}/manage`)

  }
}

onMounted(() => {
  document.addEventListener('keydown', handleKeydown)
})

onUnmounted(() => {
  document.removeEventListener('keydown', handleKeydown)
})

// onMounted(async () => {
//   timezones.value = await getTimeZones(); // Fetch the list of timezones
// });

function updateTimezone() {
  // Update the timezone in your store
  userStore.setUserTimezone(selectedTimezone.value)
  // Optionally, send the updated timezone to your backend here
}

</script>