<template>
  <div v-if="props.episode?.status && props.episode?.status?.id < 7" class="mb-6">
    <label class="block mb-2 uppercase font-bold text-xs text-red-700"
           for="scheduledReleaseDate"
    >
      <span v-if="showEpisodeStore.episode.scheduled_release_dateTime">
          Scheduled Release Date</span>
      <span v-else>
          Schedule Release</span>
    </label>

    <div class="mb-2">
      {{ showEpisodeStore.formattedScheduledReleaseDateTime }}
    </div>

    <div class="flex flex-row flex-wrap space-x-2">
      <DateTimePickerSelect :date="showEpisodeStore.episode.scheduleReleaseDateTimeInUserTz"
                            @date-time-selected="handleScheduledDateTime"/>
      <!-- Display the selected date and time received from DateTimePicker -->
      <button v-if="showEpisodeStore.episode.scheduled_release_dateTime"
              class="px-3 py-2 bg-blue-500 text-sm text-white font-semibold rounded-md"
              @click.prevent="cancelScheduledRelease">Cancel Release
      </button>
    </div>

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

dayjs.extend(utc)
dayjs.extend(timezone)

const showEpisodeStore = useShowEpisodeStore()
const userStore = useUserStore()
const notificationStore = useNotificationStore()

const props = defineProps({
  episode: Object,
  can: Object,
  errors: Object,
})

const handleScheduledDateTime = (newDate) => {
  let newDateInUserTz = dayjs(newDate.date).tz(userStore.timezone).format()

  console.log('handle Scheduled DateTime...')
  console.log('date user selected: ' + newDate.date)
  console.log('date converted to user time: ' + newDateInUserTz)
  console.log('current date: ' + userStore.userCurrentTime)

  // if release dateTime is in the past, alert and return
  if (newDateInUserTz < userStore.userCurrentTime) {
    notificationStore.setGeneralServiceNotification('Alert', 'The scheduled date and time is in the past! Please select a date/time in the future.')
  } else {
    // else proceed
    showEpisodeStore.setScheduledReleaseDateTime(newDateInUserTz)
  }
}

function cancelScheduledRelease() {
  showEpisodeStore.cancelScheduledRelease()
}
</script>