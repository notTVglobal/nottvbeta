<template>
  <div>
    <ScheduledReleaseDateTime :episode="episode" :can="can" :errors="errors" />
    <ReleaseDateTime :episode="episode" :can="can" :errors="errors" />
  </div>
</template>
<script setup>
import { useShowEpisodeStore } from '@/Stores/ShowEpisodeStore'
import { useUserStore } from '@/Stores/UserStore'
import ReleaseDateTime from '@/Components/Pages/ShowEpisodes/Elements/ScheduleReleaseDateTimeComponents/ReleaseDateTime.vue'
import ScheduledReleaseDateTime
  from '@/Components/Pages/ShowEpisodes/Elements/ScheduleReleaseDateTimeComponents/ScheduledReleaseDateTime.vue'

import { ref } from 'vue'
import dayjs from 'dayjs'
import utc from 'dayjs/plugin/utc'
import timezone from 'dayjs/plugin/timezone'

dayjs.extend(utc)
dayjs.extend(timezone)

const showEpisodeStore = useShowEpisodeStore()
const userStore = useUserStore()

const props = defineProps({
  episode: Object,
  can: Object,
  errors: Object,
})



let scheduledDateTime = ref('') // This will hold the selected date and time in ISO format
let releaseDateTime = ref('') // This will hold the selected date and time in ISO format

// Define a ref to store selected date and time received from DateTimePicker

let formattedReleaseDateTime = ref('') // This will display the formatted date and time
let formattedScheduledDateTime = ref('') // This will display the formatted date and time

let userReleaseDateTime = ref('') // This will display the date and time in the user's timezone
let userScheduledDateTime = ref('') // This will display the date and time in the user's timezone






// const updateReleaseDateTime = () => {
//   if (selectedReleaseDateTime.value) {
//     // Convert the selected date and time to the desired time zone
//     // const timeZone = 'UTC'; // Change this to your desired time zone
//     formattedReleaseDateTime.value = convertToTimeZone(
//         new Date(releaseDateTime),
//         userTimezone.value,
//     )
//
//   } else {
//     formattedReleaseDateTime.value = ''
//   }
// }

// const updateScheduledDateTime = () => {
//   if (selectedScheduledDateTime.value) {
//     // Convert the selected date and time to the desired time zone
//     // const timeZone = 'UTC'; // Change this to your desired time zone
//     formattedScheduledDateTime.value = convertToTimeZone(
//         new Date(scheduledDateTime),
//         userTimezone.value,
//     )
//   } else {
//     formattedScheduledDateTime.value = ''
//   }
// }
</script>