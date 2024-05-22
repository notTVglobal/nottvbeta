<template>
  <div>
    <DatePicker v-model="date" mode="dateTime" :attributes='attrs' :rules="rules" hide-time-header>
      <template #default="{ togglePopover }">
        <button
            class="px-3 py-2 bg-blue-500 text-sm text-white font-semibold rounded-md"
            @click.prevent="togglePopover"
        >
          <slot name="buttonName">Select date</slot>
        </button>
      </template>
      <template #footer>
        <div class="w-full px-4 pb-3">
          <button
              class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold w-full px-3 py-1 rounded-md"
              @click.prevent="clearDate"
          >
            Clear
          </button>
        </div>
      </template>
    </DatePicker>

  </div>
</template>

<script setup>
import { ref, defineProps, defineEmits, watch } from 'vue'

// import VCalendar from 'v-calendar'
import { DatePicker } from 'v-calendar'
import 'v-calendar/style.css'

const props = defineProps({
  // initialDate: Date,
  // initialTime: String,
  inputValue: String,
  date: String,
})

const emits = defineEmits(['date-time-selected'])

// let date = new Date();
// let date = ref(new Date());
let date = ref(props.date)

const clearDate = () => {
  attrs.value = null
  emits('date-time-selected', {date: null})
}

const calendar = ref(null)
const inputValue = ref(props.inputValue || null)

// Define refs to store selected date and time
// const selectedDate = ref(props.initialDate || null);
// const selectedTime = ref(props.initialTime || null);

const attrs = ref([
  {
    key: 'today',
    highlight: true,
    dates: new Date(),
  },
])

const rules = ref({
  minutes: {interval: 30},
})

// Watch for changes in selected dateTime emit it
watch([date], ([newDate]) => {
  attrs.value = null
  emits('date-time-selected', {date: newDate})
})
</script>
