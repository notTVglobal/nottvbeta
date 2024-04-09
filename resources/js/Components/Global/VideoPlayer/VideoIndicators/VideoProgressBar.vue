<template>
  <div v-show="!appSettingStore.showNavDropdown"
       class="tooltip tooltip-bottom hover-time"
       :style="{ left: hoverPosition }"
       :data-tip="hoverTime">
    <div class="thumbnail"></div>
    <button class="custom-progress-bar"
            id="progress-bar"
            @mousedown="handleProgressClick($event)"
            @mousemove="showHoverTime($event)"
            @mouseleave="hideHoverTime">
      <div class="custom-progress" :style="{ width: progressPercentage + '%' }" id="progress">
        <!--                <div v-if="isHovering" class="hover-overlay">{{timeRemainingTime}}</div>-->
      </div>
    </button>
  </div>

</template>

<script setup>
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import videojs from 'video.js'
import { computed, onMounted, ref } from 'vue'

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()

const progressBarRef = ref(null)
const progressRef = ref(null)
const seekHandleRef = ref(null)
const timeRemainingTime = ref('00:00') // Default tooltip text, initialize with "00:00"
const videoDuration = ref('00:00')
const hoverTime = ref('00:00:00')
const hoverPosition = ref('0px') // Initialize with '0px'
const isHovering = ref(false) // Initialize as false
let screenWidth = ref(screen.width)
let mouseActive = false

const formattedTime = computed(() => videoPlayerStore.formattedTime);
const progressPercentage = computed(() => {
  const currentTime = videoPlayerStore.currentTime;
  const duration = videoPlayerStore.duration;
  return (currentTime / duration) * 100 || 0;
});

onMounted(() => {
  // Set the references to the DOM elements here
  progressBarRef.value = document.getElementById('progress-bar')
  progressRef.value = document.getElementById('progress') // If progressRef refers to the same element
  seekHandleRef.value = document.getElementById('seek-handle') // If you have a seek handle

  // progressRef.value = document.getElementById('progress-bar');
  if (!progressRef.value) {
    console.error('Progress bar element not found.')
  }
  // Add an event listener to update the custom progress bar when the video progresses
  // const videoPlayer = videojs('main-player')
  // videoPlayer.on('timeupdate', () => {
  //   let currentTime = videoPlayer.currentTime()
  //   let duration = videoPlayer.duration()
  //   let progressPercentage = (currentTime / duration) * 100
  //   const formattedDuration = formatDuration(duration)
  //   // Format the time as "00:00:00"
  //   const hours = Math.floor(currentTime / 3600)
  //   const minutes = Math.floor(currentTime / 60)
  //   const seconds = Math.floor(currentTime % 60)
  //   timeRemainingTime.value = `${formatDuration(currentTime)} / ${formattedDuration}`
  //
  //   // Make sure progressRef.value is not null before setting the width
  //   if (progressRef.value) {
  //     progressRef.value.style.width = `${progressPercentage}%`
  //   }
  // })
})

function handleProgressClick(event) {
  const videoPlayer = videojs('main-player')
  let progressBarRect = progressBarRef.value.getBoundingClientRect()
  let offsetX = event.clientX - progressBarRect.left
  let progressBarWidth = progressBarRect.width

  // Calculate the seek percentage
  let seekPercentage = (offsetX / progressBarWidth) * 100

  // Calculate the seek time in seconds
  let seekTime = (seekPercentage / 100) * videoPlayer.duration()

  // Set the video's current time to seekTime
  videoPlayer.currentTime(seekTime)
}

  function formatDuration(durationInSeconds) {
    const hours = Math.floor(durationInSeconds / 3600)
    const minutes = Math.floor(durationInSeconds / 60)
    const seconds = Math.floor(durationInSeconds % 60)
    if (hours > 0) {
      return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`
    } else {
      return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`
    }
  }

function showHoverTime(event) {
  const videoPlayer = videojs('main-player')
  // Calculate the hover time based on the mouse position
  const progressBarRect = progressBarRef.value.getBoundingClientRect()
  const offsetX = event.clientX - progressBarRect.left
  const progressBarWidth = progressBarRect.width

  // Subtract 50% of the width from offsetX
  // (to compensate for the tooltip showing in the center)
  const adjustedOffsetX = offsetX - progressBarWidth / 2

  const hoverPercentage = (offsetX / progressBarWidth) * 100
  const hoverTimeInSeconds = (hoverPercentage / 100) * videoPlayer.duration()

  // Format the hover time as "00:00:00"
  const hours = Math.floor(hoverTimeInSeconds / 3600)
  const minutes = Math.floor((hoverTimeInSeconds % 3600) / 60)
  const seconds = Math.floor(hoverTimeInSeconds % 60)
  hoverTime.value = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`

  // Set the left position of hoverTime to match the cursor's X-coordinate
  hoverPosition.value = `${adjustedOffsetX}px`

  // Set isHovering to true when hovering
  isHovering.value = true
}

function hideHoverTime() {
  // Hide the hover time when the mouse leaves the progress bar
  hoverTime.value = '00:00:00'
  isHovering.value = false
}
</script>


<style scoped>

.tooltip-bottom {
  position: fixed;
  top: 4rem;
  left: 0;
  padding-bottom: 20px;
  width: 100%;
  height: 5px;
  z-index: 998;
}

.custom-progress-bar {
  position: fixed;
  top: 4rem;
  left: 0;
  padding-bottom: 20px;
  width: 100%;
  height: 5px;
  z-index: 998;
  //background-color: #2d3b4f;
  @apply cursor-progress;
}

.custom-progress {
  position: absolute;
  padding: 0;
  height: 2px;
  //width: 0;
  background-color: #062fad;
  z-index: 998;
}

.hover-time {
  //position: absolute;
  //top: 2px; /* Adjust the top position as needed */
  //white-space: nowrap;
  //background-color: #000;
}

/* Add CSS for the active class */
.hover-time.active {
  display: block;
}

.hover-overlay {
  position: fixed;
  top: 4.5rem;
  left: 1rem;
  margin-top: 2px;
  background-color: black;
  display: inline-block; /* This makes the width match the text width */
  padding: 2px 8px; /* Adjust padding as needed */
  color: white; /* Set the text color to contrast with the background */
  @apply w-fit bg-opacity-60;
}

/* Define the dimensions of each thumbnail within the sprite */
.thumbnail {
  width: 100px;
  height: 75px;
  //background-image: url('/storage/images/logo_white_512.png');
  background-size: 800px 75px; /* Adjust width and height according to your sprite image */
  z-index: 999;
}

/* Apply the hover effect */
.thumbnail:hover {
  background-position: -200px 0; /* Adjust the position to show the desired thumbnail */
}
</style>