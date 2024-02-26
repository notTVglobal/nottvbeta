<template>
  <div>
    <DatePicker v-model="date" mode="dateTime"/>

  </div>
</template>

<script setup>
import { ref, defineProps, defineEmits, watch, watchEffect } from 'vue'
import { DatePicker } from 'v-calendar'
import 'v-calendar/style.css'
import { useUserStore } from '@/Stores/UserStore'

const userStore = useUserStore()

const props = defineProps({
  date: null,
});

const emits = defineEmits();

let date = ref(props.date);
const calendar = ref(null);

// Watch for changes in selected dateTime emit it
watch([date], ([newDate]) => {
  emits('date-time-selected', {date: newDate});
});
</script>
