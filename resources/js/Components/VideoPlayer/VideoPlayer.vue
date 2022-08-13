<template>
    <div :class="videoPlayerStore.videoContainerClass">
        <div :class="videoPlayerStore.class">


<!--                <video-->
<!--                    controls-->
<!--                    loop-->
<!--                    autoplay-->
<!--                    muted-->
<!--                    preload="auto"-->
<!--                    id="videoPlayer"-->
<!--                    class="video-js vjs-big-play-centered object-contain w-full"-->
<!--                >-->
<!--                    <source id="src1" :src="`http://mist.nottv.io:8080/ctd1984.mp4`" type="`video/mp4`"/>-->
<!--                    <source id="src2" :src="`http://mist.nottv.io:8080/hls/ctd1984/index.m3u8`"-->
<!--                            type="`application/x-mpegURL`"/>-->
<!--                    <source id="src3" :src="`ws://mist.nottv.io:8080/ctd1984.mp4`" type="`video/mp4`"/>-->
<!--                </video>-->

<!--                    <div-->
<!--                        ref="player"-->
<!--                        style="-->
<!--                            position: absolute;-->
<!--                            top:12px;-->
<!--                            right:0;-->
<!--                            left:0;-->
<!--                            bottom:0;-->
<!--                            min-width:24rem;-->
<!--                            min-height:16rem;-->
<!--                            "-->
<!--                            class="shrink"-->
<!--                    >TEST</div>-->

            <Teleport to="body">
                <Login v-if="!videoPlayerStore.loggedIn" :show="showLogin" @close="showLogin = false" />
            </Teleport>
            <video-player :options="videoOptions"/>
            <div :show="videoPlayerStore.loggedIn" class="pt-16 pl-6">If you can read this, please refresh the page.</div>

            <div v-if="videoPlayerStore.fullPage" class="absolute top-16 left-0 p-5 drop-shadow z-50">
                <span class="text-xs uppercase pr-2">Now playing: </span>
                <span class="font-semibold">{{ videoPlayerStore.videoName }}</span>
            </div>
            <div v-if="!videoPlayerStore.fullPage" class="absolute top-0 bg-gray-800 px-2 z-50">
                <span class="text-xs uppercase pr-2">Now playing: </span>
                <span class="font-semibold">{{ videoPlayerStore.videoName }}</span>
            </div>
            <div v-if="!videoPlayerStore.loggedIn" class="absolute top-0 right-0 p-5 drop-shadow z-50">
                <Button @click="showLogin = true" class="text-xs uppercase pr-2">
                    <span class="underline">Log in</span> to chat</Button>
            </div>



<!--            <div v-if="videoPlayerStore.fullPage" class="flex flex-col-4 gap-4 fixed ml-6 px-2 bottom-16 z-50">-->
<!--                <button v-if="videoPlayerStore.paused" @click="playVideo" class="hover:text-blue-600">play</button>-->
<!--                <button v-if="!videoPlayerStore.paused" @click="pauseVideo" class="hover:text-blue-600">pause</button>-->
<!--                <button v-if="videoPlayerStore.muted" @click="unMuteVideo" class="text-red-500 hover:text-blue-600">-->
<!--                    unmute-->
<!--                </button>-->
<!--                <button v-if="!videoPlayerStore.muted" @click="muteVideo" class="hover:text-blue-600">mute</button>-->
<!--            </div>-->
            <div v-if="videoPlayerStore.fullPage" class="fixed bottom-0 ml-3 my-3 z-50">
                <button @click="loadVideo1" class="bg-gray-300 text-black p-1 m-2">Spring</button>
                <button @click="loadVideo2" class="bg-gray-300 text-black p-1 m-2">Dune</button>
                <button @click="loadVideo3" class="bg-gray-300 text-black p-1 m-2">1984</button>
                <button @click="loadVideo4" class="bg-gray-300 text-black p-1 m-2">The Terminator</button>
                <button @click="loadVideo5" class="bg-gray-300 text-black p-1 m-2">Natural World</button>
            </div>
            </div>
    </div>

</template>

<script setup>
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js"
import Login from "@/Components/Login.vue"
import { ref } from 'vue'

let videoPlayerStore = useVideoPlayerStore()

videoPlayerStore.videoName = "Spring"
videoPlayerStore.paused = false

let showLogin = ref(false)


function loadVideo1() {
    videoPlayerStore.loadVideo1()
    document.getElementById("src1").src = videoPlayerStore.videoSourceIdSrc1;
    document.getElementById("src1").type = videoPlayerStore.videoSourceTypeSrc1;
    document.getElementById("src2").src = videoPlayerStore.videoSourceIdSrc2;
    document.getElementById("src2").type = videoPlayerStore.videoSourceTypeSrc2;
    document.getElementById("src3").src = videoPlayerStore.videoSourceIdSrc3;
    document.getElementById("src3").type = videoPlayerStore.videoSourceTypeSrc3;
    document.getElementById("VideoPlayer").load();
}

function loadVideo2() {
    videoPlayerStore.loadVideo2()
    document.getElementById("src1").src = videoPlayerStore.videoSourceIdSrc1;
    document.getElementById("src1").type = videoPlayerStore.videoSourceTypeSrc1;
    document.getElementById("src2").src = videoPlayerStore.videoSourceIdSrc2;
    document.getElementById("src2").type = videoPlayerStore.videoSourceTypeSrc2;
    document.getElementById("src3").src = videoPlayerStore.videoSourceIdSrc3;
    document.getElementById("src3").type = videoPlayerStore.videoSourceTypeSrc3;
    // my.load();
}

function loadVideo3() {
    videoPlayerStore.loadVideo3()
    document.getElementById("src1").src = videoPlayerStore.videoSourceIdSrc1;
    document.getElementById("src1").type = videoPlayerStore.videoSourceTypeSrc1;
    document.getElementById("src2").src = videoPlayerStore.videoSourceIdSrc2;
    document.getElementById("src2").type = videoPlayerStore.videoSourceTypeSrc2;
    document.getElementById("src3").src = videoPlayerStore.videoSourceIdSrc3;
    document.getElementById("src3").type = videoPlayerStore.videoSourceTypeSrc3;
    document.getElementById("videoPlayer").load();
}

function loadVideo4() {
    videoPlayerStore.loadVideo4()
    document.getElementById("src1").src = videoPlayerStore.videoSourceIdSrc1;
    document.getElementById("src1").type = videoPlayerStore.videoSourceTypeSrc1;
    document.getElementById("src2").src = videoPlayerStore.videoSourceIdSrc2;
    document.getElementById("src2").type = videoPlayerStore.videoSourceTypeSrc2;
    document.getElementById("src3").src = videoPlayerStore.videoSourceIdSrc3;
    document.getElementById("src3").type = videoPlayerStore.videoSourceTypeSrc3;
    document.getElementById("VideoPlayer").load();
}

function loadVideo5() {
    videoPlayerStore.loadVideo5()
    document.getElementById("src").src = videoPlayerStore.videoSourceIdSrc1;
    document.getElementById("src1").type = videoPlayerStore.videoSourceTypeSrc1;
    document.getElementById("src2").src = videoPlayerStore.videoSourceIdSrc2;
    document.getElementById("src2").type = videoPlayerStore.videoSourceTypeSrc2;
    document.getElementById("src3").src = videoPlayerStore.videoSourceIdSrc3;
    document.getElementById("src3").type = videoPlayerStore.videoSourceTypeSrc3;
    document.getElementById("VideoPlayer").load();
}


</script>

<script>
import VideoPlayer from '@/Components/VideoPlayer/VideoJs.vue';

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
                sources: [
                    {
                        src:
                            'https://mist.nottv.io/hls/spring/index.m3u8',
                            // 'ws://mist.nottv.io:8080/ctd1984.mp4',
                        type: 'application/x-mpegURL'
                    }
                ]
            }
        };
    }
};
</script>
