<template>
    <div class="capitalize" :class="props.class">{{ convertTimeAgo(dateTime) }}</div>
</template>
<script setup>
import { ref, watchEffect } from 'vue'
import { useTimeAgo } from '@vueuse/core'

let props = defineProps({
  dateTime: String,
  class: String,
})

const convertTimeAgo = (date) => {
  // Since useTimeAgo expects a ref, create a ref for the date
  const dateRef = ref(date);

  // Use watchEffect to update the timeAgo value whenever the date changes
  let timeAgoValue = '';
  watchEffect(() => {
    const timeAgo = useTimeAgo(dateRef);
    timeAgoValue = timeAgo.value;
  });

  return timeAgoValue;
}

</script>