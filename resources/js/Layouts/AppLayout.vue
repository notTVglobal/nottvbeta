<template>
    <div class="fixed top-0 left-0 right-0 bottom-0 bg-gray-800 text-gray-200 vh-100 vw-100 overflow-hidden overscroll-y-none overscroll-x-none">

        <!-- Navbar for logged in user -->
        <ResponsiveNavigationMenu v-if="user" />
        <NavigationMenu v-if="user" />

        <!-- Login for Welcome Page (logged out) -->
        <Teleport to="body">
            <Login v-if="!user" :show="showLogin" @close="showLogin = false" />
        </Teleport>

        <!-- Page Content -->
        <div :class="layoutClass">
            <slot /></div>

        <!-- Video Player -->
        <VideoPlayerMain
            :user="user" />

    </div>
</template>

<script setup>
import {ref, provide, computed, onBeforeMount, onBeforeUnmount, defineAsyncComponent, onMounted} from "vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useStreamStore } from "@/Stores/StreamStore"
import { useUserStore } from "@/Stores/UserStore"
import { useChatStore } from "@/Stores/ChatStore"
import { useShopStore } from "@/Stores/ShopStore"
import { useChannelStore } from "@/Stores/ChannelStore"

const ResponsiveNavigationMenu = defineAsyncComponent( () =>
    import('@/Components/Navigation/ResponsiveNavigationMenu'))
const NavigationMenu = defineAsyncComponent( () =>
    import('@/Components/Navigation/NavigationMenu'))
const Login = defineAsyncComponent( () =>
    import('@/Components/Welcome/Login'))
const VideoPlayerMain = defineAsyncComponent( () =>
    import('@/Components/VideoPlayer/VideoPlayerMain'))

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()
let streamStore = useStreamStore()
let chatStore = useChatStore()
let shopStore = useShopStore()
let channelStore = useChannelStore()

let isStreamPage = ref()
let showLogin = ref(false)

let props = defineProps({
    user: Object,
});

const pageHide = computed(() => ({
    'hidden lg:block': videoPlayerStore.ott && userStore.isMobile
}))

const layoutClass = computed(() => ({
    layoutWelcome: !props.user,
    layoutLoggedIn: props.user,
    'hidden lg:block': videoPlayerStore.ott && userStore.isMobile
}))

userStore.checkIsMobile()
userStore.showNavDropdown = false

setPage()

onBeforeMount( () => {
    if(props.user) {
        updateUserStore()
        provide(/* key */ 'getUserData', /* value */ true)
    }
})

onMounted( () => {
    console.log('we are here.')
})

onBeforeUnmount(() => {
    videoPlayerStore.viewerCount = 0
    disconnect();
});

function setPage() {
    isStreamPage = videoPlayerStore.currentPage === "stream";
}

async function updateUserStore() {
    await axios.post('/getUserStoreData')
        .then(response => {
            userStore.isAdmin = response.data.isAdmin
            userStore.isCreator = response.data.isCreator
            userStore.isNewsPerson = response.data.isNewsPerson
            userStore.isVip = response.data.isVip
            userStore.isSubscriber = response.data.isSubscriber
            userStore.hasAccount = response.data.hasAccount
            userStore.getUserDataCompleted = true
            console.log('get user data on AppLayout')
        })
        .catch(error => {
            console.log(error);
        })
}

function hideOSD() {
    videoPlayerStore.osd = false;
    if (videoPlayerStore.fullPage) {
        userStore.showNavDropdown = false;
    }
}

function showOSD() {
    videoPlayerStore.osd = true;
    if (videoPlayerStore.fullPage) {
        userStore.showNavDropdown = true;
    }
}

function disconnect() {
    window.Echo.leave("channel." + channelStore.currentChannelId);
    console.log('CHANNEL DISCONNECTED');
}
</script>
