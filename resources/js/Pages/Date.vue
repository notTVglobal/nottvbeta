<template>
  <div id="date" :style="dateStyle" class="w-full hide-scrollbar scrollbar-hide overflow-hidden">
    {{ currentDate }}
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const dateStyle = {
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

const currentDate = ref('');
const formatDate = () => {
  const now = new Date();
  currentDate.value = now.toLocaleDateString('en-US', {
    weekday: 'long', // "Monday"
    year: 'numeric', // "2021"
    month: 'long', // "July"
    day: 'numeric' // "1"
  });
};

onMounted(() => {
  formatDate();  // Set the date when the component mounts
});
</script>

<script>
import NoLayout from '../Layouts/NoLayout';
export default {
  layout: NoLayout,
}
</script>

<style scoped>
/* Additional styles can be added here if needed */
</style>
