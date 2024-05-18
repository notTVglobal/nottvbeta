<template>
    <div class="capitalize" :class="props.class">{{ timeAgo }}</div>
</template>
<script setup>
import { computed, ref, watch, watchEffect } from 'vue'
import { useTimeAgo } from '@vueuse/core'
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import timezone from 'dayjs/plugin/timezone';
import utc from 'dayjs/plugin/utc'; // Required for timezone support
import { useUserStore } from "@/Stores/UserStore"

const userStore = useUserStore()

// Extend Day.js with the plugins
dayjs.extend(relativeTime);
dayjs.extend(timezone);
dayjs.extend(utc);

let props = defineProps({
  dateTime: String,
  timezone: String,
  class: String,
})

// Computed property to convert dateTime to the user's timezone
const dateInUserTimezone = computed(() => {
  if (userStore.timezone) {
    if (userStore.timezone === props.timezone) {
      return dayjs(props.dateTime).format();
    } else {
      return dayjs.utc(props.dateTime).tz(userStore.timezone).format();
    }

  }
  return '';
});

// Computed property to generate "time ago" string based on the converted date
const timeAgo = computed(() => {
  if (dateInUserTimezone.value) {
    return dayjs(dateInUserTimezone.value).fromNow();
  }
  return 'Loading...'; // Or any placeholder text until the timezone is loaded
});

//
// const userTimezone = userStore.timezone; // Adjust according to your state management
//
// const convertTimeToUserTimezone = (date) => {
//   // Convert the date to the user's timezone using Day.js
//   return dayjs.tz(date, userTimezone);
// };
//
// const convertTimeAgo = (date) => {
//   // Since useTimeAgo expects a ref, create a ref for the date
//   // const dateRef = ref(date);
//
//   // Convert dateTime to the user's timezone first
//   const dateInUserTimezone = userStore.convertUtcToUserTimezone(date);
//
//   const convertedDate = ref('');
//
//   watchEffect(() => {
//     if (userStore.timezone) {
//       convertedDate.value = dayjs.utc(props.dateTime).tz(userStore.timezone).format();
//     }
//   })
//
//   // Use ref to reactively update the timeAgo value
//   const dateRef = ref(convertedDate.value);
//
//   // Use watchEffect to update the timeAgo value whenever the date changes
//   let timeAgoValue = ref('');
//   watchEffect(() => {
//     // const timeAgo = useTimeAgo(dateRef);
//     // timeAgoValue = timeAgo.value;
//
//     // Directly use dayjs() with .fromNow() to generate the "time ago" string
//     timeAgoValue.value = dayjs(convertedDate.value).fromNow();
//   });
//
//   return timeAgoValue;
// }
//
// // Example of using convertTimeAgo in the setup
// const timeAgo = convertTimeAgo(props.dateTime);

</script>