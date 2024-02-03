<template>
  <div class="custom-volume-indicator">
    <div class="relative h-full"> <!-- Ensure the parent container has a defined height -->
      <!--      <div id="volumeIndicator" class="absolute bottom-0 bg-green-500 w-3 z-999"></div>-->
      <!-- Volume Indicator; shown when volume is not at its default state -->
      <progress v-show="audioLevel !== 0" max="100" :value="audioLevel" class="w-full h-full" id="volumeIndicator"></progress>

    </div>
  </div>


</template>

<script setup>
import videojs from 'video.js'
import { nextTick, onMounted, onUnmounted, ref, watch } from 'vue'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'

const videoPlayerStore = useVideoPlayerStore()
const videoPlayer = ref(null)
let audioLevel = ref(0)
let sourceNode = ref()

let audioContext
let analyserNode
let animationFrameId

onMounted(async () => {
  await nextTick() // Ensure the DOM is updated

  // Initialize Video.js player
  videoPlayer.value = videojs('main-player')

  videoPlayer.value.ready(async () => {
    await nextTick() // Wait for the next DOM update

    // Access the underlying HTMLMediaElement
    const videoElement = videoPlayer.value.tech().el();

    // if (videoPlayer instanceof HTMLMediaElement) {
      try {
        // Use the globally defined audioContext
        // if (!window.audioContext) {
        //   window.audioContext = new (window.AudioContext || window.webkitAudioContext)();
        //   const sourceNode = window.audioContext.createMediaElementSource(videoPlayer);
        // }
        sourceNode.value = window.audioContext.createMediaElementSource(videoElement);
        analyserNode = window.audioContext.createAnalyser();
        sourceNode.value.connect(analyserNode)
        analyserNode.connect(window.audioContext.destination) // Connect to destination, if not already connected

        // Setup the analyser node
        analyserNode.fftSize = 256 // Example size, adjust based on needs
        const bufferLength = analyserNode.frequencyBinCount
        const dataArray = new Uint8Array(bufferLength)

        // Function to loop and update audio level
        const updateAudioLevel = () => {
          // console.log(audioLevel.value); // Ensure this logs values you expect (0 to 1 before scaling)

          analyserNode.getByteFrequencyData(dataArray)

          // Simple approach to calculate volume from frequency data
          let sum = 0
          for (let i = 0; i < bufferLength; i++) {
            sum += dataArray[i]
          }
          let average = sum / bufferLength

          // Update the audio level for display (scale as needed)
         audioLevel.value = average * 100 / 128 // Scale the average to fit your progress bar; adjust as necessary

          animationFrameId = requestAnimationFrame(updateAudioLevel)
        }

        // Start updating audio level
        updateAudioLevel();

        // // updateAudioLevel()
        // if (isNaN(audioLevel.value) || !isFinite(audioLevel.value)) {
        //   audioLevel.value = 0; // Set a default or fallback value
        // }

      } catch (error) {
        console.error('Error creating MediaElementSource:', error)
      }
    // } else {
    //   console.error('Video element not found or not ready')
    // }
  })

})

onUnmounted(() => {
  if (animationFrameId) {
    cancelAnimationFrame(animationFrameId)
  }
  // Disconnect the nodes if necessary when the component is unmounted
  if (analyserNode) {
    analyserNode.disconnect()
  }
  if (audioContext) {
    audioContext.close() // Close the audio context to release resources
  }
})

</script>

<style scoped>
.custom-volume-indicator {
  /* Rotate the progress bar to make it vertical */
  transform: rotate(-90deg);
  /* Transform origin to bottom right to rotate around the bottom-right corner */
  transform-origin: bottom right;

  /* Fixed positioning to place it along the right side */
  position: fixed;
  right: 10px; /* Align to the right side */
  top: 60px; /* Start from the bottom */

  /* Adjust width and height for the visual appearance of the bar */
  width: calc(100vh); /* Full height of the viewport, which becomes the width after rotation */
  height: 7px; /* Thickness of the progress bar */

  z-index: 998;
  //background-color: #2d3b4f;

}
</style>