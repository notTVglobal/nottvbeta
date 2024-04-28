<template>
<div class="">
    <div class="flex justify-end custom-volume-indicator"> <!-- Ensure the parent container has a defined height -->
      <!--      <div id="volumeIndicator" class="absolute bottom-0 bg-green-500 w-3 z-999"></div>-->
      <!-- Volume Indicator; shown when volume is not at its default state -->
      <div v-show="showAudioComponent && !videoPlayerStore.muted" class="flex flex-row">
        <div>
          <span class="text-xs text-white uppercase tracking-widest pr-2">Audio Signal</span>
        </div>
        <div class="flex flex-col">
          <div class="w-full">
            <progress max="100" :value="audioStore.audioLevel" id="volumeIndicator"></progress>
          </div>
          <div class="grid grid-cols-3 text-xs text-white">
            <div class="text-left">0</div><div class="text-center">50</div><div class="text-right">100</div>
          </div>

        </div>
      </div>
      <div v-if="!showAudioComponent && !videoPlayerStore.muted" class="text-xs text-gray-400 uppercase tracking-widest pr-2">No Audio Signal</div>
      <div v-if="videoPlayerStore.muted" class="text-xs text-yellow-500 uppercase tracking-widest pr-2">Audio Muted</div>
    </div>
</div>


</template>

<script setup>
// import videojs from 'video.js'
import { computed, nextTick, onMounted, onUnmounted, ref, watch } from 'vue'
import { useAudioStore } from '@/Stores/AudioStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { debounce } from 'lodash'

const audioStore = useAudioStore()
const videoPlayerStore = useVideoPlayerStore()

const showAudioComponent = ref(false);

// Debounced function to handle setting the value to false
const setFalseIfLow = debounce(() => {
  // This function will only execute if there has been no call to reset the timer for 1500ms,
  // meaning the audio level has stayed low
  showAudioComponent.value = false;
  // console.log("Debounced: Set to false due to low audio level");
}, 1500);

// Watch for changes in audio level
watch(() => audioStore.audioLevel, newAudioLevel => {
  if (newAudioLevel > 1) {
    showAudioComponent.value = true;
    setFalseIfLow.cancel(); // Cancel any pending debounced calls to set false if audio goes high
    // console.log("Immediate: Set to true due to high audio level");
  } else {
    setFalseIfLow(); // Only debounce setting to false
  }

  // console.log("Current audio level:", newAudioLevel);
  // console.log("Current showAudioComponent.value:", showAudioComponent.value);
});

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