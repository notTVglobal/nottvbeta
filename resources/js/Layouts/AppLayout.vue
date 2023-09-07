<template>
    <div class="absolute top-0 left-0 right-0 bottom-0 bg-gray-800 text-gray-200 vh-100 vw-100 overflow-hidden overscroll-y-none overscroll-x-none">

    <!-- Navbar for logged in user -->
        <div v-if="user">

            <div v-show="videoPlayerStore.showNav" class="fixed top-0 w-full nav-mask">
                <ResponsiveNavigationMenu/>
                <NavigationMenu /></div>

        </div>

        <!-- Video Player -->
        <VideoPlayerMain
            :user="user" />

        <div class="w-full">

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



    <!-- Logged in view -->
            <div v-if="user"
                  class="fixed top-48 lg:top-16 lg:mt-0 pb-72 lg:pb-16
                     w-full lg:w-[calc(100vw-24rem)]
                     h-full
                     z-10 overflow-y-scroll overscroll-x-none"
                  :class="pageHide">

            <slot /></div>

    <!-- Logged out view (Welcome) -->
            <div v-if="userStore.currentPage='welcome'"
                 class="fixed h-screen w-full top-0 overflow-y-scroll z-50">

                <slot /></div>


    </div>
</template>

<script setup>
import { ref, computed, onBeforeMount, onBeforeUnmount, onUpdated } from "vue"
import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/Navigation/NavigationMenu"
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
import VideoPlayerMain from "@/Components/VideoPlayer/VideoPlayerMain"
// const VideoPlayerMain = defineAsyncComponent(() =>
//     import('@/Components/VideoPlayer/VideoPlayerMain')
// )

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()
let streamStore = useStreamStore()
let chatStore = useChatStore()
let shopStore = useShopStore()
let channelStore = useChannelStore()

let isStreamPage = ref()

function setPage() {
    isStreamPage = videoPlayerStore.currentPage === "stream";
}

setPage()

userStore.checkIsMobile()

let props = defineProps({
    user: Object,
});

function getUser() {
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

userStore.showNavDropdown = false
onBeforeMount(async () => {
    getUser()
})

onUpdated(() => {
    userStore.showNavDropdown = false
    getUser()
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

function disconnect() {
    window.Echo.leave("channel." + channelStore.currentChannelId);
    console.log('CHANNEL DISCONNECTED');
}

onBeforeUnmount(() => {
    videoPlayerStore.viewerCount = 0
    disconnect();
});


</script>
