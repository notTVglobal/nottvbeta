<template>
    <Head title="Testing" />
    <div>
        <video
            id="vid1"
            class="video-js vjs-default-skin"
            controls
            autoplay
            width="320" height="132"
            data-setup='{ "techOrder": ["youtube"], "sources": [{ "type": "video/youtube", "src": "https://www.youtube.com/watch?v=xjS6SftYQaQ"}], "youtube": { "ytControls": 2 } }'
        >
        </video>
    </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import {onBeforeMount, onBeforeUnmount, onMounted} from "vue"
import {useUserStore} from "@/Stores/UserStore";
import videojs from 'video.js';
// import Youtube from 'videojs-youtube';

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'testing'

// onBeforeMount(() => {
//     userStore.scrollToTopCounter = 0;
// })

onMounted(() => {
    videoPlayerStore.makeVideoTopRight()
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()

    videojs(props.id, props.options)
    console.log('onPlayerReady2')
})

onBeforeUnmount(() => {
    let videoJs = videojs(props.id)
    videoJs.dispose();
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
    z-index: 950;
    top: 20px;

}

</style>
