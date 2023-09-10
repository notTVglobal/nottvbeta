<template>
    <Head title="Stream" />


</template>


<script setup>
import { inject, onBeforeMount, onMounted, onUnmounted } from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore.js"
import { useStreamStore } from "@/Stores/StreamStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()
let streamStore = useStreamStore()
let chatStore = useChatStore()

const getUserData = inject('getUserData')

onBeforeMount( () => {
    videoPlayerStore.currentPageIsStream = true;
    videoPlayerStore.currentView = 'stream'
    videoPlayerStore.currentPage = 'stream'
})

onMounted(async() => {
    if (!getUserData) {
        await updateUserStore()
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
            userStore.isAdmin = response.data.isAdmin
            userStore.isCreator = response.data.isCreator
            userStore.isNewsPerson = response.data.isNewsPerson
            userStore.isVip = response.data.isVip
            userStore.isSubscriber = response.data.isSubscriber
            userStore.getUserDataCompleted = true
            // console.log('get user data on Stream')
        })
        .catch(error => {
            console.log(error);
        })
}

</script>

