<template>

    <div :class="videoPlayerStore.videoContainerClass">
        <div :class="videoPlayerStore.class">

<!--        Login for Welcome Page (logged out) -->
            <Teleport to="body">
                <Login v-if="!user" :show="showLogin" @close="showLogin = false" />
            </Teleport>


<!--        FIX THIS ON MOBILE DISPLAY (VIDEO NEEDS TO BE AT THE TOP, NOT THE MIDDLE). -->

<!--            <video-player :id="playerName" :options="videoOptions" v-touch="()=>videoPlayerStore.toggleOSD()" />-->
            <video-player :id="playerName" :options="videoOptions" v-touch="()=>clickOnVideoAction()" />


    <!-- TopRight Video -->
            <div v-if="! videoPlayerStore.fullPage && user" >

                <!-- notTV Bug -->
                <div class="opacity-10">
                    <img :src="`/storage/images/logo_white_512.png`" class="absolute right-2 top-5 w-10 mr-4"></div>

                 On Screen Display (OSD)
                <OsdTopRight :show="videoPlayerStore.showOSD" />

                 Video Player Controls
                <VideoControlsTopRight :show="videoPlayerStore.showOSD" />

            </div>


    <!-- FullPage Video -->
            <div v-if="videoPlayerStore.fullPage && user">

<!--        Mobile FullPage -->
                <div v-if="userStore.isMobile">

                    <!-- notTV Bug -->
                    <div v-show="! videoPlayerStore.showOSD" class="fixed h-screen top-4 h-16 left-5 opacity-10 z-50">
                        <img :src="`/storage/images/logo_white_512.png`" class="block h-9 w-auto shrink-0"></div>

                    <!-- Video Player Controls -->
                    <VideoControlsFullPageMobile v-show="videoPlayerStore.showOSD" />

                    <!-- On Screen Display (OSD) -->
                    <OsdFullPageMobile v-show="videoPlayerStore.showOSD" />

                    <!-- Over The Top (OTT) -->
                    <OttFullPageButtons v-show="videoPlayerStore.showOSD" />
                    <OttFullPageDisplayChannels />
                    <OttFullPageDisplayPlaylist />
                    <OttFullPageDisplayChatMobile :user="user"/>
                    <OttFullPageDisplayFilters />

                </div>


<!--        Standard FullPage -->
                <div v-if="!userStore.isMobile">

                    <!-- notTV Bug -->
                    <div v-show="! videoPlayerStore.showOSD" class="fixed h-screen top-4 h-16 left-5 opacity-10 z-50">
                        <img :src="`/storage/images/logo_white_512.png`" class="block h-9 w-auto shrink-0"></div>

                    <!-- Video Player Controls -->
                    <VideoControlsFullPageStandard v-show="videoPlayerStore.showOSD" />

                    <!-- On Screen Display (OSD) -->
                    <OsdFullPageStandard v-show="videoPlayerStore.showOSD" />

                    <!-- Over The Top (OTT) -->
                    <OttFullPageButtons v-show="videoPlayerStore.showOSD" />
                    <OttFullPageDisplayChannels />
                    <OttFullPageDisplayPlaylist />
                    <OttFullPageDisplayChatStandard :user="user" />
                    <OttFullPageDisplayFilters />

                </div>
            </div>


        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Inertia } from "@inertiajs/inertia"
// import { router } from '@inertiajs/inertia'
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useStreamStore } from "@/Stores/StreamStore"
import { useChatStore } from "@/Stores/ChatStore"
import { useUserStore } from "@/Stores/UserStore"

import OttFullPageButtons from "@/Components/VideoPlayer/OttFullPageButtons.vue"
import OttFullPageDisplayChannels from "@/Components/VideoPlayer/OttFullPageDisplayChannels"
import OttFullPageDisplayPlaylist from "@/Components/VideoPlayer/OttFullPageDisplayPlaylist"
import OttFullPageDisplayChatStandard from "@/Components/VideoPlayer/OttFullPageDisplayChatStandard"
import OttFullPageDisplayChatMobile from "@/Components/VideoPlayer/OttFullPageDisplayChatMobile"
import OttFullPageDisplayFilters from "@/Components/VideoPlayer/OttFullPageDisplayFilters"
import Login from "@/Components/Welcome/Login"
import VideoControlsFullPageStandard from "@/Components/VideoPlayer/VideoControlsFullPageStandard"
import VideoControlsFullPageMobile from "@/Components/VideoPlayer/VideoControlsFullPageMobile"
import VideoControlsTopRight from "@/Components/VideoPlayer/VideoControlsTopRight"
import OsdTopRight from "@/Components/VideoPlayer/OsdTopRight.vue"
import OsdFullPageStandard from "@/Components/VideoPlayer/OsdFullPageStandard.vue"
import OsdFullPageMobile from "@/Components/VideoPlayer/OsdFullPageMobile.vue"


let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let chatStore = useChatStore()
let userStore = useUserStore()

videoPlayerStore.paused = false
chatStore.showChat = false
streamStore.showChannels = false
streamStore.showOSD = false
videoPlayerStore.showControls = false

let showLogin = ref(false)

let playerName = 'main-player';

let props = defineProps({
    src: String,
    user: Object,
    can: Object,
})

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


<script>
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore"
import VideoPlayer from '@/Components/VideoPlayer/VideoJs'

import { ref } from 'vue'

export default {
    name: 'VideoPlayer',
    components: {
        VideoPlayer
    },
    data() {
        const videoPlayerStore = useVideoPlayerStore()
        const videoSource = videoPlayerStore.videoSource
        const videoSourceType = videoPlayerStore.videoSourceType
        return {
            videoOptions: {
                autoplay: true,
                muted: true,
                controls: false,
                enableSourceset: true,
                sources: [
                    {
                        src:
                            videoSource,
                        type: videoSourceType
                    }
                ]
            }
        };
    }
};



</script>

