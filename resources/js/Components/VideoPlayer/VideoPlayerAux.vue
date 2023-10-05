<template>
<div class="max-w-[12rem]">
    <div class="max-w-[12rem]">
        <video-player :id="`aux-player`" :options="videoOptions" />
    </div>
</div>

</template>

<script setup>
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore"
let videoPlayerStore = useVideoPlayerStore()

let playerName = 'aux-player';

// let props = defineProps({
//     id: String,
//     src: String,
//     type: String,
// })

</script>


<script>
import VideoPlayer from '@/Components/VideoPlayer/VideoJsAux.vue'

let videoSource = 'https://mist.not.tv/hls/test/index.m3u8';
let videoSourceType = 'application/x-mpegURL';

export default {
    name: 'VideoPlayer',
    components: {
        VideoPlayer
    },
    data() {
        return {
            videoOptions: {
                autoplay: true,
                muted: true,
                controls: true,
                fill: true,
                sources: [
                    {
                        src: videoSource,
                        type: videoSourceType,
                    }
                ]
            }
        };
    },
    props: {
        id: String,
        src: String,
        type: String,
    },
    setup(props) {

    }
};



</script>
