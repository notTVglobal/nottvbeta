<template>
  <div>
    <div v-if="countdownMessage">
      <div :class="messageClass">{{ countdownMessage }}</div>
    </div>

    <div v-else>
      <div>Next Live Broadcast will begin in... &nbsp;</div>
      <div :class="containerClass" class="countdown-container">
        <div class="font-mono text-2xl">
          <!-- Display days only if greater than 0 -->
          <span :class="daysClass" v-if="days > 0" class="countdown-unit mr-1">{{ days }}</span><span v-if="days > 0"
                                                                                                      class="mr-2">d</span>
          <!-- Always display hours, minutes, and seconds -->
          <span :class="hoursClass" class="countdown-unit mr-1">{{ hours }}</span><span class="mr-2">h</span>
          <span :class="numberClass" class="countdown-unit mr-1">{{ minutes }}</span><span class="mr-2">m</span>
          <span :class="secondsClass" class="countdown-unit mr-1">{{ seconds }}</span><span class="mr-2">s</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import dayjs from 'dayjs'
import duration from 'dayjs/plugin/duration'
import utc from 'dayjs/plugin/utc'
import { useGoLiveStore } from '@/Stores/GoLiveStore'

dayjs.extend(duration)
dayjs.extend(utc)

const goLiveStore = useGoLiveStore()
const nextBroadcast = computed(() => goLiveStore.selectedShow?.nextBroadcast)

const days = ref(0)
const hours = ref(0)
const minutes = ref(0)
const seconds = ref(0)
const countdownMessage = ref('')

// Computed property to determine if less than 5 minutes remain
const isLessThanFiveMinutesRemaining = computed(() => {
  return days.value === 0 && hours.value === 0 && (minutes.value > 0 || (minutes.value === 0 && seconds.value > 0)) && minutes.value < 5
})

// Computed property to determine if less than 10 seconds remain
const isLessThanTenSecondsRemaining = computed(() => {
  return days.value === 0 && hours.value === 0 && minutes.value === 0 && seconds.value <= 10
})

// Class binding for the countdown container
const containerClass = computed(() => {
  return isLessThanFiveMinutesRemaining.value ? 'container-red' : 'container-default'
})

// Class binding for days
const daysClass = computed(() => {
  return days.value > 0 ? 'text-almost-live' : 'text-default'
})

// Class binding for hours
const hoursClass = computed(() => {
  return isLessThanTenSecondsRemaining.value ? 'text-red' : (days.value === 0 && hours.value > 0 ? 'text-almost-live' : 'text-default')
})

// Class binding for minutes and seconds
const numberClass = computed(() => {
  return isLessThanTenSecondsRemaining.value ? 'text-red' : (isLessThanFiveMinutesRemaining.value ? 'text-almost-live' : 'text-default')
})

const secondsClass = computed(() => {
  return isLessThanTenSecondsRemaining.value ? 'text-red' : numberClass.value
})

const messageClass = computed(() => {
  if (countdownMessage.value === 'The broadcast is live now!') {
    return 'live-message'
  } else if (countdownMessage.value === 'No broadcast is currently scheduled!') {
    return 'no-schedule-message'
  }
  return ''
})

const updateCountdown = () => {
  if (!nextBroadcast.value) {
    countdownMessage.value = 'No broadcast is currently scheduled!'
    return
  }

  const now = dayjs().utc()
  const targetTime = dayjs.utc(nextBroadcast.value)
  const difference = targetTime.diff(now)

  if (difference <= 0) {
    countdownMessage.value = 'The broadcast is live now!'
    return
  }

  const timeDuration = dayjs.duration(difference)

  days.value = Math.floor(timeDuration.asDays())
  hours.value = Math.floor(timeDuration.asHours()) % 24
  minutes.value = timeDuration.minutes()
  seconds.value = timeDuration.seconds()
}

onMounted(() => {
  updateCountdown()
  const intervalId = setInterval(updateCountdown, 1000)

  onUnmounted(() => {
    clearInterval(intervalId)
  })
})
</script>

<style scoped>
.countdown-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 10px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.container-default {
  background-color: #f5f5f5;
}

.container-red {
  background-color: #dc2626;
}

.countdown-unit {
  background-color: #1f2937; /* Default background color */
  padding: 5px 10px;
  border-radius: 5px;
  font-weight: bold;
}

.text-default {
  color: #ffffff;
}

.text-almost-live {
  color: #ffffff;
}

.text-red {
  color: #dc2626;
}

.live-message {
  color: #dc2626; /* Red color for live broadcast */
  font-weight: bold;
  font-size: 1.5rem;
}

.no-schedule-message {
  color: #6b7280; /* Gray color for no schedule */
  font-style: italic;
  font-size: 1.5rem;
}
</style>
