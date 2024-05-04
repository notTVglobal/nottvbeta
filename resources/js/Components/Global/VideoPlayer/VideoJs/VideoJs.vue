<template>
  <div>
    <img id="custom-overlay" src="/storage/images/logo_white.svg" alt="notTV logo" class="hidden"/>
    <!-- iPhone needs the options loaded from the video tag here to autoplay. -->
    <video-js id="main-player"
              class="video-js vjs-fit"
              :class="videoPlayerStore.class"
              autoplay
              muted
              playsinline
              crossorigin="anonymous"

    >
      <source :type="$page.props.firstPlay.first_play_video_source_type"
              :src="$page.props.firstPlay.first_play_video_source">
    </video-js>

  </div>

</template>

<script setup>
import { onBeforeUnmount, onMounted, ref, watch } from 'vue'
import videojs from 'video.js'
import 'video.js/dist/video-js.css'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'

const videoPlayerStore = useVideoPlayerStore()
const appSettingStore = useAppSettingStore()

const speedIndexL = ref(0); // To cycle through speeds for 'l'
const speedIndexJ = ref(0); // To cycle through speeds for 'j'

const handleKeydown = (event) => {
  // console.log(`Key pressed: ${event.code}`); // Debugging line to check which key is pressed
  if (!videoPlayerStore.player) return

  switch (event.code) {
    case 'Space':
      videoPlayerStore.player.paused() ? videoPlayerStore.player.play() : videoPlayerStore.player.pause()
      videoPlayerStore.player.playbackRate(1.0)
      event.preventDefault()
      break
    case 'ArrowRight':
      videoPlayerStore.player.currentTime(videoPlayerStore.player.currentTime() + 5)
      event.preventDefault()
      break
    case 'ArrowLeft':
      videoPlayerStore.player.currentTime(videoPlayerStore.player.currentTime() - 5)
      event.preventDefault()
      break
    case 'ArrowUp':
      videoPlayerStore.player.volume(Math.min(1, videoPlayerStore.player.volume() + 0.1))
      event.preventDefault()
      break
    case 'ArrowDown':
      videoPlayerStore.player.volume(Math.max(0, videoPlayerStore.player.volume() - 0.1))
      event.preventDefault()
      break
    case 'KeyK': // Toggle play/pause at normal speed
      if (videoPlayerStore.player.paused()) {
        videoPlayerStore.player.playbackRate(1.0)
        videoPlayerStore.player.play()
      } else {
        videoPlayerStore.player.pause()
        videoPlayerStore.player.playbackRate(1.0)
      }
      event.preventDefault()
      break
    case 'KeyL': // Increase playback speed or play at 1x if paused
      if (videoPlayerStore.player.paused()) {
        // If the video is paused, play it at normal speed
        videoPlayerStore.player.playbackRate(1.0);
        videoPlayerStore.player.play();
      } else {
        // If the video is playing, cycle through the speeds
        const speedsL = [1.0, 1.5, 2.0, 5.0, 10.0];
        speedIndexL.value = (speedIndexL.value + 1) % speedsL.length;
        videoPlayerStore.player.playbackRate(speedsL[speedIndexL.value]);
      }
      event.preventDefault();
      break;
    case 'KeyJ': // Reverse playback speed
      // Reverse playback speed is not natively supported in HTML5 and would be computationally expensive.
      // so we will just jump back 1 seconds.
      videoPlayerStore.player.currentTime(videoPlayerStore.player.currentTime() - 1)
      event.preventDefault()
      break;
    case 'KeyM': // Reverse playback speed
        videoPlayerStore.toggleMute()
      event.preventDefault();
      break
  }
}

onMounted(() => {
  const customOverlay = document.getElementById('custom-overlay')
  const videoElementId = videoPlayerStore.videoElementId
  const playerOptions = videoPlayerStore.playerOptions
  // Initialize Video.js player
  const videoPlayer = videojs(videoElementId, playerOptions)
  // const videoPlayer = videoPlayerStore.player;

  // Use the store to manage the player
  videoPlayerStore.setPlayer(videoPlayer)

  // Access the video DOM element controlled by VideoJS
  const videoDomElement = videoPlayer.el()
  videoDomElement.appendChild(customOverlay)

  videoPlayer.on('fullscreenchange', function () {
    if (videoPlayer.isFullscreen()) {
      videoPlayerStore.enterFullscreen()
      document.addEventListener('keydown', handleKeydown)
      videoPlayer.el().appendChild(customOverlay)
      customOverlay.classList.remove('hidden')
      customOverlay.classList.add('visible')
    } else {
      videoPlayerStore.exitFullscreen()
      if (!videoPlayer.paused()) {
        videoPlayer.play() // Resume playback
      }
      document.removeEventListener('keydown', handleKeydown)
      customOverlay.classList.remove('visible')
      customOverlay.classList.add('hidden')
    }
  })

  // Disable right-click on the video element
  videoDomElement.addEventListener('contextmenu', (event) => {
    event.preventDefault()
  })

  // Watch for changes in appSettingStore.fullPage and appSettingStore.ott
  watch(() => [appSettingStore.fullPage, appSettingStore.ott], ([fullPage, ott]) => {
    if (fullPage && !ott) {
      // If fullPage is true and ott is falsy (0 or undefined), attach keydown listener
      if (appSettingStore.loggedIn) {
        document.addEventListener('keydown', handleKeydown);
      }
    } else {
      // Otherwise, remove the keydown listener
      document.removeEventListener('keydown', handleKeydown);
    }
  }, { immediate: true });

})


onBeforeUnmount(() => {
  document.removeEventListener('keydown', handleKeydown)
})

</script>

<style scoped>
.video-js {
  all: initial;
}

.hidden {
  display: none;
}

.visible {
  display: block;
  position: absolute;
  top: 2.5rem;
  left: 2.5rem;
  width: 5rem;
  z-index: 1000; /* Ensure it's above other elements */
}
</style>

