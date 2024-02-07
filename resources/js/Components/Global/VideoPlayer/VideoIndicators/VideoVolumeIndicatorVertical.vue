<template>
<div class="fixed flex justify-end text-right top-10 left-0 right-10 z-999 w-calc([100vw])">
    <div class="flex justify-end custom-volume-indicator"> <!-- Ensure the parent container has a defined height -->
      <!--      <div id="volumeIndicator" class="absolute bottom-0 bg-green-500 w-3 z-999"></div>-->
      <!-- Volume Indicator; shown when volume is not at its default state -->
      <div v-if="audioStore.audioLevel > 1" class="flex flex-row">
        <div>
          <span class="text-xs text-gray-500 uppercase tracking-widest pr-2">Audio Signal</span>
        </div>
        <div class="flex flex-col">
          <div class="w-full">
            <progress max="100" :value="audioStore.audioLevel" id="volumeIndicator"></progress>
          </div>
          <div class="grid grid-cols-3 text-xs text-gray-500">
            <div>0</div><div class="text-center">50</div><div class="text-right">100</div>
          </div>

        </div>
      </div>
      <div v-else class="text-xs text-gray-500 uppercase tracking-widest pr-2">No Audio Signal</div>
    </div>
</div>


</template>

<script setup>
// import videojs from 'video.js'
import { nextTick, onMounted, onUnmounted, ref, watch } from 'vue'
import { useAudioStore } from '@/Stores/AudioStore'

const audioStore = useAudioStore()

</script>

<style scoped>
.custom-volume-indicator {
  position: relative;
  top: 5rem; /* Start from at least 4rem down to accommodate the navbar */
  right: 0;
  bottom: 0;

  width: calc(100vh - 2rem);
  max-height: 3px; /* Thickness of the progress bar */

  z-index: 998;
}
.custom-volume-indicator progress {
  background-color: transparent; /* Optional: Makes the background transparent */
  border-color: darkblue;
  border-width: thin;

}
</style>