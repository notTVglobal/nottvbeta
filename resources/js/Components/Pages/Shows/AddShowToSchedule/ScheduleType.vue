<template>
  <div class="flex flex-col w-full mx-auto justify-center">
    <div class="mb-2 text-primary text-center">Choose Schedule Type</div>
    <div class="flex justify-center space-x-4 mt-4">
      <button @click.prevent="selectScheduleType('one-time')"
              class="btn btn-primary h-40 w-60 bg-indigo-500 hover:bg-indigo-700 text-white rounded-lg flex flex-col p-4">
        <font-awesome-icon :icon="['fas', 'calendar-day']" class="hover:text-blue-800 mb-2 text-2xl"/>
        <span class="text-lg">One-time Event</span>
      </button>
      <button @click.prevent="selectScheduleType('recurring')"
              class="btn btn-primary h-40 w-60 bg-indigo-500 hover:bg-indigo-700 text-white rounded-lg flex flex-col p-4">
        <font-awesome-icon :icon="['fas', 'sync-alt']" class="hover:text-blue-800 mb-2 text-2xl"/>
        <span class="text-lg">Recurring Show</span>
      </button>
    </div>
    <button @click.prevent="changeTimezone" class="mt-4 btn btn-sm w-fit px-4 py-1 self-center hover:bg-gray-500 hover:text-gray-800 hover:transition">< change timezone</button>
  </div>
</template>
<script setup>

const props = defineProps({
  timezoneConfirmed: Boolean,
})

const emits = defineEmits(['select-schedule-type', 'change-timezone'])

function selectScheduleType(type) {
  emits('select-schedule-type', type)
}

function changeTimezone() {
  emits('change-timezone')
}
</script>