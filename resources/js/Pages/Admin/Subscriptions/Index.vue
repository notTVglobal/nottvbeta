<template>
    <div id="topDiv">
        <div>
            {{subscriptions}}
        </div>
    </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { onMounted } from "vue"
import {useUserStore} from "@/Stores/UserStore";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

userStore.currentPage = 'Admin/Subscriptions'

defineProps({
    subscriptions: Object,
})

onMounted(() => {
    videoPlayerStore.makeVideoTopRight()
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()

})


</script>
