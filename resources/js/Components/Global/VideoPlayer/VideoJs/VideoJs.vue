<template>
  <div :class="videoPlayerStore.class">

    <!-- iPhone needs the options loaded from the video tag here to autoplay. -->
    <video-js id="main-player"
              class="video-js vjs-fit"
              :class="videoPlayerStore.class"
              autoplay
              muted
              playsinline
    >
      <source :type="$page.props.firstPlay.first_play_video_source_type"
              :src="$page.props.firstPlay.first_play_video_source">
    </video-js>

  </div>

</template>

<script setup>
import { onMounted } from 'vue'
import videojs from 'video.js'
import 'video.js/dist/video-js.css'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'

const appSettingStore = useAppSettingStore()
import { useStreamStore } from '@/Stores/StreamStore'
import { useChatStore } from '@/Stores/ChatStore'
import { useUserStore } from '@/Stores/UserStore'

const videoPlayerStore = useVideoPlayerStore()
const streamStore = useStreamStore()
const chatStore = useChatStore()
const userStore = useUserStore()

onMounted(() => {
  const videoElementId = 'main-player'
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
    controls: false,
    muted: true, // Start muted to comply with autoplay policies
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
  const videoPlayer = videojs(videoElementId, playerOptions);

  // Use the store to manage the player
  videoPlayerStore.setPlayer(videoPlayer);

  // Access the video DOM element controlled by VideoJS
  const videoDomElement = videoPlayer.el();

  // Disable right-click on the video element
  videoDomElement.addEventListener('contextmenu', (event) => {
    event.preventDefault();
  });

})
</script>

<style scoped>
.video-js {
  all: initial;
}
</style>

