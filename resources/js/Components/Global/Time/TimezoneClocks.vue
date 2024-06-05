<template>
  <div class="flex justify-center items-center space-x-8">
    <div v-for="timezone in timezones" :key="timezone.name" class="text-center">
      <div class="analog-clock" :class="timezone.name">
        <div class="hand hour" :style="hourStyle(timezone.offset)"></div>
        <div class="hand minute" :style="minuteStyle"></div>
        <div class="hand second" :style="secondStyle"></div>
      </div>
      <p class="mt-2">{{ timezone.name }}</p>
      <p>{{ getFormattedTime(timezone.offset) }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useUserStore } from "@/Stores/UserStore";
import dayjs from 'dayjs';
import utc from 'dayjs/plugin/utc';
import timezone from 'dayjs/plugin/timezone';

dayjs.extend(utc);
dayjs.extend(timezone);

const userStore = useUserStore();

const timezones = [
  { name: 'Pacific Time', offset: 'America/Los_Angeles' },
  { name: 'Central Time', offset: 'America/Chicago' },
  { name: 'Eastern Time', offset: 'America/New_York' },
  { name: 'London Time', offset: 'Europe/London' },
  { name: 'Sydney Time', offset: 'Australia/Sydney' },
  { name: 'Tokyo Time', offset: 'Asia/Tokyo' }
];

const now = ref(dayjs());

const getFormattedTime = (offset) => dayjs().tz(offset).format('HH:mm:ss');

const updateTime = () => {
  now.value = dayjs();
};

const hourStyle = (offset) => {
  const hours = now.value.tz(offset).hour();
  const degrees = ((hours % 12) / 12) * 360;
  return { transform: `rotate(${degrees}deg)` };
};

const minuteStyle = computed(() => {
  const minutes = now.value.minute();
  const degrees = (minutes / 60) * 360;
  return { transform: `rotate(${degrees}deg)` };
});

const secondStyle = computed(() => {
  const seconds = now.value.second();
  const degrees = (seconds / 60) * 360;
  return { transform: `rotate(${degrees}deg)`, transition: 'transform 0.05s linear' };
});

let interval;

onMounted(() => {
  interval = setInterval(updateTime, 1000);
});

onUnmounted(() => {
  clearInterval(interval);
});
</script>

<style scoped>
.analog-clock {
  position: relative;
  width: 80px;
  height: 80px;
  border: 2px solid black;
  border-radius: 50%;
  margin: auto;
}

.hand {
  position: absolute;
  bottom: 50%;
  left: 50%;
  transform-origin: bottom;
  background: black;
}

.hour {
  width: 4px;
  height: 30px;
}

.minute {
  width: 2px;
  height: 40px;
}

.second {
  width: 1px;
  height: 45px;
  background: red;
  transition: transform 0.05s linear;
}
</style>
