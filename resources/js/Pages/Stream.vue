<template>
    <Head title="Stream" />


</template>


<script setup>
import { inject, onBeforeMount, onBeforeUnmount, onMounted, onUnmounted, ref } from "vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useUserStore } from "@/Stores/UserStore"
import { useStreamStore } from "@/Stores/StreamStore"
import { useChatStore } from "@/Stores/ChatStore"
import { useChannelStore } from "@/Stores/ChannelStore"
import { Inertia } from "@inertiajs/inertia";
import { usePage } from "@inertiajs/inertia-vue3"
import videojs from "video.js";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()
let streamStore = useStreamStore()
let chatStore = useChatStore()
let channelStore = useChannelStore()

const getUserData = inject('getUserData', null)
let urlPrev = usePage().props.value.urlPrev

onBeforeMount(() => {
    // getUserTimezone()
    videoPlayerStore.currentPageIsStream = true;
    videoPlayerStore.currentView = 'stream'
    userStore.currentPage = 'stream'
})

// const channel = [
//     {
//         id: 2,
//         name: 'Stream',
//         stream: 'thirdeyespies',
//         channel_source: null,
//         isLive: null
//     }
// ]

onMounted(async () => {
    // console.log(window.location.href)
    //
    // console.log(window.history)
    // userStore.prevUrl = window.history.length > 1 && window.history.state
    //     ? window.history.state.url : null;

    // async function changeChannel() {
    //     await channelStore.getChannels()
    //     await channelStore.disconnectViewerFromChannel()
    //     await channelStore.changeChannel(channelStore.channel_list[1])
    //     Inertia.reload()
    // }
    //
    // await changeChannel()

    if (!getUserData) {
        updateUserStore()
    }

    videoPlayerStore.makeVideoFullPage()
    if (userStore.isMobile) {
        videoPlayerStore.showOsd()
    } else {
        videoPlayerStore.showOsdAndControlsAndNav()
    }

    videoPlayerStore.loggedIn = true
    videoPlayerStore.ott = 0
    videoPlayerStore.osd = true
    videoPlayerStore.ottButtons = true
    videoPlayerStore.ottChannels = false
    videoPlayerStore.ottChat = false
    videoPlayerStore.ottPlaylist = false
    videoPlayerStore.ottFilters = false
    videoPlayerStore.fullPage = true

    // for testing purposes, channel 2 on my local machine is my test channel
    // need to add to the if statement, on firstPlay when the user loads the app
    // channel is undefined.... so don't run this function if the viewer loads the
    // app on the stream page or refreshes the page on the stream page.


    // if (videoPlayerStore.videoPlayerLoaded) {
    //     if (channelStore.currentChannelId !== 2 && urlPrev !== 'empty' && urlPrev !== 'stream') {
    //         await (async () => {
    //             // await channelStore.getChannels();
    //             await channelStore.disconnectViewerFromChannel();
    //             await channelStore.changeChannel(channel[0]);
    //             // Inertia.reload();
    //         })();
    //     }
    // }
})

onBeforeUnmount(() => {
    videoPlayerStore.controls = false
    videoPlayerStore.makeVideoTopRight()
    videoPlayerStore.currentPageIsStream = false
    videoPlayerStore.fullPage = false
})

onUnmounted(() => {
    // videoPlayerStore.ott = 0
    // videoPlayerStore.osd = true
    // videoPlayerStore.ottButtons = true
    // videoPlayerStore.ottChannels = false
    // videoPlayerStore.ottChat = false
    // videoPlayerStore.ottPlaylist = false
    // videoPlayerStore.ottFilters = false
})

let props = defineProps({
    getUserData: Boolean,
    video: Object,
    user: Object,
})

function updateUserStore() {
    axios.post('/getUserStoreData')
        .then(response => {
            userStore.id = response.data.id
            userStore.isAdmin = response.data.isAdmin
            userStore.isCreator = response.data.isCreator
            userStore.isNewsPerson = response.data.isNewsPerson
            userStore.isVip = response.data.isVip
            userStore.isSubscriber = response.data.isSubscriber
            userStore.hasAccount = response.data.hasAccount
            userStore.getUserDataCompleted = true
            userStore.timezone = userTimezone
            console.log('get user data on Stream')
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
    updateUserTimezone()
}

const userTimezone = ref('');

const getUserTimezone = () => {
    // Use the Intl object to get the user's timezone
    userTimezone.value = Intl.DateTimeFormat().resolvedOptions().timeZone;
};

const updateUserTimezone = async () => {
    try {
        const response = await axios.post('/users/update-timezone', {timezone: userTimezone.value});

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
}
</script>

