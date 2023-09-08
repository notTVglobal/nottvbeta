z<template>
    <div class="absolute top-0 left-0 right-0 bottom-0 bg-gray-800 text-gray-200 vh-100 vw-100 overflow-hidden overscroll-y-none overscroll-x-none">

        <!-- Navbar for logged in user -->
        <div v-if="user">
            <div v-show="videoPlayerStore.showNav" class="fixed top-0 w-full nav-mask">
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
import { ref, computed, onBeforeMount, onBeforeUnmount, onUpdated, defineAsyncComponent } from "vue"
import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/Navigation/NavigationMenu"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useStreamStore } from "@/Stores/StreamStore"
import { useUserStore } from "@/Stores/UserStore"
import { useChatStore } from "@/Stores/ChatStore"
import { useShopStore } from "@/Stores/ShopStore"
import { useChannelStore } from "@/Stores/ChannelStore"
import VideoPlayerMain from "@/Components/VideoPlayer/VideoPlayerMain"
import Login from "@/Components/Welcome/Login"

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()
let streamStore = useStreamStore()
let chatStore = useChatStore()
let shopStore = useShopStore()
let channelStore = useChannelStore()

let isStreamPage = ref()
let showLogin = ref(false)

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

const layoutClass = computed(() => ({
    layoutWelcome: !props.user,
    layoutLoggedIn: props.user,
    'hidden lg:block': videoPlayerStore.ottClass === 'OttOpen' && userStore.isMobile
}))

onBeforeUnmount(() => {
    videoPlayerStore.viewerCount = 0
    disconnect();
});


</script>
