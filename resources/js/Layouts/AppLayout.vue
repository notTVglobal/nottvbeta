<template>
    <div class="fixed top-0 left-0 right-0 bottom-0 bg-gray-800 text-gray-200 vh-100 vw-100 overflow-hidden overscroll-y-none overscroll-x-none">

        <!-- Navbar for logged in user -->
        <ResponsiveNavigationMenu v-if="user" :key="userStore.currentPage"/>
        <NavigationMenu v-if="user" :key="userStore.currentPage"/>
        <NotificationModal v-if="user"/>

        <!-- Login for Welcome Page (logged out) -->
        <Teleport to="body">
            <Login v-if="!user" :show="showLogin" @close="showLogin = false" />
        </Teleport>

        <!-- Page Content -->
        <div v-show="!userStore.hidePage" :class="layoutClass">
                <slot /></div>

        <!-- Video Player -->
        <VideoPlayerMain
            :user="user" />

        <DialogNotification />

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
import ResponsiveNavigationMenu from '@/Components/Navigation/ResponsiveNavigationMenu'
import NavigationMenu from '@/Components/Navigation/NavigationMenu'
import Messages from "@/Components/VideoPlayer/Chat/VideoOTTChatMessages.vue";
import Message from "@/Components/Modals/Messages.vue";
import NotificationModal from "@/Components/Notifications/NotificationModal.vue";
import DialogNotification from "@/Components/Modals/DialogNotification.vue";
import { format } from "date-fns";
import { Inertia } from "@inertiajs/inertia";

// const ResponsiveNavigationMenu = defineAsyncComponent( () =>
//     import('@/Components/Navigation/ResponsiveNavigationMenu'))
// const NavigationMenu = defineAsyncComponent( () =>
//     import('@/Components/Navigation/NavigationMenu'))
const Login = defineAsyncComponent( () =>
    import('@/Components/Welcome/Login'))
// const VideoPlayerMain = defineAsyncComponent( () =>
//     import('@/Components/VideoPlayer/VideoPlayerMain'))
import VideoPlayerMain from "@/Components/VideoPlayer/VideoPlayerMain.vue"

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()
let streamStore = useStreamStore()
let chatStore = useChatStore()
let shopStore = useShopStore()
let channelStore = useChannelStore()

let isStreamPage = ref()
let showLogin = ref(false)
let reloadNav = 0

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
    getUserTimezone()
    if(props.user && !userStore.getUserDataCompleted) {
        updateUserStore()
        provide(/* key */ 'getUserData', /* value */ true)
    } else
        provide(/* key */ 'getUserData', /* value */ false)
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
            userStore.id = response.data.id
            userStore.isAdmin = response.data.isAdmin
            userStore.isCreator = response.data.isCreator
            userStore.isNewsPerson = response.data.isNewsPerson
            userStore.isVip = response.data.isVip
            userStore.isSubscriber = response.data.isSubscriber
            userStore.hasAccount = response.data.hasAccount
            userStore.getUserDataCompleted = true
            console.log('get user data on AppLayout')
            userStore.subscribeToUserNotifications(response.data.id)
            reloadNav++
            if (userStore.isCreator) {
                userStore.prevUrl = '/dashboard'
            } else {
                userStore.prevUrl = '/stream'
            }

        })
        .catch(error => {
            console.log(error);
        })
    // save user Timezone
    await updateUserTimezone()
    userStore.timezone = userTimezone

}

const userTimezone = ref('');

const getUserTimezone = () => {
    // Use the Intl object to get the user's timezone
    userTimezone.value = Intl.DateTimeFormat().resolvedOptions().timeZone;
};

const updateUserTimezone = async () => {
    try {
        const response = await axios.post('/users/update-timezone', { timezone: userTimezone.value });

        // Handle success response as needed
        console.log(response.data.message);
    } catch (error) {
        // Handle error response or network error
        console.error(error);

        if (error.response) {
            // Handle specific error responses if needed
            console.error(error.response.data);
        }
    }
};


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

function clickOnVideoAction() {

    // if (!userStore.isMobile && !videoPlayerStore.currentPageIsStream) {
    //     videoPlayerStore.toggleOsdAndControls()
    // }
    if (!videoPlayerStore.currentPageIsStream) {
        Inertia.visit('/stream')
    }
    if(videoPlayerStore.currentPageIsStream) {
        // let videoPlayer = videojs('main-player');
        videoPlayerStore.togglePlay()
        // videoPlayerStore.toggleOsdAndControls()
    }
    // if (videoPlayerStore.currentPageIsStream === true) {
    //     // if (userStore.isMobile && orientation.value === 'landscape-primary') {
    //     //         videoPlayerStore.toggleOsdAndControlsAndNav()
    //     // } else if (!userStore.isMobile) {
    //     //     videoPlayerStore.toggleOsdAndControls()
    //     // }
    //     // videoPlayerStore.toggleOsdAndControlsAndNav()
    // } else {
    //     Inertia.visit('/stream')
    // }
    // videoPlayerStore.ottClass = 'OttClose'
    // videoPlayerStore.ott = 0
    // if(userStore.isMobile) {
    //
    // } else {
    //     // videoPlayerStore.toggleOsdAndControls()
    // }
    // }
}
</script>
