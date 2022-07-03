

<template>
    <div>
<!--        <video width="320" height="240" controls>-->
<!--&lt;!&ndash;            <source src="https://cloudflarestream.com/91dc50d14c619364b534537fc95b45b4/manifest/manifest.m3u8" type="application/x-mpegURL">&ndash;&gt;-->
<!--&lt;!&ndash;            <source src="http://mist.nottv.io:8080/liberty_april_02.mp4" type="video/mp4">&ndash;&gt;-->
<!--            <source src="http://mist.nottv.io:8080/hls/liberty_april_02/index.m3u8" type="application/x-mpegURL">-->


<!--        </video>-->
        <video ref="videoPlayer" class="video-js"></video>

    </div>
</template>


<script>
    // import {Head, Link} from '@inertiajs/inertia-vue3';
    import videojs from 'video.js';

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
