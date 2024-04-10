<template>
    <Head title="Testing" />
    <div>
<!--        <video-->
<!--            id="vid1"-->
<!--            class="video-js vjs-default-skin"-->
<!--            controls-->
<!--            autoplay-->
<!--            width="320" height="132"-->
<!--            data-setup='{ "techOrder": ["youtube"], "sources": [{ "type": "video/youtube", "src": "https://www.youtube.com/watch?v=xjS6SftYQaQ"}], "youtube": { "ytControls": 2 } }'-->
<!--        >-->
<!--        </video>-->
<!--        <button @click="videoPlayerStore.getVideoCurrentTime()" class="mt-8 rounded-lg bg-blue-600 hover:bg-blue-800 text-white">Get Time</button>-->
<!--        <div class="mt-8">Time: {{videoPlayerStore.videoCurrentTime}}</div>-->
    </div>

</template>

<script setup>
import {ref, onBeforeMount, watch, onBeforeUnmount, onMounted} from "vue"
import { usePageSetup } from '@/Utilities/PageSetup'

usePageSetup('testing')

// onBeforeMount(() => {
//     userStore.scrollToTopCounter = 0;
// })

// let currentTime = ref()

onMounted(() => {
  console.log('onPlayerReady2')
})




// onBeforeUnmount(() => {
//     let videoJs = videojs(props.id)
//     videoJs.dispose();
// })



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
