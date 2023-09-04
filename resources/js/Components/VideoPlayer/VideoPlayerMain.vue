<template>

    <div :class="videoPlayerStore.videoContainerClass">
        <div :class="videoPlayerStore.class">

    <!-- Login for Welcome Page (logged out) -->
            <Teleport to="body">
                <Login v-if="!user" :show="showLogin" @close="showLogin = false" />
            </Teleport>

            <!-- Video Player -->
<!--            <video-player :options="videoOptions"/>-->
            <video id="main-player" class="video-js vjs-big-play-centered vjs-fill"></video>
<!--                <video-player :id="playerName" :options="videoOptions"/>-->

<!--            <video ref="videoPlayer"-->
<!--                   id="main-player"-->
<!--                   :class="videoPlayerStore.class"-->
<!--                   class="video-js vjs-big-play-centered vjs-fill"-->
<!--                   v-touch="() => {clickOnVideoAction()}"-->
<!--            />-->
<!--&lt;!&ndash;                <source :src="videoPlayerStore.videoSource" :type="videoPlayerStore.videoSourceType">&ndash;&gt;-->
<!--            </video>-->
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
import {onBeforeMount, onMounted, onUnmounted, ref} from 'vue'
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

console.log('check point 1 VideoPlayerMain')

let props = defineProps({
    src: String,
    user: Object,
    can: Object,
    videoSource: '',
    videoSourceType: '',
    // this prop 'main-player' is not getting
    // loaded before the VideoPlayer is mounted.
    // the player name will determine if it's
    // the main-player or the aux-player.
    // playerName: 'main-player'
})

onBeforeMount(() => {
    console.log('check point 2 VideoPlayerMain')
})

videoPlayerStore.videoSource = "/storage/videos/BigBuckBunny.mp4"
videoPlayerStore.videoSourceType = "video/mp4"
// let videoPlayer = ref(null)
let videoOptions = {
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
//
onMounted(() => {

    console.log('check point 3 VideoPlayerMain')
    let videoJs = videojs('main-player', videoOptions)

})
//
// onUnmounted(() => {
//     if (videoPlayer) {
//         videoPlayer.dispose()
//     }
// })



function backToPage() {
    videoPlayerStore.makeVideoTopRight();
    chatStore.showChat = false;
    streamStore.showOSD = false;
}

function clickOnVideoAction() {
    if (videoPlayerStore.currentPageIsStream === true) {
        videoPlayerStore.toggleOSD()
    } else {
        Inertia.visit('/stream')
    }
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

