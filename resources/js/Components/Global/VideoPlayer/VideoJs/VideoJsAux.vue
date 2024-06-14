<template>
  <div class="w-full h-auto max-w-screen">

    <video-js :id="id"
              class="video-js vjs-layout-tiny w-full max-w-full min-h-64 h-auto bg-blue-400"
              autoplay
              muted
              playsinline
              crossorigin="anonymous"
    >
      <source :src="source"
              :type="sourceType">
    </video-js>

  </div>
</template>


<script setup>
import { onMounted, onBeforeUnmount } from 'vue'
import videojs from 'video.js'
import 'video.js/dist/video-js.css'
import { useVideoAuxPlayerStore } from '@/Stores/VideoAuxPlayerStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'

const appSettingStore = useAppSettingStore()
import { useStreamStore } from '@/Stores/StreamStore'
import VideoJs from '@/Components/Global/VideoPlayer/VideoJs/VideoJs.vue'

const videoAuxPlayerStore = useVideoAuxPlayerStore()
const streamStore = useStreamStore()

const props = defineProps({
  options: Object,
  id: String,
  source: String,
  sourceType: String,
})

onMounted(() => {
  const videoElementId = props.id
  const playerOptions = {
    // Define your player options here
    controlBar: {
      audioTrackButton: false,
      autoHide: true,
      captionsButton: false,
      chaptersButton: false,
      currentTimeDisplay: false,
      customControlSpacer: false,
      descriptionsButton: false,
      durationDisplay: false,
      fullscreenToggle: false, // Removes the full screen option
      liveDisplay: false,
      pictureInPictureToggle: false,
      playToggle: false,
      playbackRateMenuButton: false,
      progressControl: {seekBar: false},
      remainingTimeDisplay: false,
      seekToLive: false,
      subsCapsButton: false,
      subtitlesButton: false,
      timeDivider: false,
      volumePanel: {
        inline: true,
        volumeBar: {
          vertical: false,
          liveDisplay: false,
        },
      },
    },
    userActions: {
      hotkeys: true, // Enable hotkeys to show/hide controls
    },
    controls: true,
    muted: false, // Start muted to comply with autoplay policies
    inactivityTimeout: 0,
    autoplay: true,
    preload: 'auto',
    // techOrder: ['html5'],
    html5: {
      hls: {
        overrideNative: !videojs.browser.IS_SAFARI, // Override native HLS on non-Safari browsers
      },
    },
  }

  // Initialize Video.js player
  const videoAuxPlayer = videojs(videoElementId, playerOptions)

  // Use the store to manage the player
  videoAuxPlayerStore.setPlayer(videoAuxPlayer)

  // Access the video DOM element controlled by VideoJS
  const videoDomElement = videoAuxPlayer.el()

  // Disable right-click on the video element
  videoDomElement.addEventListener('contextmenu', (event) => {
    event.preventDefault()
  })
})

onBeforeUnmount(() => {
  let videoAuxPlayer = videojs(props.id)
  videoAuxPlayer.dispose()
})

</script>