<template>
  <div class="schedule">
    <div class="day" v-for="day in schedule.days" :key="day.date">
      <div class="hour-block" v-for="hour in day.hours" :key="hour.time">
        <draggable v-model="hour.shows" group="shows" @end="onDragEnd(day.date, hour.time, $event)">
          <div v-for="show in hour.shows" :key="show.id" class="show">
            {{ show.title }}
          </div>
        </draggable>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import draggable from 'vuedraggable';
import axios from 'axios';

const schedule = ref({ days: [] });

onMounted(async () => {
  await fetchSchedule();
});

async function fetchSchedule() {
  try {
    const response = await axios.get('/api/schedule');
    schedule.value = response.data;
  } catch (error) {
    console.error('Failed to fetch schedule:', error);
  }
}

async function onDragEnd(date, time, event) {
  // Extract necessary data from the event
  const { oldIndex, newIndex } = event;

  // Determine the show that was moved and its new placement
  const movedShow = schedule.value.days.find(day => day.date === date)
      .hours.find(hour => hour.time === time)
      .shows[oldIndex];

  // Here you would calculate the new date/time based on newIndex
  // and update the backend via an API call to Laravel
  const newTime = calculateNewTime(time, newIndex); // Implement this based on your logic
  const updatedSchedule = { ...movedShow, newTime }; // Prepare data for the backend

  try {
    await axios.put(`/api/schedule/${movedShow.id}`, updatedSchedule);
    // Optionally, fetch the updated schedule or directly update the local state
  } catch (error) {
    console.error('Failed to update schedule:', error);
  }
}
</script>

<style scoped>
/* Add styles for .schedule, .day, .hour-block, .show here */
</style>
