<template>

    <div :class="videoPlayerStore.videoContainerClass">
        <div :class="videoPlayerStore.class">

    <!-- Login for Welcome Page (logged out) -->
            <Teleport to="body">
                <Login v-if="!user" :show="showLogin" @close="showLogin = false" />
            </Teleport>

<!--            <div class="z-50 text-black fixed top-0 bottom-0 left-0 right-0 item-center mx-auto my-auto pt-36 bg-purple-300">{{orientation}}</div>-->

            <!-- Video Player -->
            <div v-touch="() => {clickOnVideoAction()}">
                <video id="main-player"
                       class="video-js vjs-big-play-centered vjs-fill"
                />
            </div>



    <!-- TopRight Video -->
            <div v-if="!videoPlayerStore.fullPage && user">
                <!-- isMobile -->
                <div v-if="userStore.isMobile" >
                    <!-- OSD and Controls hidden on mobile -->
                </div>

                <!-- !isMobile -->
                <div v-if="!userStore.isMobile" >

                    <!-- notTV Bug -->
                    <div class="opacity-10">
                        <img :src="`/storage/images/logo_white_512.png`" class="absolute right-2 top-5 w-10 mr-4"></div>

                    <!-- On Screen Display (OSD)-->
                    <OsdTopRight :show="videoPlayerStore.showOSD" />

                    <!-- Video Player Controls-->
                    <VideoControlsTopRight :show="videoPlayerStore.showControls" />

                </div>
            </div>

            <div v-if="videoPlayerStore.fullPage && user">


        <!-- Mobile FullPage -->
                <div v-if="userStore.isMobile">

                    <!-- notTV Bug -->
                    <div v-show="! videoPlayerStore.showOSD" class="fixed h-screen top-4 left-5 opacity-10 z-50">
                        <img :src="`/storage/images/logo_white_512.png`" class="block h-9 w-auto shrink-0"></div>

                    <!-- On Screen Display (OSD) -->
                    <OsdFullPageMobile v-show="videoPlayerStore.showOSD"/>

                    <!-- Video Player Controls -->
                    <VideoControlsFullPageMobile v-show="videoPlayerStore.showControls" />

                    <!-- Over The Top (OTT) -->
                    <OttFullPageButtons v-show="videoPlayerStore.showOttButtons" />
                    <OttFullPageDisplayChannels />
                    <OttFullPageDisplayPlaylist />
                    <OttFullPageDisplayChatMobile :user="user"/>
                    <OttFullPageDisplayFilters />

                </div>


        <!-- Standard FullPage -->
                <div v-if="!userStore.isMobile">

                    <!-- notTV Bug -->
                    <div v-show="! videoPlayerStore.showOSD" class="fixed h-screen top-4 left-5 opacity-10 z-50">
                        <img :src="`/storage/images/logo_white_512.png`" class="block h-9 w-auto shrink-0"></div>

                    <!-- On Screen Display (OSD) -->
                    <OsdFullPageStandard v-show="videoPlayerStore.showOSD"/>

                    <!-- Video Player Controls -->
                    <VideoControlsFullPageStandard v-show="videoPlayerStore.showControls" />

                    <!-- Over The Top (OTT) -->
                    <OttFullPageButtons v-show="videoPlayerStore.showOSD" />
                    <OttFullPageDisplayChannels />
                    <OttFullPageDisplayPlaylist />
                    <OttFullPageDisplayChatStandard :user="user"/>
                    <OttFullPageDisplayFilters />

                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { onMounted, onUnmounted, onUpdated, ref, reactive, watch } from 'vue'
import { Inertia } from "@inertiajs/inertia"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useStreamStore } from "@/Stores/StreamStore"
import { useChatStore } from "@/Stores/ChatStore"
import { useUserStore } from "@/Stores/UserStore"

import OttFullPageButtons from "@/Components/VideoPlayer/OttButtons/OttFullPageButtons.vue"
import OttFullPageDisplayChannels from "@/Components/VideoPlayer/OttFullPageDisplay/Channels"
import OttFullPageDisplayPlaylist from "@/Components/VideoPlayer/OttFullPageDisplay/Playlist"
import OttFullPageDisplayChatStandard from "@/Components/VideoPlayer/OttFullPageDisplay/ChatStandard"
import OttFullPageDisplayChatMobile from "@/Components/VideoPlayer/OttFullPageDisplay/ChatMobile"
import OttFullPageDisplayFilters from "@/Components/VideoPlayer/OttFullPageDisplay/Filters"
import Login from "@/Components/Welcome/Login"
import VideoControlsFullPageStandard from "@/Components/VideoPlayer/VideoControls/VideoControlsFullPageStandard"
import VideoControlsFullPageMobile from "@/Components/VideoPlayer/VideoControls/VideoControlsFullPageMobile"
import VideoControlsTopRight from "@/Components/VideoPlayer/VideoControls/VideoControlsTopRight"
import OsdTopRight from "@/Components/VideoPlayer/Osd/OsdTopRight.vue"
import OsdFullPageStandard from "@/Components/VideoPlayer/Osd/OsdFullPageStandard.vue"
import OsdFullPageMobile from "@/Components/VideoPlayer/Osd/OsdFullPageMobile.vue"
import VideoPlayer from "@/Components/VideoPlayer/VideoJs"
import videojs from 'video.js'
import { useScreenOrientation } from '@vueuse/core'
import axios, {Axios} from "axios";

let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let chatStore = useChatStore()
let userStore = useUserStore()

videoPlayerStore.paused = false
chatStore.showChat = false
streamStore.showChannels = false
streamStore.showOSD = false

const isMobile = ref({
    mobile: userStore.isMobile,
})

let showLogin = ref(false)

let props = defineProps({
    src: String,
    user: Object,
    can: Object,
    videoSource: '',
    videoSourceType: '',
})

let videoOptions = ref()

function setVideoOptions() {
    videoOptions = {
        autoplay: true,
        playsinline: true,
        muted: true,
        controls: false,
        enableSourceset: true,
        sources: [
            {
                src:
                videoPlayerStore.videoSource,
                type: videoPlayerStore.videoSourceType
            }
        ]
    }

}
let videoJs = ref()
async function getFirstPlaySettings() {
    await axios.get('/api/app_settings')
        .then(response => {
            videoPlayerStore.videoSource = response.data[0].first_play_video_source
            videoPlayerStore.videoSourceType = response.data[0].first_play_video_source_type
            videoPlayerStore.videoName = response.data[0].first_play_video_name
            console.log('app settings retrieved.');

        })
        .catch(error => {
            console.log(error)
        })
    setVideoOptions()
    videoJs = videojs('main-player', videoOptions)
}



// let videoOptions = {
//     autoplay: true,
//     playsinline: true,
//     muted: true,
//     controls: false,
//     enableSourceset: true,
//     sources: [
//         {
//             src:
//             videoPlayerStore.videoSource,
//             type: videoPlayerStore.videoSourceType
//         }
//     ]
// }
getFirstPlaySettings()

const {
    isSupported,
    orientation,
    angle,
    lockOrientation,
    unlockOrientation,
} = useScreenOrientation()

onMounted(() => {
    // let videoJs = videojs('main-player', videoOptions)
    // if (userStore.isMobile) {
    //     if (orientation==='landscape-secondary' || orientation==='landscape-primary') {
    //         videoPlayerStore.hideOsdAndControlsAndNav()
    //     } else if (orientation==='portrait-secondary' || orientation==='portrait-primary') {
    //         videoPlayerStore.showOsdAndControlsAndNav()
    //     }
    // }

})
//
// onUnmounted(() => {
//     if (videoPlayer) {
//         videoPlayer.dispose()
//     }
// })


// tec21 (03/03/23): this doesn't seem to be working :-(
watch(orientation, (newOrientation) => {
    if (userStore.isMobile) {
        if (newOrientation==='landscape-primary' ) {
            videoPlayerStore.hideOsdAndControlsAndNav()
        } else if (newOrientation==='landscape-secondary') {
            videoPlayerStore.hideOsdAndControlsAndNav()
        } else if (newOrientation==='portrait-primary') {
            videoPlayerStore.showOsdAndControlsAndNav()
        } else if (newOrientation==='portrait-secondary') {
            videoPlayerStore.showOsdAndControlsAndNav()
        }
    }
})



function backToPage() {
    videoPlayerStore.makeVideoTopRight();
    chatStore.showChat = false;
    streamStore.showOSD = false;
}

let screenWidth = ref(screen.width)

function clickOnVideoAction() {

    if (!userStore.isMobile && !videoPlayerStore.currentPageIsStream) {
        videoPlayerStore.toggleOsdAndControls()
    }
    if (userStore.isMobile && !videoPlayerStore.currentPageIsStream) {
        Inertia.visit('/stream')
    }
    if(videoPlayerStore.currentPageIsStream) {
        videoPlayerStore.toggleOsdAndControlsAndNav()

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

let mouseActive = false

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
<!--import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore"-->
<!--import VideoPlayer from '@/Components/VideoPlayer/VideoJs'-->

<!--import { ref } from 'vue'-->
<!--console.log('check point A VideoPlayerMain')-->
<!--export default {-->
<!--    name: 'VideoPlayer',-->
<!--    components: {-->
<!--        VideoPlayer-->
<!--    },-->
<!--    data() {-->
<!--        const videoPlayerStore = useVideoPlayerStore()-->
<!--        videoPlayerStore.videoSource = "/storage/videos/BigBuckBunny.mp4"-->
<!--        videoPlayerStore.videoSourceType = "video/mp4"-->
<!--        const videoSource = videoPlayerStore.videoSource-->
<!--        const videoSourceType = videoPlayerStore.videoSourceType-->
<!--        return {-->
<!--            videoOptions: {-->
<!--                autoplay: true,-->
<!--                playsinline: true,-->
<!--                muted: true,-->
<!--                controls: false,-->
<!--                enableSourceset: true,-->
<!--                sources: [-->
<!--                    {-->
<!--                        src:-->
<!--                            videoSource,-->
<!--                        type: videoSourceType-->
<!--                    }-->
<!--                ]-->
<!--            }-->
<!--        };-->
<!--    },-->
<!--    methods: {-->
<!--        playVideo(){-->
<!--            this.play()-->
<!--        }-->
<!--    }-->
<!--};-->



<!--</script>-->

