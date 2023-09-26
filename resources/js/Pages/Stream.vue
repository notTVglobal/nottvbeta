<template>
    <Head title="Stream" />


</template>


<script setup>
import {inject, onBeforeMount, onMounted, onUnmounted, ref} from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore.js"
import { useStreamStore } from "@/Stores/StreamStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()
let streamStore = useStreamStore()
let chatStore = useChatStore()

const getUserData = inject('getUserData', null)

onBeforeMount( () => {
    getUserTimezone()
    videoPlayerStore.currentPageIsStream = true;
    videoPlayerStore.currentView = 'stream'
    userStore.currentPage = 'stream'
})

onMounted(() => {
    if (!getUserData) {
        updateUserStore()

    }
    videoPlayerStore.makeVideoFullPage()
    videoPlayerStore.showOsdAndControlsAndNav()
    videoPlayerStore.loggedIn = true
    videoPlayerStore.ott = 0
    videoPlayerStore.osd = true
    videoPlayerStore.ottButtons = true
    videoPlayerStore.ottChannels = false
    videoPlayerStore.ottChat = false
    videoPlayerStore.ottPlaylist = false
    videoPlayerStore.ottFilters = false
})

onUnmounted(() => {
    videoPlayerStore.currentPageIsStream = false;
    videoPlayerStore.ott = 0
    videoPlayerStore.osd = true
    videoPlayerStore.ottButtons = true
    videoPlayerStore.ottChannels = false
    videoPlayerStore.ottChat = false
    videoPlayerStore.ottPlaylist = false
    videoPlayerStore.ottFilters = false
})

let props = defineProps ({
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

</script>

