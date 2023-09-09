<template>
    <div>
        <video-js ref="videoPlayer"
                  id="main-player"
                  class="video-js vjs-big-play-centered vjs-fill"
                  data-setup='{"controls": false, "autoplay": true, "preload": "auto", "muted": true, "playsinline": true}' >
        </video-js>
    </div>

</template>

<script setup>
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js";
import {useStreamStore} from "@/Stores/StreamStore.js";
import {useChatStore} from "@/Stores/ChatStore.js";
import {useUserStore} from "@/Stores/UserStore.js";

let videoPlayerStore = useVideoPlayerStore();
let streamStore = useStreamStore();
let chatStore = useChatStore();
let userStore = useUserStore();

</script>

<style>


</style>

