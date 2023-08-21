<template>

    <div id="topDiv" class="divZ space-x-4">
        <button class="bg-blue-800 text-white" @click="videoPlayerStore.makeVideoTopRight()">VIDEO TOP RIGHT</button>
        <button class="bg-blue-800 text-white" @click="videoPlayerStore.makeVideoFullPage()">VIDEO FULL PAGE</button>

    </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { onBeforeMount, onMounted } from "vue"
import {useUserStore} from "@/Stores/UserStore";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'testing'

// onBeforeMount(() => {
//     userStore.scrollToTopCounter = 0;
// })

onMounted(() => {
    videoPlayerStore.makeVideoFullPage()
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()
    // if (userStore.scrollToTopCounter === 0 ) {
    //
    //     userStore.scrollToTopCounter ++;
    // }
})

// function makeVideoFullPage() {
//     videoPlayerStore.class = 'fullPageVideoClass'
//     videoPlayerStore.videoContainerClass = 'fullPageVideoContainer'
// }
//
// function makeVideoTopRight() {
//     videoPlayerStore.class = 'topRightVideoClass'
//     videoPlayerStore.videoContainerClass = 'topRightVideoContainer'
// }

</script>

<style scoped>
.divZ {
    position: absolute;
    z-index: 900;
    top: 20px;

}

</style>
