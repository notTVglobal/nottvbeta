<template>
  <div class="w-full">
    <div class="mb-2 pb-6 text-primary text-center">Confirm Timezone</div>
    <div class="flex flex-row justify-center items-center w-full max-w-lg mx-auto">
      <div v-if="isCanadianTimezoneTest" class="w-full max-w-3xl">
        <p class="mb-4">Canadian Timezones</p>
        <div @focusin="handleFocus" @focusout="handleBlur">
        <vSelect
            v-model="selectedTimezone"
            :options="formattedCanadianTimezones"
            class="ml-2 rounded-lg bg-white dark:bg-gray-800 dark:text-white style-chooser"
            :placeholder="timezonePlaceholder"
            searchable
        />
        </div>
        <button @click.prevent="showAllTimezones" class="my-2 ml-2 btn btn-outline">Show All Timezones</button>
      </div>
      <div v-else class="w-full max-w-lg">
        <TimezoneSelector @update-timezone="updateTimezone"
                          :placeholder="timezonePlaceholder"
                          @focus="handleFocus"
                          @blur="handleBlur"/>
        <button @click.prevent="showCanadianTimezones" class="my-2 ml-2 btn btn-outline">Show Only Canadian Timezones
        </button>
      </div>
    </div>
    <div class="flex flex-row justify-center pt-6">
      <button @click.prevent="confirmTimezone" class="btn btn-primary">Confirm Timezone</button>
    </div>
  </div>
</template>


<script setup>
import { computed, ref, watch } from 'vue'
import { useUserStore } from '@/Stores/UserStore'
import TimezoneSelector from '@/Components/Global/Time/TimezoneSelector.vue'

const userStore = useUserStore()

const props = defineProps({
  timezone: String,
})

const emits = defineEmits(['update-timezone', 'confirm-timezone'])

const selectedTimezone = ref(props.timezone)
const expanded = ref(false)
const timezonePlaceholder = ref('Select a timezone...')

// For testing purposes, create a local variable to simulate Canadian timezone
const testCanadianTimezone = ref('America/Toronto')

// Format Canadian timezones
const formattedCanadianTimezones = computed(() =>
    userStore.timezones
        .filter(tz => tz.startsWith('America/'))
        .map(tz => {
          const parts = tz.split('/')
          const city = parts[1].replace('_', ' ')
          return {value: tz, label: city}
        }),
)

const isCanadianTimezoneTest = computed(() =>
    formattedCanadianTimezones.value.some(tz => tz.value === testCanadianTimezone.value) && !expanded.value,
)

watch(selectedTimezone, (newTimezone) => {
  updateTimezone(newTimezone)
})

function updateTimezone(newTimezone) {
  const timezoneValue = newTimezone?.value || newTimezone;
  emits('update-timezone', timezoneValue);
  console.log('Emitted timezone:', timezoneValue);
}

function confirmTimezone() {
  emits('confirm-timezone')
}

function showAllTimezones() {
  selectedTimezone.value = null
  expanded.value = true
}

function showCanadianTimezones() {
  selectedTimezone.value = null
  expanded.value = false
}

function handleFocus() {
  timezonePlaceholder.value = 'Type to Search...'
}

function handleBlur() {
  timezonePlaceholder.value = 'Select a timezone...'
}
</script>


<style scoped>
/* Override styles for v-select */
.style-chooser {
  width: 100%;
  padding: 0.5rem;
}

.style-chooser .vs__search::placeholder,
.style-chooser .vs__dropdown-toggle,
.style-chooser .vs__dropdown-menu {
  background: #dfe5fb;
  border: none;
  color: #394066;
  text-transform: lowercase;
  font-variant: small-caps;
  min-width: 100%;
}

.style-chooser .vs__clear,
.style-chooser .vs__open-indicator {
  fill: #394066;
  cursor: pointer;
}

/* Simple button styling for "Show All Timezones" and "Show Only Canadian Timezones" */
.btn-outline {
  border: 1px solid #394066;
  background: transparent;
  color: #394066;
}

.btn-outline:hover {
  background: #394066;
  color: #ffffff;
}
</style>

