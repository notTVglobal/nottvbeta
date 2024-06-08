<template>

  <div v-if="props.episode?.status && props.episode?.status?.id === 7" class="mb-6">
    <label class="block mb-2 uppercase font-bold text-xs text-red-700"
           for="releaseDate"
    >
      Release Date
    </label>

    <div class="mb-2">
      {{ showEpisodeStore.formattedReleaseDateTime }}
    </div>

    <div v-if="errors.release_dateTime" v-text="errors.episode_number"
         class="text-xs text-red-600 mt-1"></div>


    <DateTimePickerSelect v-if="can.editShow" :date="showEpisodeStore.episode.release_dateTime"
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
import { useUserStore } from '@/Stores/UserStore'
import { useNotificationStore } from '@/Stores/NotificationStore'
import DateTimePickerSelect from '@/Components/Global/Calendar/DateTimePickerSelect'
import dayjs from 'dayjs'
import utc from 'dayjs/plugin/utc'
import timezone from 'dayjs/plugin/timezone'
import { ref } from 'vue'
import isSameOrAfter from 'dayjs/plugin/isSameOrAfter'

dayjs.extend(utc)
dayjs.extend(timezone)
dayjs.extend(isSameOrAfter)

const showEpisodeStore = useShowEpisodeStore()
const userStore = useUserStore()
const notificationStore = useNotificationStore()

const props = defineProps({
  episode: Object,
  can: Object,
  errors: Object,
})

let selectedReleaseDateTime = ref('')

const handleReleaseDateTime = (newDate) => {
  let newDateInUserTz = dayjs(newDate.date).tz(userStore.timezone).format()

  console.log('handle Release DateTime...')
  console.log('date user selected: ' + newDate.date)
  console.log('date converted to user time: ' + newDateInUserTz)
  console.log('current date: ' + userStore.userCurrentTime)
  // if release dateTime is in the future, alert and return
  if (dayjs(newDateInUserTz).isAfter(dayjs(userStore.userCurrentTime))) {
    notificationStore.setGeneralServiceNotification('Alert', 'The selected release date and time is in the future! Please select a date/time in the past.')

  } else {
    // else proceed
    showEpisodeStore.setReleaseDateTime(newDateInUserTz)
  }
}
</script>