<template>
  <div v-if="teamStore.nextBroadcast && teamStore.nextBroadcastZoomLink && !teamStore.nextBroadcastIsOver"
       class="flex justify-center text-center text-black p-4 mx-5 bg-yellow-300 rounded-lg">

    <div class="space-x-4">
      <p class="text-lg md:text-xl leading-relaxed font-medium text-gray-900 dark:text-gray-200 p-3 rounded">
        <span v-if="!isBroadcastOpen">Join the next broadcast!</span>
        <span v-else>Join the meeting!</span>
      </p>
      <p v-if="!isBroadcastOpen" class="text-sm text-black">The link will appear 30 minutes before we go live.</p>
      <div class="flex justify-center">
        <ZoomLogo class="w-48 p-3"/>
      </div>
      <div v-if="isBroadcastOpen" class="flex flex-col justify-center items-center text-black">
        <!-- Display the Zoom link or other broadcast-related elements here -->
        <h2>The broadcast meeting room is currently open.</h2>
      </div>
      <div class="mt-2 space-x-2 space-y-2">
        <button v-if="isBroadcastOpen" @click="joinZoom"
                class="w-fit bg-blue-500 hover:bg-blue-700 text-white font-bold mt-2 py-2 px-4 rounded">
          Click to Join
        </button>
        <div v-if="!isBroadcastOpen">
          <NextBroadcastEmailReminderDialog/>
        </div>
        <button @click.prevent="shareZoomLink"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
          Click to Share This
        </button>
      </div>

    </div>
  </div>

</template>
<script setup>
import { computed, watchEffect } from 'vue'
import { useTeamStore } from '@/Stores/TeamStore'
import { useUserStore } from '@/Stores/UserStore'
import { useSocialShareStore } from '@/Stores/SocialShareStore'
import dayjs from 'dayjs'
import utc from 'dayjs/plugin/utc'
import timezone from 'dayjs/plugin/timezone'
import duration from 'dayjs/plugin/duration'
import isBetween from 'dayjs/plugin/isBetween'
import ZoomLogo from '@/Components/Global/SvgLogos/ZoomLogo.vue'
import NextBroadcastEmailReminderDialog from '@/Components/Pages/Teams/Elements/NextBroadcastEmailReminderDialog.vue'

dayjs.extend(utc)
dayjs.extend(timezone)
dayjs.extend(duration)
dayjs.extend(isBetween)

const teamStore = useTeamStore()
const userStore = useUserStore()
const socialShareStore = useSocialShareStore()

// Map store state to local computed properties
const team = computed(() => teamStore.team || {})

const isBroadcastOpen = computed(() => {
  const userCurrentTime = dayjs(userStore.userCurrentTime)
  const broadcastDate = dayjs(teamStore.nextBroadcast.broadcastDate)

  // console.log('User Current Time:', userCurrentTime.format())
  // console.log('Broadcast Date:', broadcastDate.format())

  const startTime = broadcastDate.subtract(30, 'minute')
  const endTime = broadcastDate.add(60, 'minute')

  // console.log('Start Time:', startTime.format())
  // console.log('End Time:', endTime.format())

  if (userCurrentTime.isBetween(startTime, endTime, null, '[]')) {
    // console.log('Broadcast is open')
    return true
  } else {
    // console.log('Broadcast is closed')
    return false
  }
})

// Watch the current time to update isBroadcastOpen when time changes
watchEffect(() => {
  const interval = setInterval(() => {
    isBroadcastOpen.value // Trigger re-evaluation
  }, 60000) // Check every minute

  // Clear interval on component unmount
  return () => clearInterval(interval)
})

const zoomLink = computed(() => {
  const nextBroadcast = teamStore.nextBroadcast
  // console.log('nextBroadcast:', nextBroadcast);  // Debugging
  if (nextBroadcast && Array.isArray(nextBroadcast.broadcastDetails)) {
    // console.log('broadcastDetails:', nextBroadcast.broadcastDetails);  // Debugging
    const zoomLinkObj = nextBroadcast.broadcastDetails.find(detail => detail.zoomLink)
    return zoomLinkObj ? zoomLinkObj.zoomLink : ''
  }
  return ''
})

function shareZoomLink() {
  const payload = {
    name: 'Join us through Zoom for the next broadcast of ' + teamStore.nextBroadcast.name + ' on notTV!',
    description: teamStore.nextBroadcast.description,
    url: zoomLink.value,
    image: teamStore.nextBroadcast.image,
  }
  socialShareStore.parseModel(payload)
}

function joinZoom() {
  // console.log('zoomLink value:', zoomLink.value);  // Accessing the value of the computed property
  window.open(zoomLink.value, '_blank')
}
</script>
