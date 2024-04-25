<template>
  <div class="exclamation-box">
    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
      <!-- Background square -->
      <rect width="100" height="100" style="fill: yellow; stroke: lightgray; stroke-width: 4;" />
      <!-- Exclamation mark -->
      <text x="50%" y="65%" fill="red" font-size="75" style="font-family: Arial, sans-serif; font-weight: bold;" text-anchor="middle">!</text>
    </svg>
  </div>

</template>
<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
</script>
<style scoped>
.exclamation-box {
  width: 75px; /* Adjust size as needed */
  height: 75px; /* Adjust size as needed */
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>