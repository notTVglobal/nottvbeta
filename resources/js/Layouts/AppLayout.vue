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
                                            class="fixed top-44 lg:top-78 right-0 w-full lg:w-96 mt-4 overflow-y-none z-40"
                                            :class="{'lg:mt-3':userStore.isMobile, 'lg:mt-2':!userStore.isMobile, ottDisplayShow}"/>
                        <div v-if="!userStore.isMobile && videoPlayerStore.ott" class="ottTopRightDisplayBG fixed top-44 lg:top-78 right-0 w-full h-full lg:w-96 mt-4 lg:mt-2 z-20 bg-gray-900"></div>
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
                    <div v-if="user"
                          class="fixed top-48 lg:top-16 lg:mt-0 pb-72 lg:pb-16
                             w-fit lg:w-[calc(100vw-24rem)]
                             h-full
                             z-10 overflow-y-scroll overscroll-x-none"
                          :class="[{'':userStore.isMobile}, pageHide]">

                    <slot /></div>
            </div>


    </div>
</template>

<script setup>
import {computed, onBeforeMount, onBeforeUnmount, onUpdated} from "vue";
import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/Navigation/NavigationMenu"
import VideoPlayerMain from "@/Components/VideoPlayer/VideoPlayerMain"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useStreamStore } from "@/Stores/StreamStore"
import { useUserStore } from "@/Stores/UserStore"
import { useChatStore } from "@/Stores/ChatStore"
import { useShopStore } from "@/Stores/ShopStore"
import OttTopRightDisplay from '@/Components/VideoPlayer/OttTopRightDisplay'
import OttTopRightButtons from '@/Components/VideoPlayer/OttTopRightButtons'

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()
let streamStore = useStreamStore()
let chatStore = useChatStore()
let shopStore = useShopStore()

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
    user_id: '',
    logged_out_id: ''
});

const oldLoggedOutId = props.logged_out_id

function getUser() {
    if (props.user === null) {userStore.id = props.user_id; userStore.oldLoggedOutId = props.user_id;}
    else {
        userStore.id = props.user.id
    }
}

onBeforeMount(() => {
    getUser()
    videoPlayerStore.videoSource = "https://archive.org/download/BigBuckBunny_124/Content/big_buck_bunny_720p_surround.mp4"
    videoPlayerStore.videoSourceType = "application/x-mpegURL"
    videoPlayerStore.videoName = "Big Buck Bunny"
    streamStore.currentChannel = "Video On Demand"
    videoPlayerStore.currentChannelName = "VOD"
    videoPlayerStore.currentChannelId = 0
    userStore.showNavDropdown = false
    videoPlayerStore.addViewerToChannel()
})

onUpdated(() => {
    if (userStore.id !== props.user_id && userStore.id === null) {
        videoPlayerStore.disconnectLoggedOutUserFromChannel(oldLoggedOutId)
        userStore.oldLoggedOutId = null
    }
    getUser()
})

onBeforeUnmount(() => {
    // if sessions_id is removed and the user_logged_out_id remains
    // we'll need to purge the viewer from the ViewerCount.
    // this doesn't seem to get removed when I close the browser
    if (userStore.id !== props.user_id && userStore.id === null) {
        videoPlayerStore.disconnectLoggedOutUserFromChannel(oldLoggedOutId)
        userStore.oldLoggedOutId = null
    }
    videoPlayerStore.disconnectLoggedOutUserFromChannel(oldLoggedOutId)
})

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

function disconnect() {
    window.Echo.leave("channel." + videoPlayerStore.currentChannelId);
    console.log('CHANNEL DISCONNECTED');
}

onBeforeUnmount(() => {
    videoPlayerStore.viewerCount = null
    disconnect();
});


</script>
