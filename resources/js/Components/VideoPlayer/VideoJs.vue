<template>
        <div>
            <video ref="videoPlayer" class="video-js" style="max-width: 10rem; max-height: 10rem;"></video>
        </div>
</template>


<script setup>
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js";
let videoPlayerStore = useVideoPlayerStore();

</script>
<script>
import videojs from 'video.js';
import 'video.js/dist/video-js.css';

export default {
    name: 'VideoPlayer',
    props: {
        options: {
            type: Object,
            default() {
                return {};
            }
        }
    },
    data() {
        return {
            player: null
        }
    },
    mounted() {
        this.player = videojs(this.$refs.videoPlayer, this.options, () => {
            this.player.log('onPlayerReady', this);
        });
    },
    beforeDestroy() {
        if (this.player) {
            this.player.dispose();
        }
    }
}
</script>
