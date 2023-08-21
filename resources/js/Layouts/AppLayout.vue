<template>
    <div class="absolute top-0 left-0 right-0 bottom-0 bg-gray-800 text-gray-200 vh-100 vw-100 overflow-hidden overscroll-y-none overscroll-x-none">


    <!-- Navbar for logged in user -->
        <div v-if="user">

            <!--        tec21: On a desktop computer we need the mouse enter and leave functions to allow the user to click the links properly
                    but, on a tablet it makes it so you need to tap the nav links twice. -->
            <!--        I think this is fixed now -->

            <div v-show="videoPlayerStore.showNav" class="fixed top-0 w-full nav-mask">
                <ResponsiveNavigationMenu/>
                <NavigationMenu /></div>

        </div>

    <!-- Chat -->


    <!-- Video Player -->
            <div class="w-full">

                <VideoPlayerMain class="vh-100 z-20"
                                 :key="videoPlayerStore.key"
                                 :user="user"
                />

<!-- tec21: 08/20/23 removed the following from VideoPlayerMain
        to fix the bug with mobile users focusing input and
        the OSD showing. It was messing up the input focus too. -->
<!--                @mouseenter="showOSD"-->
<!--                @mouseleave="hideOSD"-->
<!--                v-touch="()=>toggleOSD()"-->


                <!-- isMobile -->
<!--                <div v-if="userStore.isMobile">-->
                    <div v-if="!videoPlayerStore.fullPage" >
                        <OttTopRightButtons class="videoOTT fixed top-40 lg:top-72 w-full lg:w-96 right-0 z-30"/>
                        <OttTopRightDisplay :user="user"
                                            class="fixed top-44 lg:top-78 right-0 w-full lg:w-96 mt-4 lg:mt-2 overflow-y-none z-40"
                                            :class="ottDisplayShow"/>
<!--                        <div class="ottTopRightDisplayBG fixed top-44 lg:top-78 right-0 w-full h-full lg:w-96 mt-4 lg:mt-2 z-20 bg-green-600"></div>-->
                    </div>

<!--                </div>-->

<!--                 !isMobile-->
<!--                <div v-if="!userStore.isMobile">-->
<!--                    <div v-if="!videoPlayerStore.fullPage" class="fixed top-72 w-full lg:w-96 right-0 z-30">-->
<!--                        <OttTopRightButtons class="videoOTT"/></div>-->

<!--                    <OttTopRightDisplay :user="user"-->
<!--                                        class="fixed top-78 right-0 w-full lg:w-96 mt-2 overflow-y-none"-->
<!--                                        :class="ottDisplayShow"/>-->
<!--                </div>-->
            </div>

        <!-- Page Content -->
            <div>
                <!-- Logged out view (Welcome) -->
                <main v-if="! user"
                      class="fixed top-0 h-screen w-screen overflow-y-scroll z-50">

                    <slot /></main>

                <!-- Logged in view -->
<!--                <main v-if="user"-->
<!--                      class="absolute pb-24 lg:top-16-->
<!--                             w-fit lg:w-[calc(100vw-24rem)]-->
<!--                             min-h-[calc(100vh-19rem)] lg:h-[calc(100vh-4rem)]-->
<!--                             z-10 overflow-y-scroll overscroll-x-none"-->
<!--                      :class="[{'top-48 h-full':userStore.isMobile}, pageHide]">-->
                    <main v-if="user"
                          class="absolute pt-40 pb-20 lg:pt-8 lg:top-16
                             w-fit lg:w-[calc(100vw-24rem)]
                             h-full
                             z-10 overflow-y-scroll overscroll-x-none"
                          :class="[{'':userStore.isMobile}, pageHide]">

                    <slot /></main>
            </div>


    </div>
</template>

<script setup>
import {computed, ref} from "vue";
import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/Navigation/NavigationMenu"
import VideoPlayerMain from "@/Components/VideoPlayer/VideoPlayerMain"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useStreamStore } from "@/Stores/StreamStore"
import { useUserStore } from "@/Stores/UserStore"
import { useChatStore } from "@/Stores/ChatStore";
import OttTopRightDisplay from '@/Components/VideoPlayer/OttTopRightDisplay'
import OttTopRightButtons from '@/Components/VideoPlayer/OttTopRightButtons'

let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let userStore = useUserStore()
let chatStore = useChatStore()

videoPlayerStore.videoSource = "https://mist2.not.tv/hls/mist1pull1/index.m3u8"
videoPlayerStore.videoSourceType = "application/x-mpegURL"
videoPlayerStore.videoName = "notTV One"
streamStore.currentChannel = "Stream"
videoPlayerStore.currentChannelName = "one"
userStore.showNavDropdown = false

let isStreamPage = null

function setPage() {
    if (videoPlayerStore.currentPage === "stream") {
        isStreamPage = true;
    } else
        isStreamPage = false;
}

setPage()

userStore.checkIsMobile()

let props = defineProps({
    user: Object,
});

const ottDisplayShow = computed(() => ({
    'hidden': videoPlayerStore.ottClass !== 'OttOpen'
}))

const pageHide = computed(() => ({
    'hidden lg:block': videoPlayerStore.ottClass === 'OttOpen' && userStore.isMobile
}))

function hideOSD() {
    videoPlayerStore.showOSD = false;
    if (videoPlayerStore.fullPage) {
        videoPlayerStore.showNav = false;
    }

}

function showOSD() {
    videoPlayerStore.showOSD = true;
    if (videoPlayerStore.fullPage) {
        videoPlayerStore.showNav = true;
    }
}

function toggleOSD() {
    if (!videoPlayerStore.fullPage) {
        videoPlayerStore.toggleOSD()
    }
}


</script>
