<template>
  <div>
    <input type="time" v-model="currentHour" @input="updateShows" class="text-black">
    <div :class="gridClass">
      <SpotComponent
          v-for="show in visibleShows"
          :key="show.id"
          :show="show"
          :span="calculateSpan(show)"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { useScheduleStore } from '@/Stores/ScheduleStore'
import SpotComponent from './SpotComponent.vue';
import { mockShows } from '@/Json/mockShows'; // assuming you export this from a module

const scheduleStore = useScheduleStore()

const baseTime = ref(new Date());
const currentHour = ref(baseTime.value.toISOString().substring(11, 16));
// const shows = ref([]);

// Update base time whenever current hour changes
// const updateShows = () => {
//   const [hours, minutes] = currentHour.value.split(':');
//   baseTime.value.setHours(parseInt(hours), parseInt(minutes), 0, 0);
// };

const gridClass = computed(() => {
  // Here you can have logic based on window width or other factors
  return 'grid grid-cols-8 gap-2 p-4'
}); // as before

const calculateSpan = (show) => {
  const showEnd = new Date(show.startTime.getTime() + show.span * 30 * 60000);
  if (show.startTime >= baseTime.value && showEnd <= new Date(baseTime.value.getTime() + 4 * 3600000)) {
    return show.span;
  }
  // additional logic to adjust span based on partial overlap
};

watch(currentHour, updateShows);

const visibleShows = computed(() => {
  return mockShows.map(show => {
    const startTime = new Date(baseTime.value.toDateString() + ' ' + show.startTime);
    const span = Math.min(show.span, 8); // Limit span to grid size
    return { ...show, startTime, span };
  });
});

const timeFormat = (date) => {
  return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

const endTime = (show) => {
  return new Date(show.startTime.getTime() + show.span * 30 * 60000);
};

const updateShows = () => {
  const [hour, minute] = currentHour.value.split(':');
  baseTime.value.setHours(parseInt(hour), parseInt(minute), 0, 0);
};

// const visibleShows = computed(() => {
//   return mockShows.map(show => {
//     // Calculate the adjusted start time based on the base time
//     const startTime = new Date(baseTime.value.toDateString() + ' ' + show.startTime);
//     const endTime = new Date(startTime.getTime() + show.span * 30 * 60000); // span in minutes to milliseconds
//
//     // Determine if the show should be visible within the current view window
//     // For example, assuming the view window is 4 hours from the base time
//     const viewWindowStart = baseTime.value;
//     const viewWindowEnd = new Date(baseTime.value.getTime() + 4 * 3600000);
//
//     // Check if the show overlaps with the current view window
//     const isVisible = (startTime < viewWindowEnd && endTime > viewWindowStart);
//
//     return {
//       ...show,
//       startTime,
//       endTime, // optional, for debugging or further calculations
//       isVisible // This can be used to conditionally render shows
//     };
//   }).filter(show => show.isVisible); // Optionally filter out shows not visible in the current window
// });

// const visibleShows = computed(() => {
//   // Filter or adjust shows based on the current 'baseTime'
//   return mockShows.map(show => ({
//     ...show,
//     startTime: new Date(show.startTime.getTime()) // ensure reactive updates
//   }));
// });

// const numberOfColumns = scheduleStore.numberOfColumns;

// const width = ref(window.innerWidth);

onMounted(async() => {
// Register the resize event listener to update the width
  window.addEventListener('resize', scheduleStore.updateWidth);
  // const response = await axios.get('@/Json/mockShows');
  // shows.value = response.data.mockShows.map(show => ({
  //   ...show,
  //   startTime: new Date(baseTime.value.toDateString() + ' ' + show.startTime)
  // }));
});

onUnmounted(() => {
  window.removeEventListener('resize', scheduleStore.updateWidth);
});

// Assuming the current hour is 0, which represents the start of our viewing grid
// const baseTime = new Date();
// baseTime.setMinutes(0, 0, 0); // Normalize to the full hour for consistency

// const mockShows = [
//   { name: 'Early Morning Show', span: 2, startTime: new Date(baseTime.getTime() - 30*60000) }, // Started 30 minutes ago
//   { name: 'Morning Show', span: 4, startTime: baseTime }, // Starts now
//   { name: 'Late Morning Show', span: 3, startTime: new Date(baseTime.getTime() + 1*3600000) }, // Starts in 1 hour
//   { name: 'Noon News', span: 2, startTime: new Date(baseTime.getTime() + 90*60000) }, // Starts in 1.5 hours
//   { name: 'Afternoon Talk', span: 6, startTime: new Date(baseTime.getTime() + 2*3600000) }, // Starts in 2 hours
//   { name: 'Evening Special', span: 1, startTime: new Date(baseTime.getTime() - 90*60000) }, // Started 1.5 hours ago
//   { name: 'Late Show', span: 3, startTime: new Date(baseTime.getTime() + 2*3600000) }, // Starts in 2 hours
//   { name: 'Night Owl Movie', span: 2, startTime: new Date(baseTime.getTime() + 3*3600000) } // Starts in 3 hours
// ];
</script>

<style scoped>
.grid {
  display: grid;
  grid-template-columns: repeat(8, minmax(0, 1fr));
  grid-auto-rows: minmax(100px, auto);
  gap: 4px;
}

@media (max-width: 640px) {
  .grid {
    grid-template-columns: repeat(4, minmax(0, 1fr));
  }
}
@media (min-width: 641px) and (max-width: 768px) {
  .grid {
    grid-template-columns: repeat(6, minmax(0, 1fr));
  }
}
@media (min-width: 769px) {
  .grid {
    grid-template-columns: repeat(8, minmax(0, 1fr));
  }
}
</style>
