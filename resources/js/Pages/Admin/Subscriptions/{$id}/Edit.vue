<template>
    <div id="topDiv">
        <div>
            {{subscription}}
        </div>
    </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { onMounted } from "vue"
import {useUserStore} from "@/Stores/UserStore";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

let props = defineProps({
    subscription: Object,
})

userStore.currentPage = `Admin/Subscription/${props.subscription.id}/Edit`

onMounted(() => {
    videoPlayerStore.makeVideoTopRight()
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()

})


</script>
