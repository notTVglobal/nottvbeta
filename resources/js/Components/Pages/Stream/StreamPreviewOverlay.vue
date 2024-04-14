<template>
  <div class="overlay-container">
    <div class="quadrant top-left" :style="quadrantStyle.topLeft">
      <div class="content">Top Left Content</div>
    </div>
    <div class="quadrant top-right" :style="quadrantStyle.topRight">
      <div class="content">Top Right Content</div>
    </div>
    <div class="quadrant bottom-left" :style="quadrantStyle.bottomLeft">
      <div class="content">Bottom Left Content</div>
    </div>
    <div class="quadrant bottom-right" :style="quadrantStyle.bottomRight">
      <div class="content">Bottom Right Content</div>
    </div>
    <div class="center-overlay">
      <div class="content">Center Screen Content</div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'

// Define reactive color references
const topLeftColor = ref('255, 99, 71'); // RGB values for Tomato without "rgb"
const topRightColor = ref('70, 130, 180'); // RGB values for SteelBlue without "rgb"
const bottomLeftColor = ref('50, 205, 50'); // RGB values for LimeGreen without "rgb"
const bottomRightColor = ref('255, 140, 0'); // RGB values for DarkOrange without "rgb"

// Define a universal opacity level
const opacityLevel = ref(0.5); // 50% opacity

const quadrantStyle = computed(() => ({
  topLeft: { backgroundColor: `rgba(${topLeftColor.value}, ${opacityLevel.value})` },
  topRight: { backgroundColor: `rgba(${topRightColor.value}, ${opacityLevel.value})` },
  bottomLeft: { backgroundColor: `rgba(${bottomLeftColor.value}, ${opacityLevel.value})` },
  bottomRight: { backgroundColor: `rgba(${bottomRightColor.value}, ${opacityLevel.value})` },
}));

</script>

<style scoped>
/* Hide scrollbar for Chrome, Safari and Opera on all child elements */
.overlay-container::-webkit-scrollbar,
.overlay-container *::-webkit-scrollbar {
  display: none;
}

/* Hide scrollbar for IE, Edge, and Firefox on the container and all child elements */
.overlay-container,
.overlay-container * {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}

.overlay-container {
  position: relative;
  width: 1920px;
  height: 1080px;
  background-color: #000; /* For visibility */
  overflow: hidden; /* Ensures no scrolling from the container itself */
}

.quadrant {
  position: absolute;
  width: 960px; /* Half of the container width */
  height: 540px; /* Half of the container height */
  overflow: hidden; /* Ensures content doesn't spill out */
}

.top-left {
  top: 0;
  left: 0;
}

.top-right {
  top: 0;
  right: 0;
}

.bottom-left {
  bottom: 0;
  left: 0;
}

.bottom-right {
  bottom: 0;
  right: 0;
}

.center-overlay {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 300px; /* Arbitrary size for visibility */
  height: 200px;
  background-color: rgba(255, 255, 255, 0.5); /* Semi-transparent white */
}

.content {
  padding: 20px;
  color: white; /* Ensures text is visible */
}
</style>