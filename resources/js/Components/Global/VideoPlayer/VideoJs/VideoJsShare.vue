<template>
  <div class="w-full h-screen bg-black flex flex-col items-center justify-center">
    <video :id="id"
              class="video-js vjs-default-skin w-full h-full"
              playsinline
              controls
              crossorigin="anonymous"
    >
      <source :src="source" :type="sourceType">
    </video>
  </div>
</template>

<script setup>

const props = defineProps({
  id: String,
  source: String,
  sourceType: String
})



</script>

<style scoped>
.video-js {
  width: 100%;
  height: 100%;
}

.vjs-default-skin {
  color: #fff; /* Make controls white */
}

.vjs-default-skin .vjs-control-bar {
  background: rgba(0, 0, 0, 0.7); /* Semi-transparent background for controls */
}

.vjs-default-skin .vjs-slider {
  background: rgba(255, 255, 255, 0.5); /* Lighter background for sliders */
}
</style>
