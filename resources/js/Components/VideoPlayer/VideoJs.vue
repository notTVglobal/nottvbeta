<template>
    <div :class="videoPlayerStore.class">

<!--        <ProgressBar />-->
<!-- iPhone needs the options loaded from the video tag here to autoplay. -->
        <video-js id="main-player"
                  class="video-js vjs-fit"
                  :class="videoPlayerStore.class"
                  autoplay
                  muted
                  playsinline
        >
            <source :type="$page.props.firstPlayVideoSourceType" :src="$page.props.firstPlayVideoSource">
<!--            <source type="video/youtube" src="https://www.youtube.com/watch?v=fqaHrwOhihI">-->
<!--            <source type="video/youtube" src="https://www.youtube.com/watch?v=xjS6SftYQaQ&list=SPA60DCEB33156E51F">-->
        </video-js>

    </div>

</template>


<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useStreamStore } from "@/Stores/StreamStore"
import { useChatStore } from "@/Stores/ChatStore"
import { useUserStore } from "@/Stores/UserStore"
import {defineAsyncComponent, onBeforeUnmount, onMounted, ref} from "vue"
import videojs from "video.js"
import 'video.js/dist/video-js.css';
import youtube from "videojs-youtube"
// import ProgressBar from "@/Components/VideoPlayer/Osd/ProgressBar.vue";

// const ProgressBar = defineAsyncComponent( () =>
//     import('@/Components/VideoPlayer/Osd/ProgressBar')
// )


let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let chatStore = useChatStore()
let userStore = useUserStore()

onMounted( () => {
    let videoPlayer = videojs('main-player', {
        controlBar: {
            audioTrackButton: false,
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
            progressControl: { seekBar: false },
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
        controls: false,
        muted: false,
        inactivityTimeout: 0,
        autoplay: true,
        preload: 'auto',
        techOrder: ["html5", "youtube"],
        youtube: {"customVars":
                    {
                    "wmode": "transparent"
                    }}
    })
    // Ensure that the progress-bar element exists before setting progressRef

    // let videoPlayer = videojs('main-player')
    videoPlayer.ready(function() {
        videoPlayer.muted(true)
        videoPlayerStore.muted = true
        videoPlayer.play();

        // Ensure that the seek-handle element exists before adding the event listener
        // seekHandleRef.value = document.getElementById('seek-handle');
        // if (seekHandleRef.value) {
        //     seekHandleRef.value.addEventListener('mousedown', handleMouseDown);
        // }
    })

// Handle the fullscreen change event
    videoPlayer.on('fullscreenchange', () => {
        if (videoPlayer.isFullscreen()) {
            // Video is entering fullscreen mode
            // You can add custom behavior for entering fullscreen here if needed
        } else {
            // Video is exiting fullscreen mode
            // Check if the video was playing before entering fullscreen
            if (videoPlayer.paused() === false) {
                // Resume playback after exiting fullscreen
                videoPlayer.play();
            }
        }
    })



})

onBeforeUnmount(() => {
    let videoPlayer = videojs('main-player')
    // Cleanup event listeners when the component is unmounted
    videoPlayer.off('timeupdate');
    // seekHandleRef.value.removeEventListener('mousedown', handleMouseDown);
});

// async function getFirstPlaySettings() {
//     await axios.get('/api/app_settings')
//         .then(response => {
//             videoPlayerStore.videoSource = response.data[0].first_play_video_source
//             videoPlayerStore.videoSourceType = response.data[0].first_play_video_source_type
//             videoPlayerStore.videoName = response.data[0].first_play_video_name
//             console.log('app settings retrieved.');
//
//         })
//         .catch(error => {
//             console.log(error)
//         })
//     // setVideoOptions()
//     // videoJs = videojs('main-player', videoOptions)
// }




</script>

<style scoped>
.video-js {
    all: initial;
}
</style>

