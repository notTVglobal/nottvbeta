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
<div class="mt-2 space-x-2 space-y-2">
  <button v-if="isBroadcastOpen" @click="joinZoom" class="w-fit bg-blue-500 hover:bg-blue-700 text-white font-bold mt-2 py-2 px-4 rounded">
    Click to Join
  </button>
  <button @click.prevent="shareZoomLink" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
    Click to Share This
  </button>
  <button v-if="!isBroadcastOpen" @click="getEmailReminder" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
    Get an Email Reminder
  </button>
</div>

  </div>

</template>
<script setup>
import { computed, ref, watch, watchEffect } from 'vue'
import { useTeamStore } from '@/Stores/TeamStore'
import { useUserStore } from '@/Stores/UserStore'
import { useNotificationStore } from '@/Stores/NotificationStore'
import { useSocialShareStore } from '@/Stores/SocialShareStore'
import dayjs from 'dayjs';
import utc from 'dayjs/plugin/utc';
import timezone from 'dayjs/plugin/timezone';
import ZoomLogo from '@/Components/Global/SvgLogos/ZoomLogo.vue'

dayjs.extend(utc);
dayjs.extend(timezone);

const teamStore = useTeamStore()
const userStore = useUserStore()
const notificationStore = useNotificationStore()
const socialShareStore = useSocialShareStore()

// Map store state to local computed properties
const team = computed(() => teamStore.team || {});

const isBroadcastOpen = computed(() => {
  // Ensure nextBroadcast and broadcastDate are available
  if (!team.value.nextBroadcast || !team.value.nextBroadcast.broadcastDate) {
    return false;
  }

  // Calculate the time difference
  const diffInMinutes = userStore.userCurrentTime.diff(teamStore.nextBroadcast.broadcastDate, 'minute');

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

const zoomLink = computed(() => {
  const nextBroadcast = teamStore.nextBroadcast;
  // console.log('nextBroadcast:', nextBroadcast);  // Debugging
  if (nextBroadcast && Array.isArray(nextBroadcast.broadcastDetails)) {
    // console.log('broadcastDetails:', nextBroadcast.broadcastDetails);  // Debugging
    const zoomLinkObj = nextBroadcast.broadcastDetails.find(detail => detail.zoomLink);
    return zoomLinkObj ? zoomLinkObj.zoomLink : '';
  }
  return '';
});

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
  window.open(zoomLink.value, '_blank');
}

function getEmailReminder() {
  // Implement the logic to sign up for an email reminder
  notificationStore.setGeneralServiceNotification('Reminder Set!', 'You’re all set! We’ll send you an email reminder before the broadcast starts. Stay tuned!');
}
</script>
