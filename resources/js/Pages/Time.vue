<template>
  <div id="time" :style="outputStyle" class="w-full hide-scrollbar scrollbar-hide overflow-hidden">
    {{ currentTime }}
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const outputStyle = {
  display: 'inline-block',
  fontFamily: 'monospace',
  fontSize: '70px',
  textAlign: 'center',
  color: 'lightgray',
  borderRadius: '10px',
  padding: '10px',
  backgroundColor: 'rgba(0, 0, 0, 0.75)',
  WebkitFontSmoothing: 'antialiased', // For Chrome, Safari, newer versions of Opera
  MozOsxFontSmoothing: 'grayscale', // For Firefox 25+
  fontSmooth: 'always', // For older Firefox (though not standard)
  textRendering: 'optimizeLegibility', // Improves legibility and rendering
};

const currentTime = ref('');
const formatTime = () => {
  const now = new Date();
  currentTime.value = now.toLocaleTimeString('en-US', {
    hour12: false,
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
    timeZoneName: 'short'
  }) + ` GMT${now.getTimezoneOffset() / 60 * -1}`;
};

let intervalId;

onMounted(() => {
  formatTime();  // Initialize time once component is mounted
  intervalId = setInterval(formatTime, 1000);  // Update every second
});

onUnmounted(() => {
  clearInterval(intervalId);  // Clear the interval when component is unmounted
});
</script>

<script>
import NoLayout from '../Layouts/NoLayout';
export default {
  layout: NoLayout,
}
</script>
