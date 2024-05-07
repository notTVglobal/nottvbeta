<template>
  <div class="space-x-4">
    <p class="text-lg md:text-xl leading-relaxed font-medium text-gray-800 dark:text-gray-200 p-3 rounded">
      Join the next broadcast!
    </p>
    <p class="text-sm text-black">The link will appear 30 minutes before we go live.</p>
    <div class="flex justify-center mb-4">
      <ZoomLogo class="w-48 p-3"/>
    </div>

    <button v-if="isBroadcastOpen" @click="joinZoom" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
      Click to Join
    </button>
    <button @click="shareBroadcast" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
      Click to Share This
    </button>
    <button @click="getEmailReminder" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
      Get an Email Reminder
    </button>
  </div>

</template>
<script setup>
import ZoomLogo from '@/Components/Global/SvgLogos/ZoomLogo.vue'
import { computed, ref } from 'vue';

const isBroadcastOpen = computed(() => {
  // Placeholder condition; replace with actual logic to determine if the Zoom link should be active
  // return new Date(nextBroadcast.value.dateTime) <= new Date();
});

function joinZoom() {
  // window.open(nextBroadcast.value.zoomLink, '_blank');
}

function shareBroadcast() {
  // navigator.clipboard.writeText(nextBroadcast.value.zoomLink).then(() => {
  //   alert('Link copied to clipboard!');
  // }, () => {
  //   alert('Failed to copy the link.');
  // });
}

function getEmailReminder() {
  // Implement the logic to sign up for an email reminder
  alert('Reminder set!');
}
</script>
