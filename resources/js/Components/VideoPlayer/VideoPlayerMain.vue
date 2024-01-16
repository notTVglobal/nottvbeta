<template>
    <div>
<!--        This v-touch Vuetify directive is not working properly-->
<!--        Vuetify 3 is still in development. We are implementing-->
<!--        a vanilla javascript approach with an event listener instead. -->
<!--        v-touch="() => {clickOnVideoAction()}"-->
    <!-- Video Player -->
        <div
             :class="videoPlayerStore.videoContainerClass"
             >
            <div :class="videoPlayerStore.class">
              <videoJs /></div>

        </div>

        <div v-show="videoPlayerStore.currentPageIsStream && !userStore.showNavDropdown"
             class="tooltip tooltip-bottom hover-time"
             :style="{ left: hoverPosition }"
             :data-tip="hoverTime">
            <div class="thumbnail"></div>
            <button class="custom-progress-bar"
                    id="progress-bar"
                    @mousedown="handleProgressClick($event)"
                    @mousemove="showHoverTime($event)"
                    @mouseleave="hideHoverTime">
                <div class="custom-progress" id="progress">
        <!--                <div v-if="isHovering" class="hover-overlay">{{timeRemainingTime}}</div>-->
                </div>
            </button>
        </div>

    <!-- TopRight Video -->
        <div v-if="!videoPlayerStore.fullPage && user">

            <!-- notTV Bug -->
            <div class="bugTopRightContainer">
                <img :src="`/storage/images/logo_white_512.png`" class="bugTopRightClass">
            </div>

            <!-- On Screen Display (OSD)-->
<!--                <OsdTopRight v-if="videoPlayerStore.showOSD" class="" />-->

            <!-- Video Player Controls-->
            <VideoControlsTopRight
                v-if="videoPlayerStore.controls"
                class="hidden lg:block"
            />

            <!-- OTT Buttons and Displays -->
            <OttTopRightButtons :key="userStore.key"/>
            <OttTopRightDisplayNowPlayingInfo :user="user"/>
            <OttTopRightDisplayPlaylist :user="user"/>
            <OttTopRightDisplayChannels :user="user"/>
            <OttTopRightDisplayChat :user="user"/>
            <OttTopRightDisplayFilters :user="user"/>

        </div>

        <!-- FullPage -->
        <div v-if="videoPlayerStore.fullPage && user">

            <!-- notTV Bug -->
            <div v-if="! videoPlayerStore.osd" class="bugFullPageContainer">
                <img :src="`/storage/images/logo_white_512.png`" class="bugFullPageClass">
            </div>

                <!-- On Screen Display (OSD) -->
            <OsdFullPage v-show="videoPlayerStore.osd" />

            <!-- Video Player Controls -->
            <VideoControlsFullPage
                v-if="videoPlayerStore.controls"
            />

            <!-- Over The Top (OTT) -->
            <OttFullPageButtons v-if="videoPlayerStore.osd"/>
            <OttFullPageDisplayChannels />
            <OttFullPageDisplayPlaylist />
            <OttFullPageDisplayChat :user="user"/>
            <OttFullPageDisplayFilters />

        </div>
    </div>
</template>

<script setup>
import { ref, onUnmounted, onMounted } from 'vue'
import { Inertia } from "@inertiajs/inertia"
import { useAppSettingStore } from '@/Stores/AppSettingStore';
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useStreamStore } from "@/Stores/StreamStore"
import { useChatStore } from "@/Stores/ChatStore"
import { useUserStore } from "@/Stores/UserStore"

import OttFullPageButtons from "@/Components/VideoPlayer/OttButtons/OttFullPageButtons"
import OttFullPageDisplayChannels from "@/Components/VideoPlayer/OttFullPageDisplay/Channels"
import OttFullPageDisplayPlaylist from "@/Components/VideoPlayer/OttFullPageDisplay/Playlist"
import OttFullPageDisplayChat from "@/Components/VideoPlayer/OttFullPageDisplay/Chat"
import OttFullPageDisplayFilters from "@/Components/VideoPlayer/OttFullPageDisplay/Filters"
import OttTopRightButtons from "@/Components/VideoPlayer/OttButtons/OttTopRightButtons"
import OttTopRightDisplayNowPlayingInfo from "@/Components/VideoPlayer/OttTopRightDisplay/NowPlayingInfo"
import OttTopRightDisplayFilters from "@/Components/VideoPlayer/OttTopRightDisplay/Filters"
import OttTopRightDisplayChat from "@/Components/VideoPlayer/OttTopRightDisplay/Chat"
import OttTopRightDisplayPlaylist from "@/Components/VideoPlayer/OttTopRightDisplay/Playlist"
import OttTopRightDisplayChannels from "@/Components/VideoPlayer/OttTopRightDisplay/Channels"
import VideoControlsFullPage from "@/Components/VideoPlayer/VideoControls/VideoControlsFullPage"
import VideoControlsFullPageMobile from "@/Components/VideoPlayer/VideoControls/VideoControlsFullPageMobile"
import VideoControlsTopRight from "@/Components/VideoPlayer/VideoControls/VideoControlsTopRight"
import OsdTopRight from "@/Components/VideoPlayer/Osd/OsdTopRight"
import OsdFullPage from "@/Components/VideoPlayer/Osd/OsdFullPage"
import videojs from 'video.js'
import { tryOnBeforeMount, useScreenOrientation } from '@vueuse/core'
import videoJs from "@/Components/VideoPlayer/VideoJs"
import ProgressBar from "@/Components/VideoPlayer/Osd/ProgressBar.vue";
// const VideoJs = defineAsyncComponent( () =>
//     import('@/Components/VideoPlayer/VideoJs')
// )
//
const appSettingStore = useAppSettingStore();
let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let chatStore = useChatStore()
let userStore = useUserStore()


let showLogin = ref(false)
let screenWidth = ref(screen.width)
let mouseActive = false

const progressBarRef = ref(null);
const progressRef = ref(null);
const seekHandleRef = ref(null);
const timeRemainingTime = ref('00:00'); // Default tooltip text, initialize with "00:00"
const videoDuration = ref('00:00');
const hoverTime = ref('00:00:00');
const hoverPosition = ref('0px'); // Initialize with '0px'
const isHovering = ref(false); // Initialize as false
// const elementRef = ref(null);


onMounted( () => {

    // if (elementRef.value) {
    //     elementRef.value.addEventListener('touchstart', clickOnVideoAction);
    //     // For mouse click support in non-touch devices:
    //     elementRef.value.addEventListener('click', clickOnVideoAction);
    // }
    // Set the references to the DOM elements here
    progressBarRef.value = document.getElementById('progress-bar');
    progressRef.value = document.getElementById('progress'); // If progressRef refers to the same element
    seekHandleRef.value = document.getElementById('seek-handle'); // If you have a seek handle

    // progressRef.value = document.getElementById('progress-bar');
    if (!progressRef.value) {
        console.error('Progress bar element not found.');
    }
    // Add an event listener to update the custom progress bar when the video progresses
    let videoPlayer = videojs('main-player')
    videoPlayer.on('timeupdate', () => {
        let currentTime = videoPlayer.currentTime();
        let duration = videoPlayer.duration();
        let progressPercentage = (currentTime / duration) * 100;
        const formattedDuration = formatDuration(duration);
        // Format the time as "00:00:00"
        const hours = Math.floor(currentTime / 3600);
        const minutes = Math.floor(currentTime / 60);
        const seconds = Math.floor(currentTime % 60);
        timeRemainingTime.value = `${formatDuration(currentTime)} / ${formattedDuration}`;

        // Make sure progressRef.value is not null before setting the width
        if (progressRef.value) {
            progressRef.value.style.width = `${progressPercentage}%`;
        }

    });
    videoPlayer.ready(() => {
        videoPlayer.controls(false)
        videoPlayerStore.videoPlayerLoaded = true
    })
    if (!videoPlayerStore.fullPage) {
        videoPlayerStore.controls = false
    }
})

function formatDuration(durationInSeconds) {
    const hours = Math.floor(durationInSeconds / 3600);
    const minutes = Math.floor(durationInSeconds / 60);
    const seconds = Math.floor(durationInSeconds % 60);
    if (hours > 0) {
        return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    } else {
        return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    }
}

function showHoverTime(event) {
    let videoPlayer = videojs('main-player')
    // Calculate the hover time based on the mouse position
    const progressBarRect = progressBarRef.value.getBoundingClientRect();
    const offsetX = event.clientX - progressBarRect.left;
    const progressBarWidth = progressBarRect.width;

    // Subtract 50% of the width from offsetX
    // (to compensate for the tooltip showing in the center)
    const adjustedOffsetX = offsetX - progressBarWidth / 2;

    const hoverPercentage = (offsetX / progressBarWidth) * 100;
    const hoverTimeInSeconds = (hoverPercentage / 100) * videoPlayer.duration();

    // Format the hover time as "00:00:00"
    const hours = Math.floor(hoverTimeInSeconds / 3600);
    const minutes = Math.floor((hoverTimeInSeconds % 3600) / 60);
    const seconds = Math.floor(hoverTimeInSeconds % 60);
    hoverTime.value = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

    // Set the left position of hoverTime to match the cursor's X-coordinate
    hoverPosition.value = `${adjustedOffsetX}px`;

    // Set isHovering to true when hovering
    isHovering.value = true;
}

function hideHoverTime() {
    // Hide the hover time when the mouse leaves the progress bar
    hoverTime.value = '00:00:00';
    isHovering.value = false;
}

let props = defineProps({
    src: '',
    type: '',
    user: Object,
    can: Object,
})

const isMobile = ref({
    mobile: userStore.isMobile,
})

function handleProgressClick(event) {
    let videoPlayer = videojs('main-player');
    let progressBarRect = progressBarRef.value.getBoundingClientRect();
    let offsetX = event.clientX - progressBarRect.left;
    let progressBarWidth = progressBarRect.width;

    // Calculate the seek percentage
    let seekPercentage = (offsetX / progressBarWidth) * 100;

    // Calculate the seek time in seconds
    let seekTime = (seekPercentage / 100) * videoPlayer.duration();

    // Set the video's current time to seekTime
    videoPlayer.currentTime(seekTime);
    // videoPlayer.play();
}

// const videoContainer = computed(() => ({
//     welcomeVideoContainer: userStore.currentPage === 'welcome',
//     fullPageVideoContainer: videoPlayerStore.fullPage && !videoPlayerStore.pip && userStore.currentPage !== 'welcome',
//     // fullPageVideoContainer: videoPlayerStore.fullPage && !userStore.isMobile,
//     // fullPageVideoContainerMobile: videoPlayerStore.fullPage && userStore.isMobile,
//     topRightVideoContainer: !videoPlayerStore.fullPage && !videoPlayerStore.pip && userStore.currentPage !== 'welcome',
//     // topRightVideoContainer: !videoPlayerStore.fullPage && !userStore.isMobile,
//     // topRightVideoContainerMobile: !videoPlayerStore.fullPage && userStore.isMobile,
//     pipVideoContainer: videoPlayerStore.pip && userStore.currentPage !== 'welcome'
// }))
//
// const video = computed(() => ({
//     welcomeVideoClass: userStore.currentPage === 'welcome',
//     fullPageVideoClass: videoPlayerStore.fullPage && !videoPlayerStore.pip && userStore.currentPage !== 'welcome',
//     // fullPageVideoClass: videoPlayerStore.fullPage && !userStore.isMobile,
//     // fullPageVideoClassMobile: videoPlayerStore.fullPage && userStore.isMobile,
//     topRightVideoClass: !videoPlayerStore.fullPage && !videoPlayerStore.pip && userStore.currentPage !== 'welcome',
//     // topRightVideoClass: !videoPlayerStore.fullPage && !userStore.isMobile,
//     // topRightVideoClassMobile: !videoPlayerStore.fullPage && userStore.isMobile,
//     pipVideoClass: videoPlayerStore.pip && userStore.currentPage !== 'welcome'
// }))

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
    // let player = videojs('main-player');
    // player.on('ended', function() {
    //     this.dispose();
    // });
    if (elementRef.value) {
        elementRef.value.removeEventListener('touchstart', clickOnVideoAction);
        elementRef.value.removeEventListener('click', clickOnVideoAction);
    }
})


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
// })

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

function backToPage() {
    videoPlayerStore.makeVideoTopRight();
    videoPlayerStore.ottChat = false;
    videoPlayerStore.osd = false;
}

const clickOnVideoAction = () => {

    // if (!userStore.isMobile && !videoPlayerStore.currentPageIsStream) {
    //     videoPlayerStore.toggleOsdAndControls()
    // }
    // if (!videoPlayerStore.currentPageIsStream) {
    //     Inertia.visit('/stream')
    //     // videoPlayerStore.controls = true
    // }
    // if (videoPlayerStore.fullPage === false) {
    //     videoPlayerStore.makeVideoFullPage()
    //     videoPlayerStore.fullPage = true
    //     videoPlayerStore.controls = true
    // }
    // else if(videoPlayerStore.fullPage === true) {
    //     // let videoPlayer = videojs('main-player');
    //     videoPlayerStore.togglePlay()
    //     // videoPlayerStore.toggleOsdAndControls()
    // }

    if (!videoPlayerStore.currentPageIsStream && !appSettingStore.pipChatMode) {
        // videoPlayerStore.makeVideoFullPage()
        // videoPlayerStore.fullPage = true
        // videoPlayerStore.controls = true
        // Inertia.visit('/stream')

        videoPlayerStore.makeVideoFullPage()
        videoPlayerStore.ott = 0
        videoPlayerStore.fullPage = true
        if (!videoPlayerStore.currentPageIsStream) {
            userStore.prevUrl = window.history.state.url
        }
        Inertia.visit('/stream')

        // if (userStore.isMobile) {
        //     videoPlayerStore.makeVideoFullPage()
        //     videoPlayerStore.fullPage = true
        //     Inertia.visit('/stream')
        // } else {
        //     videoPlayerStore.toggleControls();
        // }
        //

        // videoPlayerStore.controls = !!videoPlayerStore.controls;
    }
    else {
        // let videoPlayer = videojs('main-player');
        if (userStore.isMobile) {
            videoPlayerStore.controls = !videoPlayerStore.controls
        } else {
            videoPlayerStore.togglePlay()
        }

        // videoPlayerStore.toggleOsdAndControls()
    }
    // if (videoPlayerStore.currentPageIsStream === true) {
    //     // if (userStore.isMobile && orientation.value === 'landscape-primary') {
    //     //         videoPlayerStore.toggleOsdAndControlsAndNav()
    //     // } else if (!userStore.isMobile) {
    //     //     videoPlayerStore.toggleOsdAndControls()
    //     // }
    //     // videoPlayerStore.toggleOsdAndControlsAndNav()
    // } else {
    //     Inertia.visit('/stream')
    // }
    // videoPlayerStore.ottClass = 'OttClose'
    // videoPlayerStore.ott = 0
    // if(userStore.isMobile) {
    //
    // } else {
    //     // videoPlayerStore.toggleOsdAndControls()
    // }
    // }
}

// function mouseEnter(event) {
//     mouseActive = true
//     if (!videoPlayerStore.fullPage) {
//         // videoPlayerStore.controls = true
//     }
//     // console.log(mouseActive);
//     // videoPlayerStore.showOsdControlsOnly()
//     // document.addEventListener('mousemove', mouseMove, true);
//     // setInterval(function() {
//     //     videoPlayerStore.hideOsdAndControls()
//     // }, 3000);
//     // setInterval(function() {
//     //     mouseActive = false
//     //     videoPlayerStore.hideOsdControlsOnly()
//     // }, 3000);
// }
// function mouseLeave(event) {
//     mouseActive = false
//     if (!videoPlayerStore.fullPage) {
//         // videoPlayerStore.controls = false
//     }
//     // console.log(mouseActive);
//
//     // videoPlayerStore.hideOsdControlsOnly()
//     // this.popup = false;
//     // document.addEventListener('mousemove', mouseMove, false);
//
// }
// function mouseMove(event) {
//     mouseActive = true
//     // videoPlayerStore.showOsdControlsOnly()
//     // console.log(event.clientX, event.clientY);
//     // setInterval(function() {
//     //     mouseActive = false
//     //     videoPlayerStore.hideOsdAndControls()
//     // }, 3000);
// }

</script>

<style scoped>

.tooltip-bottom {
    position: fixed;
    top:4rem;
    left:0;
    padding-bottom: 20px;
    width: 100%;
    height: 5px;
    z-index:998;
}

.custom-progress-bar {
    position: fixed;
    top:4rem;
    left:0;
    padding-bottom: 20px;
    width: 100%;
    height: 5px;
    z-index:998;
    //background-color: #2d3b4f;
    @apply cursor-progress;
}

.custom-progress {
    position: absolute;
    padding: 0;
    height: 2px;
    width: 0;
    background-color: #062fad;
    z-index:998;
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
    position:fixed;
    top:4.5rem;
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
    z-index:999;
}

/* Apply the hover effect */
.thumbnail:hover {
    background-position: -200px 0; /* Adjust the position to show the desired thumbnail */
}
</style>

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

