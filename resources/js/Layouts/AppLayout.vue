<template>
    <div class="fixed top-0 left-0 right-0 bottom-0 bg-gray-800 text-gray-200 vh-100 vw-100 overflow-hidden overscroll-y-none overscroll-x-none">

        <!-- Navbar for logged in user -->
        <div v-if="user">
            <div v-if="videoPlayerStore.showNav" class="fixed top-0 w-full nav-mask">
                <ResponsiveNavigationMenu/>
                <NavigationMenu /></div>
        </div>

        <!-- Login for Welcome Page (logged out) -->
        <Teleport to="body">
            <Login v-if="!user" :show="showLogin" @close="showLogin = false" />
        </Teleport>

        <!-- Video Player -->
        <VideoPlayerMain
            :user="user" />

        <!-- Page Content -->
        <div :class="layoutClass">
            <slot /></div>

    </div>
</template>

<script setup>
import {ref, computed, onBeforeMount, onBeforeUnmount, onUpdated, defineAsyncComponent, onMounted} from "vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useStreamStore } from "@/Stores/StreamStore"
import { useUserStore } from "@/Stores/UserStore"
import { useChatStore } from "@/Stores/ChatStore"
import { useShopStore } from "@/Stores/ShopStore"
import { useChannelStore } from "@/Stores/ChannelStore"

import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu.vue";
import NavigationMenu from "@/Components/Navigation/NavigationMenu.vue";
// const ResponsiveNavigationMenu = defineAsyncComponent( () =>
//     import('@/Components/Navigation/ResponsiveNavigationMenu'))
// const NavigationMenu = defineAsyncComponent( () =>
//     import('@/Components/Navigation/NavigationMenu'))
const Login = defineAsyncComponent( () =>
    import('@/Components/Welcome/Login'))
const VideoPlayerMain = defineAsyncComponent( () =>
    import('@/Components/VideoPlayer/VideoPlayerMain'))
// import VideoPlayerMain from "@/Components/VideoPlayer/VideoPlayerMain.vue";
import videojs from 'video.js'

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()
let streamStore = useStreamStore()
let chatStore = useChatStore()
let shopStore = useShopStore()
let channelStore = useChannelStore()

let isStreamPage = ref()
let showLogin = ref(false)
let videoJs = ref()

let props = defineProps({
    user: Object,
});

const pageHide = computed(() => ({
    'hidden lg:block': videoPlayerStore.ottClass === 'OttOpen' && userStore.isMobile
}))

const layoutClass = computed(() => ({
    layoutWelcome: !props.user,
    layoutLoggedIn: props.user,
    'hidden lg:block': videoPlayerStore.ottClass === 'OttOpen' && userStore.isMobile
}))

// userStore.checkIsMobile()
userStore.showNavDropdown = false

setPage()

onBeforeMount(async () => {
    getUser()
})

onMounted(async () => {
    await getFirstPlaySettings()
    videoJs = videojs(document.querySelector('.video-js'));
    videoJs.src({type: videoPlayerStore.videoSourceType, src: videoPlayerStore.videoSource});
})

// onUpdated(() => {
//     userStore.showNavDropdown = false
//     getUser()
// })

// onBeforeUnmount(() => {
//     videoPlayerStore.viewerCount = 0
//     disconnect();
// });

function setPage() {
    isStreamPage = videoPlayerStore.currentPage === "stream";
}

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
}

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

// function disconnect() {
//     window.Echo.leave("channel." + channelStore.currentChannelId);
//     console.log('CHANNEL DISCONNECTED');
// }
</script>
