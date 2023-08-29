<template>
    <div class="absolute top-0 left-0 right-0 bottom-0 bg-gray-800 text-gray-200 vh-100 vw-100 overflow-hidden overscroll-y-none overscroll-x-none">

    <!-- Navbar for logged in user -->
        <div v-if="user">

            <div v-show="videoPlayerStore.showNav" class="fixed top-0 w-full nav-mask">
                <ResponsiveNavigationMenu/>
                <NavigationMenu /></div>

        </div>

    <!-- Video Player -->
            <div class="w-full">

                <VideoPlayerMain
                                 :key="videoPlayerStore.key"
                                 :user="user"
                />

                    <div v-if="!videoPlayerStore.fullPage" >
                        <OttTopRightButtons />
                        <OttTopRightDisplay :user="user"/>
                    </div>

            </div>

        <!-- Page Content -->
            <div>
                <!-- Logged out view (Welcome) -->
                <main v-if="! user"
                      class="fixed top-0 h-screen w-screen overflow-y-scroll z-50">

                    <slot /></main>

                <!-- Logged in view -->
                    <div v-if="user"
                          class="fixed top-48 lg:top-16 lg:mt-0 pb-72 lg:pb-16
                             w-full lg:w-[calc(100vw-24rem)]
                             h-full
                             z-10 overflow-y-scroll overscroll-x-none"
                          :class="[{'':userStore.isMobile}, pageHide]">

                    <slot /></div>
            </div>

    </div>
</template>

<script setup>
import {computed, onServerPrefetch, onBeforeMount, onBeforeUnmount, onUpdated, onMounted} from "vue";
import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/Navigation/NavigationMenu"
import VideoPlayerMain from "@/Components/VideoPlayer/VideoPlayerMain"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useStreamStore } from "@/Stores/StreamStore"
import { useUserStore } from "@/Stores/UserStore"
import { useChatStore } from "@/Stores/ChatStore"
import { useShopStore } from "@/Stores/ShopStore"
import { useChannelStore } from "@/Stores/ChannelStore"
import OttTopRightDisplay from '@/Components/VideoPlayer/OttTopRightDisplay'
import OttTopRightButtons from '@/Components/VideoPlayer/OttTopRightButtons'

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()
let streamStore = useStreamStore()
let chatStore = useChatStore()
let shopStore = useShopStore()
let channelStore = useChannelStore()

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

console.log('TEST POINT 1')
window.Echo.channel('viewerCount.' + channelStore.currentChannelId)
    .listen('ViewerJoinChannel', (e) => {
        console.log('test count up')
        channelStore.viewerCount = channelStore.viewerCount + 1
        // channelStore.viewerIncrement()
    })
    .listen('ViewerLeaveChannel', (e) => {
        console.log('test count down')
        channelStore.viewerCount = channelStore.viewerCount - 1
        // channelStore.viewerDecrement()
    })

onBeforeMount(() => {
    getUser()
    videoPlayerStore.videoSource = "https://archive.org/download/BigBuckBunny_124/Content/big_buck_bunny_720p_surround.mp4"
    videoPlayerStore.videoSourceType = "application/x-mpegURL"
    channelStore.currentVideoName = "Big Buck Bunny"
    channelStore.currentChannelName = null
    channelStore.currentChannelId = null
    userStore.showNavDropdown = false

    // Echo.channel('viewerCount')
    //     .listen('ViewerCountIncrement', (event) => {
    //         if (event.data.channel_id === videoPlayerStore.currentChannelId) {
    //             videoPlayerStore.viewerCount = videoPlayerStore.viewerCount + 1;}
    //     }).listen('ViewerCountDecrement', (event) => {
    //     if (event.data.channel_id === videoPlayerStore.currentChannelId) {
    //         videoPlayerStore.viewerCount = videoPlayerStore.viewerCount - 1;}
    // })

    channelStore.addViewerToChannel()
})

onUpdated(() => {
    if (userStore.id !== props.user_id && userStore.id === null) {
        channelStore.disconnectLoggedOutUserFromChannel(oldLoggedOutId)
        userStore.oldLoggedOutId = null
    }
    getUser()
})

onBeforeUnmount(() => {
    // if sessions_id is removed and the user_logged_out_id remains
    // we'll need to purge the viewer from the ViewerCount.
    // this doesn't seem to get removed when I close the browser
    if (userStore.id !== props.user_id && userStore.id === null) {
        channelStore.disconnectLoggedOutUserFromChannel(oldLoggedOutId)
        userStore.oldLoggedOutId = null
    }
    channelStore.disconnectLoggedOutUserFromChannel(oldLoggedOutId)
})

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
    window.Echo.leave("channel." + channelStore.currentChannelId);
    console.log('CHANNEL DISCONNECTED');
}

onBeforeUnmount(() => {
    videoPlayerStore.viewerCount = null
    disconnect();
});


</script>
