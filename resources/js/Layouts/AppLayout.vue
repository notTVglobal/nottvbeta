<template>
    <div class="absolute top-0 left-0 right-0 bottom-0 bg-gray-800 text-gray-200 vh-100 vw-100 overflow-hidden overscroll-y-none overscroll-x-none">

    <!-- Navbar for logged in user -->
        <div v-if="user">

            <div v-show="videoPlayerStore.showNav" class="fixed top-0 w-full nav-mask">
                <ResponsiveNavigationMenu/>
                <NavigationMenu /></div>

        </div>


        <div class="w-full">

    <!-- Video Player -->
            <VideoPlayerMain
                 :key="videoPlayerStore.key"
                 :user="user" />

    <!-- OTT Buttons and Displays -->
            <div v-if="!videoPlayerStore.fullPage" >
                <OttTopRightButtons />
                <OttTopRightDisplayNowPlayingInfo :user="user"/>
                <OttTopRightDisplayPlaylist :user="user"/>
                <OttTopRightDisplayChannels :user="user"/>
                <OttTopRightDisplayChat :user="user"/>
                <OttTopRightDisplayFilters :user="user"/>

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
import {ref, watch, computed, onServerPrefetch, onBeforeMount, onBeforeUnmount, onUpdated, onMounted} from "vue";
import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/Navigation/NavigationMenu"
import VideoPlayerMain from "@/Components/VideoPlayer/VideoPlayerMain"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useStreamStore } from "@/Stores/StreamStore"
import { useUserStore } from "@/Stores/UserStore"
import { useChatStore } from "@/Stores/ChatStore"
import { useShopStore } from "@/Stores/ShopStore"
import { useChannelStore } from "@/Stores/ChannelStore"
import OttTopRightButtons from '@/Components/VideoPlayer/OttButtons/OttTopRightButtons'
import OttTopRightDisplayChannels from '@/Components/VideoPlayer/OttTopRightDisplay/Channels'
import OttTopRightDisplayChat from '@/Components/VideoPlayer/OttTopRightDisplay/Chat'
import OttTopRightDisplayFilters from '@/Components/VideoPlayer/OttTopRightDisplay/Filters'
import OttTopRightDisplayNowPlayingInfo from '@/Components/VideoPlayer/OttTopRightDisplay/NowPlayingInfo'
import OttTopRightDisplayPlaylist from '@/Components/VideoPlayer/OttTopRightDisplay/Playlist'

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
    user_id: null,
});

function getUser() {
    // create user.id from session.id
    // this will be replaced with a guestAuth.user.id
    // if (props.user === null) {userStore.id = props.user_id; userStore.oldLoggedOutId = props.user_id;}
    // else {
    //     userStore.id = props.user.id
    // }
    if (props.user) {
        userStore.id = props.user.id
        userStore.roleId = props.user.role_id
        userStore.userIsAdmin = props.user.isAdmin
    }
    userStore.isSubscriber()
    userStore.isCreator()
    userStore.isVip()
    userStore.isAdmin()
}


onBeforeMount(async () => {
    getUser()
    // videoPlayerStore.videoSource = "https://ia800300.us.archive.org/17/items/BigBuckBunny_124/Content/big_buck_bunny_720p_surround.mp4"
    // videoPlayerStore.videoSourceType = "application/x-mpegURL"
    // videoPlayerStore.videoName = null
    userStore.showNavDropdown = false

})

onMounted(() => {
    let channel = {
        'id': 1,
        'name': 'Stream',
        'isLive': true,
    }

    channelStore.changeChannel(channel)
})

onUpdated(() => {
    userStore.showNavDropdown = false
    if (userStore.id !== props.user_id && userStore.id === null) {
        // channelStore.disconnectLoggedOutUserFromChannel(userStore.oldLoggedOutId)
        userStore.oldLoggedOutId = null
    }
    getUser()
    if (userStore.id !== null && !channelStore.userAddedToChannels) {
        channelStore.addViewerToChannel()
    }

})

onBeforeUnmount( () => {
    // if sessions_id is removed and the user_logged_out_id remains
    // we'll need to purge the viewer from the ViewerCount.
    // this doesn't seem to get removed when I close the browser
    if (userStore.id !== props.user_id && userStore.id === null) {
        channelStore.disconnectLoggedOutUserFromChannel(userStore.oldLoggedOutId)
        userStore.oldLoggedOutId = null
    }
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
