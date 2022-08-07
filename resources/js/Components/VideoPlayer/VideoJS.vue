<template>
    <video-js
        controls
        preload="auto"
        class="video-js vjs-big-play-centered"
        ref="video-player"
    >
        <source :src="src">
    </video-js>



</template>

<script setup>

</script>

<script>
import videojs from 'video.js';



export default {
    props: ["src"],

    data() {
        return {
            player: null,
        };
    },
    mounted(){
        const element = this.$refs['video-player']

        this.player = videojs(element, {
            fluid: true,
        });

        this.player.volume(0.5);

    },
};
</script>
