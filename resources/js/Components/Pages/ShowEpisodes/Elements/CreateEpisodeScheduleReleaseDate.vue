<template>
  <div v-if="props.episode.status.id < 7" class="mb-6">
    <label class="block mb-2 uppercase font-bold text-xs text-red-700"
           for="releaseDate"
    >
                                            <span v-if="props.episode.scheduled_release_dateTime">
                                                Scheduled Release Date</span>
      <span v-else>
                                                Schedule Release</span>
    </label>

    <div v-if="!cancelScheduledReleaseDate">
      <div v-if="props.episode.scheduled_release_dateTime && !selectedScheduledDateTime"
           class="mb-2">
        {{
          userStore.formatLongDateTimeFromUtcToUserTimezone(props.episode.scheduled_release_dateTime)
        }}
      </div>
      <div v-if="selectedScheduledDateTime"
           class="mb-2">
        {{ userStore.formatLongDateTimeFromUtcToUserTimezone(selectedScheduledDateTime.date) }}
      </div>
    </div>
    <div v-else class="mb-2">
      <span class="italic">Scheduled release cancelled. Please save the changes.</span>
    </div>

    <div class="flex flex-row flex-wrap space-x-2">
      <DateTimePickerSelect :date="props.episode.scheduled_release_dateTime"
                            @date-time-selected="handleScheduledDateTime"/>
      <!-- Display the selected date and time received from DateTimePicker -->
      <button v-if="props.episode.scheduled_release_dateTime"
              class="px-3 py-2 bg-blue-500 text-sm text-white font-semibold rounded-md"
              @click.prevent="cancelScheduledRelease">Cancel Release
      </button>
    </div>


  </div>

  <div v-if="props.episode.status.id === 7" class="mb-6">
    <label class="block mb-2 uppercase font-bold text-xs text-red-700"
           for="releaseDate"
    >
      Release Date
    </label>

    <div v-if="props.episode.release_dateTime && !selectedReleaseDateTime"
         class="mb-2">
      {{ userStore.formatLongDateTimeFromUtcToUserTimezone(props.episode.release_dateTime) }}
    </div>
    <div v-if="selectedReleaseDateTime"
         class="mb-2">
      {{ userStore.formatLongDateTimeFromUtcToUserTimezone(selectedReleaseDateTime.date) }}
    </div>


    <DateTimePickerSelect v-if="can.editShow" :date="props.episode.release_dateTime"
                          @date-time-selected="handleReleaseDateTime">
      <template v-slot:buttonName>
        Change date
      </template>
    </DateTimePickerSelect>
    <!-- Display the selected date and time received from DateTimePicker -->


  </div>
</template>
<script setup>
import { useShowEpisodeStore } from '@/Stores/ShowEpisodeStore'
import DateTimePickerSelect from '@/Components/Global/Calendar/DateTimePickerSelect'
import { format } from 'date-fns'
import { ref } from 'vue'

const showEpisodeStore = useShowEpisodeStore()

const props = defineProps({
  can: Object,
})

const userTimezone = ref('')

// TODO: convert this to the user's local time
releaseDateTime = props.episode.release_dateTime
scheduledDateTime = props.episode.scheduled_release_dateTime

let scheduledDateTime = ref('') // This will hold the selected date and time in ISO format
let releaseDateTime = ref('') // This will hold the selected date and time in ISO format

// Define a ref to store selected date and time received from DateTimePicker
let selectedReleaseDateTime = ref('')
let selectedScheduledDateTime = ref('')
let cancelScheduledReleaseDate = ref(false)

let formattedReleaseDateTime = ref('') // This will display the formatted date and time
let formattedScheduledDateTime = ref('') // This will display the formatted date and time

let userReleaseDateTime = ref('') // This will display the date and time in the user's timezone
let userScheduledDateTime = ref('') // This will display the date and time in the user's timezone

// Compare the converted date to the current date in the user's timezone
const currentDate = convertToTimeZone(
    new Date(),
    userTimezone.value,
)

const getUserTimezone = () => {
  // Use the Intl object to get the user's timezone
  userTimezone.value = Intl.DateTimeFormat().resolvedOptions().timeZone
}

const convertToTimeZone = (dateTime, userTimezone) => {
  return format(dateTime, 'yyyy-MM-dd HH:mm:ssXXX', {userTimezone})
}

if (releaseDateTime) {
  userReleaseDateTime.value = convertToTimeZone(
      new Date(releaseDateTime),
      userTimezone.value)
  console.log('user release dateTime: ' + userReleaseDateTime.value)
}

if (scheduledDateTime) {
  userScheduledDateTime.value = convertToTimeZone(
      new Date(scheduledDateTime),
      userTimezone.value)
  console.log('user scheduled dateTime: ' + userScheduledDateTime.value)
}


const handleReleaseDateTime = (newDate) => {
  let changedDate = convertToTimeZone(
      newDate.date,
      userTimezone.value,
  )
  console.log(changedDate)
  console.log(currentDate)
  // if release dateTime is in the future, alert and return
  if (changedDate > currentDate) {
    // selectedReleaseDateTime.value = props.episode.release_dateTime
    return alert('The selected release date and time is in the future! Please select a date/time in the past.')
  } else {
    // else proceed
    selectedReleaseDateTime.value = newDate
    releaseDateTime = newDate.date
    // console.log(releaseDateTime)
    updateReleaseDateTime()
    console.log(formattedReleaseDateTime.value)
    form.release_dateTime = formattedReleaseDateTime
    form.scheduled_release_dateTime = null
  }
}
const handleScheduledDateTime = (newDate) => {
  selectedScheduledDateTime.value = newDate
  scheduledDateTime = newDate.date
  // console.log(scheduledDateTime)
  updateScheduledDateTime()
  console.log(formattedScheduledDateTime.value)

  form.scheduled_release_dateTime = formattedScheduledDateTime
  form.release_dateTime = null
}

function cancelScheduledRelease() {
  cancelScheduledReleaseDate.value = true
  selectedScheduledDateTime.value = null
  form.scheduled_release_dateTime = null
}

const updateReleaseDateTime = () => {
  if (selectedReleaseDateTime.value) {
    // Convert the selected date and time to the desired time zone
    // const timeZone = 'UTC'; // Change this to your desired time zone
    formattedReleaseDateTime.value = convertToTimeZone(
        new Date(releaseDateTime),
        userTimezone.value,
    )

  } else {
    formattedReleaseDateTime.value = ''
  }
}

const updateScheduledDateTime = () => {
  if (selectedScheduledDateTime.value) {
    // Convert the selected date and time to the desired time zone
    // const timeZone = 'UTC'; // Change this to your desired time zone
    formattedScheduledDateTime.value = convertToTimeZone(
        new Date(scheduledDateTime),
        userTimezone.value,
    )
  } else {
    formattedScheduledDateTime.value = ''
  }
}
</script>