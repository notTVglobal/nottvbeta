<template>
  <div class="space-x-4">
    <p class="text-lg md:text-xl leading-relaxed font-medium text-gray-800 dark:text-gray-200 p-3 rounded">
      <span v-if="!isBroadcastOpen">Join the next broadcast!</span>
      <span v-else>Join the broadcast!</span>
    </p>
    <p v-if="!isBroadcastOpen" class="text-sm text-black">The link will appear 30 minutes before we go live.</p>
    <div class="flex justify-center">
      <ZoomLogo class="w-48 p-3"/>
    </div>
    <div v-if="isBroadcastOpen" class="flex flex-col justify-center items-center text-black">
      <!-- Display the Zoom link or other broadcast-related elements here -->
      <h2>The broadcast is currently open.</h2>
    </div>
<div class="mt-2 space-x-2">
  <button v-if="isBroadcastOpen" @click="joinZoom" class="w-fit bg-blue-500 hover:bg-blue-700 text-white font-bold mt-2 py-2 px-4 rounded">
    Click to Join
  </button>
  <button @click="shareBroadcast" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
    Click to Share This
  </button>
  <button v-if="!isBroadcastOpen" @click="getEmailReminder" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
    Get an Email Reminder
  </button>
</div>

  </div>

</template>
<script setup>
import { useUserStore } from '@/Stores/UserStore'
import { useNotificationStore } from '@/Stores/NotificationStore'
import ZoomLogo from '@/Components/Global/SvgLogos/ZoomLogo.vue'
import { computed, ref, watchEffect } from 'vue'
import dayjs from 'dayjs';
import utc from 'dayjs/plugin/utc';
import timezone from 'dayjs/plugin/timezone';

const userStore = useUserStore()
const notificationStore = useNotificationStore()

dayjs.extend(utc);
dayjs.extend(timezone);

const props = defineProps({
  nextBroadcast: Object,
})

const isBroadcastOpen = computed(() => {
  // Ensure nextBroadcast and broadcastDate are available
  if (!props.nextBroadcast || !props.nextBroadcast.broadcastDate) {
    return false;
  }

  // Convert the broadcastDate from UTC to user's local time
  const broadcastDateUserTime = userStore.convertUtcToUserTimezone(props.nextBroadcast.broadcastDate);

  // Get the current time in user's local time
  const nowUserTime = dayjs().tz(userStore.timezone);

  // Calculate the time difference
  const diffInMinutes = nowUserTime.diff(broadcastDateUserTime, 'minute');

  // Check if the broadcast is within the allowed range
  return diffInMinutes >= -30 && diffInMinutes <= 60; // 30 minutes in the future to 60 minutes in the past
});

// Watch the current time to update isBroadcastOpen when time changes
watchEffect(() => {
  const interval = setInterval(() => {
    isBroadcastOpen.value; // Trigger re-evaluation
  }, 60000); // Check every minute

  // Clear interval on component unmount
  return () => clearInterval(interval);
});

function joinZoom() {
  window.open(props.nextBroadcast.broadcastDetails.zoomLink, '_blank');
}

function shareBroadcast() {
  navigator.clipboard.writeText(props.nextBroadcast.broadcastDetails.zoomLink).then(() => {
    notificationStore.setGeneralServiceNotification('Success!', 'The Zoom link has been copied to your clipboard. Join the live broadcast and have fun!');
  }, () => {
    notificationStore.setGeneralServiceNotification('Oops!', 'Failed to copy the Zoom link. Please try again.');
  });
}

function getEmailReminder() {
  // Implement the logic to sign up for an email reminder
  notificationStore.setGeneralServiceNotification('Reminder Set!', 'You’re all set! We’ll send you an email reminder before the broadcast starts. Stay tuned!');
}
</script>
