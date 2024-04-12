<template>
  <div :class="`col-span-${span} ${colorClass} row-span-1`" class="flex flex-col items-center justify-center text-white text-sm p-2 overflow-hidden">
    <h3 class="font-bold">{{ show.name }}</h3>
    <p>{{ displayStartTime }} - {{ displayEndTime }}</p>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  show: {
    type: Object,
    required: true
  },
  span: {
    type: Number,
    required: true
  }
});

const colorClass = computed(() => {
  switch (props.span) {
    case 1: return 'bg-green-500';  // 30 minutes
    case 2: return 'bg-orange-500'; // 60 minutes
    case 3: return 'bg-blue-500';   // 90 minutes
    case 4: return 'bg-yellow-500'; // 120 minutes
    case 5: return 'bg-red-500';    // 150 minutes
    case 6: return 'bg-purple-500'; // 180 minutes
    default: return 'bg-gray-400';  // default or undefined duration
  }
});

const displayStartTime = computed(() => props.show.startTime.toLocaleTimeString());
const displayEndTime = computed(() => new Date(props.show.startTime.getTime() + props.show.span * 30 * 60000).toLocaleTimeString());

</script>
