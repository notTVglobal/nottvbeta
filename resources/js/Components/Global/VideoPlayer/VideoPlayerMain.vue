<template>
  <div
  >
    <!--        This v-touch Vuetify directive is not working properly-->
    <!--        Vuetify 3 is still in development. We are implementing-->
    <!--        a vanilla javascript approach with an event listener instead. -->
    <!--        v-touch="() => {clickOnVideoAction()}"-->
    <!-- Video Player -->
    <div :class="videoPlayerStore.videoContainerClass" v-touch="() => {videoPlayerStore.clickOnVideoAction()}" >


      <div :class="videoPlayerStore.class" >
          <videoJs/>


      </div>
    </div>

    <VideoProgressBar v-if="user"/>

    <!-- Over The Top (OTT) -->
    <OttContainer v-if="user" :user="user"/>

    <!-- TopRight Video -->
    <div v-if="!appSettingStore.fullPage && user">

      <!-- notTV Bug -->
      <div class="bugTopRightContainer">
        <img :src="`/storage/images/logo_white_512.png`" class="bugTopRightClass" alt="">
      </div>

      <!-- On Screen Display (OSD)-->
      <!--                <OsdTopRight v-if="videoPlayerStore.showOSD" class="" />-->


      <VideoControlsTopRight
          v-if="!userStore.isMobile"
          class=""
      />

      <!-- OTT Buttons and Displays -->


    </div>

    <!-- FullPage -->
    <div v-if="appSettingStore.fullPage && user">

      <!-- notTV Bug -->
      <div v-if="!videoPlayerStore.osd" class="bugFullPageContainer">
        <img :src="`/storage/images/logo_white_512.png`" class="bugFullPageClass" alt="">
      </div>

      <!-- On Screen Display (OSD) -->
      <OsdFullPage v-if="videoPlayerStore.osd"/>

      <!-- Video Player Controls -->
      <VideoControlsFullPage
          v-if="videoPlayerStore.controls"
      />

    </div>
  </div>
</template>

<script setup>
import { ref, onUnmounted, onMounted, watch } from 'vue'
// import { router } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import videojs from 'video.js'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useNowPlayingStore } from '@/Stores/NowPlayingStore'
import { useStreamStore } from '@/Stores/StreamStore'
import { useChatStore } from '@/Stores/ChatStore'
import { useUserStore } from '@/Stores/UserStore'
import { useOsdStore } from '@/Stores/OsdStore'
import OsdFullPage from '@/Components/Global/Osd/Layout/OsdFullPage'
import VideoControlsFullPage from '@/Components/Global/VideoPlayer/VideoControls/Layout/VideoControlsFullPage'
import OttContainer from '@/Components/Global/Ott/Layout/OttContainer'
import videoJs from '@/Components/Global/VideoPlayer/VideoJs/VideoJs'
import VideoProgressBar from '@/Components/Global/VideoPlayer/VideoIndicators/VideoProgressBar'
import VideoControlsTopRight from '@/Components/Global/VideoPlayer/VideoControls/Layout/VideoControlsTopRight.vue'

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()
const nowPlayingStore = useNowPlayingStore()
const streamStore = useStreamStore()
const chatStore = useChatStore()
const userStore = useUserStore()
const osdStore = useOsdStore()

let showLogin = ref(false)
let screenWidth = ref(screen.width)
let mouseActive = false

onMounted(() => {
// onMounted lifecycle hook: Initializes video player settings and fetches user-specific playback settings.
// TODO:
// 1. Currently, the component fetches initial video source and type from pageProps provided by router.
//    This should be expanded to integrate fetching and applying user-specific video playback settings from the database.
//    Use Axios to fetch settings like last_playback_position and additional_settings (e.g., volume, playback speed) based on the user and content ID.
// 2. Implement logic to decide whether to use these settings directly or prompt the user with a modal (using a component like Modal.vue)
//    to choose whether to resume from the last saved position or start the video anew.
// 3. Extend the videojs initialization to apply these settings once fetched. This includes setting the initial playback position,
//    adjusting volume, and playback speed if those settings exist in the user's video settings.
// 4. Handle potential errors in fetching or applying settings and ensure robust fallbacks to default settings.
// 5. Consider adding event listeners to the video player to update the user's settings in real-time as they adjust their playback preferences.

  const pageProps = usePage().props
  videoPlayerStore.firstPlayVideoName = pageProps.firstPlay.first_play_video_name
  videoPlayerStore.firstPlayVideoSource = pageProps.firstPlay.first_play_video_source
  videoPlayerStore.firstPlayVideoSourceType = pageProps.firstPlay.first_play_video_source_type

  const videoElementId = videoPlayerStore.videoElementId
  const videoPlayer = videojs(videoElementId)

  // Additional logic to fetch and apply user settings will be added here following the above TODOs.
  videoPlayerStore.videoSource = pageProps.firstPlay.first_play_video_source
  videoPlayerStore.videoSourceType = pageProps.firstPlay.first_play_video_source_type

  videoPlayer.ready(() => {

    videoPlayer.controls(false)
    console.log('videoPlayer ready')
    videoPlayerStore.videoPlayerLoaded = true

    // Now that the player is ready, set up the dynamic gain control
    // videoPlayerStore.setupDynamicGainControl(videoPlayer);

  })
  if (!appSettingStore.fullPage) {
    videoPlayerStore.controls = false
  }
})

let props = defineProps({
  src: '',
  type: '',
  user: Object,
  can: Object,
})

const isMobile = ref({
  mobile: userStore.isMobile,
})

// const {
//     isSupported,
//     orientation,
//     angle,
//     lockOrientation,
//     unlockOrientation,
// } = useScreenOrientation()

videoPlayerStore.paused = false
videoPlayerStore.osd = true
videoPlayerStore.channels = false
videoPlayerStore.ottChat = false
videoPlayerStore.ottPlaylist = false
videoPlayerStore.ottFilters = false

onUnmounted(() => {
// tec21 (03/03/23): this doesn't seem to be working :-(
// I think this watch is breaking the app on iPhone.
// watch(orientation, (newOrientation) => {
//     if (userStore.isMobile) {
//         if (newOrientation==='landscape-primary' ) {
//             videoPlayerStore.hideOsdAndControlsAndNav()
//         } else if (newOrientation==='landscape-secondary') {
//             videoPlayerStore.hideOsdAndControlsAndNav()
//         } else if (newOrientation==='portrait-primary') {
//             videoPlayerStore.showOsdAndControlsAndNav()
//         } else if (newOrientation==='portrait-secondary') {
//             videoPlayerStore.showOsdAndControlsAndNav()
//         }
//     }
  videoPlayerStore.disposePlayer()
})


</script>

<!-- A note about audio Tracks. -->
<!-- https://github.com/videojs/http-streaming/blob/main/docs/multiple-alternative-audio-tracks.md -->
<!--The other property that does not have a mapping in the m3u8 is AudioTrack.kind.
It was decided that we would set the kind to main when default is set to true and
in other cases we set it to alternative unless the track has characteristics which
include public.accessibility.describes-video, in which case we set it to main-desc
(note that this kind indicates that the track is a mix of the main track and description,
so it can be played instead of the main track; a track with kind description only has
the description, not the main track).-->

<!-- Goal: see if we can play 2 audio tracks at the same time. And build a pop-up audio
mixer. This will lead into a feature that allows you to record directly through notTV
whatever it is you are watching/clicking through. A web3 video editor. -->
<!-- -tec21 Dec.4, 2022 -->


<!--<script>-->
<!--import videojs from 'video.js';-->

<!--export default {-->
<!--    name: 'VideoPlayer',-->
<!--    props: {-->
<!--        options: {-->
<!--            type: Object,-->
<!--            default() {-->
<!--                return {};-->
<!--            }-->
<!--        }-->
<!--    },-->
<!--    data() {-->
<!--        return {-->
<!--            player: null-->
<!--        }-->
<!--    },-->
<!--    mounted() {-->
<!--        this.player = videojs(this.$refs.videoPlayer, this.options, () => {-->
<!--            this.player.log('onPlayerReady', this);-->
<!--        });-->
<!--    },-->
<!--    beforeDestroy() {-->
<!--        if (this.player) {-->
<!--            this.player.dispose();-->
<!--        }-->
<!--    }-->
<!--}-->
<!--</script>-->

