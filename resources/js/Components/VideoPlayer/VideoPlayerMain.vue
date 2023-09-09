<template>
    <div>
            <!-- Video Player -->
            <div v-touch="() => {clickOnVideoAction()}" :class="videoContainer">
                <div :class="video">
                    <videoJs />
                </div>
            </div>



    <!-- TopRight Video -->

                <!-- notTV Bug -->
                <div v-if="!videoPlayerStore.fullPage && user" class="bugTopRightContainer">
                    <img :src="`/storage/images/logo_white_512.png`" class="bugTopRightClass"></div>

                <!-- Video Player Controls-->
                <VideoControlsTopRight v-if="!videoPlayerStore.fullPage && user && videoPlayerStore.showControls" class="hidden lg:block" />

                <!-- OTT Buttons and Displays -->
                <OttTopRightButtons v-if="!videoPlayerStore.fullPage && user"/>
                <OttTopRightDisplayNowPlayingInfo v-if="!videoPlayerStore.fullPage && user" :user="user"/>
                <OttTopRightDisplayPlaylist v-if="!videoPlayerStore.fullPage && user" :user="user"/>
                <OttTopRightDisplayChannels v-if="!videoPlayerStore.fullPage && user" :user="user"/>
                <OttTopRightDisplayChat v-if="!videoPlayerStore.fullPage && user" :user="user"/>
                <OttTopRightDisplayFilters v-if="!videoPlayerStore.fullPage && user" :user="user"/>


        <!-- FullPage -->

                <!-- notTV Bug -->
                <div v-if="videoPlayerStore.fullPage && user && !videoPlayerStore.showOSD" class="bugFullPageContainer">
                    <img :src="`/storage/images/logo_white_512.png`" class="bugFullPageClass"></div>

                <!-- On Screen Display (OSD) -->
                <OsdFullPage v-if="videoPlayerStore.fullPage && user && videoPlayerStore.showOSD"/>

                <!-- Video Player Controls -->
                <VideoControlsFullPage v-if="videoPlayerStore.fullPage && user && videoPlayerStore.showControls" />

                <!-- Over The Top (OTT) -->
                <OttFullPageButtons v-if="videoPlayerStore.fullPage && user && videoPlayerStore.showOSD"/>
                <OttFullPageDisplayChannels v-if="videoPlayerStore.fullPage && user" />
                <OttFullPageDisplayPlaylist v-if="videoPlayerStore.fullPage && user"/>
                <OttFullPageDisplayChatStandard v-if="videoPlayerStore.fullPage && user" :user="user"/>
                <OttFullPageDisplayFilters v-if="videoPlayerStore.fullPage && user"/>

</div>

</template>


<script setup>
import { computed, ref, defineAsyncComponent } from 'vue'
import { Inertia } from "@inertiajs/inertia"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useStreamStore } from "@/Stores/StreamStore"
import { useChatStore } from "@/Stores/ChatStore"
import { useUserStore } from "@/Stores/UserStore"

const OttFullPageButtons = defineAsyncComponent( () =>
    import('@/Components/VideoPlayer/OttButtons/OttFullPageButtons'))
const OttFullPageDisplayChannels = defineAsyncComponent( () =>
    import('@/Components/VideoPlayer/OttFullPageDisplay/Channels'))
const OttFullPageDisplayPlaylist = defineAsyncComponent( () =>
    import('@/Components/VideoPlayer/OttFullPageDisplay/Playlist'))
const OttFullPageDisplayChatStandard = defineAsyncComponent( () =>
    import('@/Components/VideoPlayer/OttFullPageDisplay/ChatStandard'))
const OttFullPageDisplayFilters = defineAsyncComponent( () =>
    import('@/Components/VideoPlayer/OttFullPageDisplay/Filters'))
const OttTopRightButtons = defineAsyncComponent( () =>
    import('@/Components/VideoPlayer/OttButtons/OttTopRightButtons'))
const OttTopRightDisplayNowPlayingInfo = defineAsyncComponent( () =>
    import('@/Components/VideoPlayer/OttTopRightDisplay/NowPlayingInfo'))
const OttTopRightDisplayFilters = defineAsyncComponent( () =>
    import('@/Components/VideoPlayer/OttTopRightDisplay/Filters'))
const OttTopRightDisplayChat = defineAsyncComponent( () =>
    import('@/Components/VideoPlayer/OttTopRightDisplay/Chat'))
const OttTopRightDisplayPlaylist = defineAsyncComponent( () =>
    import('@/Components/VideoPlayer/OttTopRightDisplay/Playlist'))
const OttTopRightDisplayChannels = defineAsyncComponent( () =>
    import('@/Components/VideoPlayer/OttTopRightDisplay/Channels'))
const VideoControlsFullPage = defineAsyncComponent( () =>
    import('@/Components/VideoPlayer/VideoControls/VideoControlsFullPage'))
const VideoControlsTopRight = defineAsyncComponent( () =>
    import('@/Components/VideoPlayer/VideoControls/VideoControlsTopRight'))
const OsdFullPage = defineAsyncComponent( () =>
    import('@/Components/VideoPlayer/Osd/OsdFullPage'))
// const VideoJs = defineAsyncComponent( () =>
//     import('@/Components/VideoPlayer/VideoJs'))

// import OttFullPageButtons from "@/Components/VideoPlayer/OttButtons/OttFullPageButtons"
// import OttFullPageDisplayChannels from "@/Components/VideoPlayer/OttFullPageDisplay/Channels"
// import OttFullPageDisplayPlaylist from "@/Components/VideoPlayer/OttFullPageDisplay/Playlist"
// import OttFullPageDisplayChatStandard from "@/Components/VideoPlayer/OttFullPageDisplay/ChatStandard"
// import OttFullPageDisplayFilters from "@/Components/VideoPlayer/OttFullPageDisplay/Filters"
// import OttTopRightButtons from "@/Components/VideoPlayer/OttButtons/OttTopRightButtons"
// import OttTopRightDisplayNowPlayingInfo from "@/Components/VideoPlayer/OttTopRightDisplay/NowPlayingInfo"
// import OttTopRightDisplayFilters from "@/Components/VideoPlayer/OttTopRightDisplay/Filters"
// import OttTopRightDisplayChat from "@/Components/VideoPlayer/OttTopRightDisplay/Chat"
// import OttTopRightDisplayPlaylist from "@/Components/VideoPlayer/OttTopRightDisplay/Playlist"
// import OttTopRightDisplayChannels from "@/Components/VideoPlayer/OttTopRightDisplay/Channels"
// import VideoControlsFullPage from "@/Components/VideoPlayer/VideoControls/VideoControlsFullPage"
// import VideoControlsTopRight from "@/Components/VideoPlayer/VideoControls/VideoControlsTopRight"
// import OsdFullPage from "@/Components/VideoPlayer/Osd/OsdFullPage"
import VideoJs from "@/Components/VideoPlayer/VideoJs.vue";

let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let chatStore = useChatStore()
let userStore = useUserStore()

let showLogin = ref(false)
let screenWidth = ref(screen.width)
let mouseActive = false

let props = defineProps({
    src: '',
    type: '',
    user: Object,
    can: Object,
    videoSource: '',
    videoSourceType: '',
})

const isMobile = ref({
    mobile: userStore.isMobile,
})

const videoContainer = computed(() => ({
    welcomeVideoContainer: userStore.currentPage === 'welcome',
    fullPageVideoContainer: videoPlayerStore.fullPage && !videoPlayerStore.pip && userStore.currentPage !== 'welcome',
    topRightVideoContainer: !videoPlayerStore.fullPage && !videoPlayerStore.pip && userStore.currentPage !== 'welcome',
    pipVideoContainer: videoPlayerStore.pip && userStore.currentPage !== 'welcome'
}))

const video = computed(() => ({
    welcomeVideoClass: userStore.currentPage === 'welcome',
    fullPageVideoClass: videoPlayerStore.fullPage && !videoPlayerStore.pip && userStore.currentPage !== 'welcome',
    topRightVideoClass: !videoPlayerStore.fullPage && !videoPlayerStore.pip && userStore.currentPage !== 'welcome',
    pipVideoClass: videoPlayerStore.pip && userStore.currentPage !== 'welcome'
}))

// const {
//     isSupported,
//     orientation,
//     angle,
//     lockOrientation,
//     unlockOrientation,
// } = useScreenOrientation()

videoPlayerStore.paused = false
chatStore.showChat = false
streamStore.showChannels = false
streamStore.showOSD = false

// onUnmounted(() => {
//     let player = videojs('main-player');
//     player.on('ended', function() {
//         this.dispose();
//     });
// })


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

function backToPage() {
    videoPlayerStore.makeVideoTopRight();
    chatStore.showChat = false;
    streamStore.showOSD = false;
}

function clickOnVideoAction() {

    if (!userStore.isMobile && !videoPlayerStore.currentPageIsStream) {
        videoPlayerStore.toggleOsdAndControls()
    }
    if (!videoPlayerStore.currentPageIsStream) {
        Inertia.visit('/stream')
    }
    if(videoPlayerStore.currentPageIsStream) {
        videoPlayerStore.toggleControls()

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

function mouseEnter(event) {
    mouseActive = true
    // console.log(mouseActive);
    // videoPlayerStore.showOsdControlsOnly()
    // document.addEventListener('mousemove', mouseMove, true);
    // setInterval(function() {
    //     videoPlayerStore.hideOsdAndControls()
    // }, 3000);
    // setInterval(function() {
    //     mouseActive = false
    //     videoPlayerStore.hideOsdControlsOnly()
    // }, 3000);
}
function mouseLeave(event) {
    mouseActive = false
    // console.log(mouseActive);

    // videoPlayerStore.hideOsdControlsOnly()
    // this.popup = false;
    // document.addEventListener('mousemove', mouseMove, false);

}
function mouseMove(event) {
    mouseActive = true
    // videoPlayerStore.showOsdControlsOnly()
    // console.log(event.clientX, event.clientY);
    // setInterval(function() {
    //     mouseActive = false
    //     videoPlayerStore.hideOsdAndControls()
    // }, 3000);
}

</script>

<!-- -tec21 Dec.4, 2022 -->
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


