<template>
<!--    style="border: none; position: absolute; top: 0; left: 0; height: 100%; width: 100%;"-->

<!--        <Draggable-->
<!--            v-slot="{ x, y }"-->
<!--            p="x-4 y-2"-->
<!--            border="~ gray-400 rounded"-->
<!--            shadow="~ hover:lg"-->
<!--            class="fixed bg-$vt-c-bg select-none cursor-move z-10"-->
<!--            :initial-value="{ x: innerWidth / 3.9, y: 150 }"-->
<!--            :prevent-default="true"-->
<!--            storage-key="vueuse-draggable-pos"-->
<!--            storage-type="session"-->
<!--        >-->
    <div class="videoContainer">
        <div :class="videoPlayer.class">
            <div class="absolute top-16 left-0 p-5 drop-shadow" v-if="videoPlayer.fullPage"><span class="text-xs uppercase pr-2">Now playing: </span><span class="font-semibold">{{ videoPlayer.videoName }}</span></div>
            <Link :class="videoPlayer.fullPage === true && 'disabled'" :href="route('stream')">
                <video autoplay muted loop id="videoPlayer" ref="videoPlayerApp" class="object-contain w-full">
                    <source id="src1" :src="videoFirstPlaySrc1" type="video/mp4"/>
                    <source id="src2" :src="videoFirstPlaySrc2" type="application/x-mpegURL"/>
                    <source id="src3" :src="videoFirstPlaySrc3" type="video/mp4"/>
                    Sorry, your browser doesn't support embedded videos.
                </video>
            </Link>
            <div v-if="videoPlayer.fullPage" class="flex flex-col-4 gap-4 fixed ml-6 bottom-16">
                <button v-if="videoPlayer.paused" @click="playVideo" class="hover:text-blue-600">play</button>
                <button v-if="!videoPlayer.paused" @click="pauseVideo" class="hover:text-blue-600">pause</button>
                <button v-if="videoPlayer.muted" @click="unMuteVideo" class="text-red-500 hover:text-blue-600">unmute</button>
                <button v-if="!videoPlayer.muted" @click="muteVideo" class="hover:text-blue-600">mute</button>
            </div>
            <div v-if="videoPlayer.fullPage" class="fixed bottom-0 ml-3 my-3">
                <button @click="loadVideo1" class="bg-gray-300 text-black p-1 m-2">Spring</button>
                <button @click="loadVideo2" class="bg-gray-300 text-black p-1 m-2">Dune</button>
                <button @click="loadVideo3" class="bg-gray-300 text-black p-1 m-2">1984</button>
                <button @click="loadVideo4" class="bg-gray-300 text-black p-1 m-2">The Terminator</button>
                <button @click="loadVideo5" class="bg-gray-300 text-black p-1 m-2">Natural World</button>
            </div>

            <div v-if="!videoPlayer.fullPage" class="bg-gray-800 px-2"><span class="text-xs uppercase pr-2">Now playing: </span><span class="font-semibold">{{ videoPlayer.videoName }}</span></div>
            <div v-if="!videoPlayer.fullPage" class="flex flex-col-4 gap-4 my-3">
                <button v-if="videoPlayer.paused" @click="playVideo" class="hover:text-blue-600">play</button>
                <button v-if="!videoPlayer.paused" @click="pauseVideo" class="hover:text-blue-600">pause</button>
                <button v-if="videoPlayer.muted" @click="unMuteVideo" class="text-red-500 hover:text-blue-600">unmute</button>
                <button v-if="!videoPlayer.muted" @click="muteVideo" class="hover:text-blue-600">mute</button>
            </div>
            <div v-if="!videoPlayer.fullPage" class="my-3">
                <button @click="loadVideo1" class="bg-gray-300 text-black p-1 m-2">Spring</button>
                <button @click="loadVideo2" class="bg-gray-300 text-black p-1 m-2">Dune</button>
                <button @click="loadVideo3" class="bg-gray-300 text-black p-1 m-2">1984</button>
                <button @click="loadVideo4" class="bg-gray-300 text-black p-1 m-2">The Terminator</button>
                <button @click="loadVideo5" class="bg-gray-300 text-black p-1 m-2">Natural World</button>
            </div>

                <!--            <iframe src="https://iframe.videodelivery.net/39ce0cc05aaf8186079fb844942f0afe"-->
                <!--                    class="w-96"-->
                <!--                    allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"-->
                <!--                    allowfullscreen="true">-->
                <!--            </iframe>-->
    <!--            <div class="text-xs opacity-50 bg-gray-800 text-gray-200">-->
    <!--                <p>({{ Math.round(x) }}, {{ Math.round(y) }}) CLICK HERE TO MOVE VIDEO PLAYER</p>-->
    <!--                <p>This video is a webm format and won't playback on an iPhone or iPad.<br />-->
    <!--                    This is just for testing purposes.-->
    <!--                </p>-->
    <!--            </div>-->
    <!--        </Draggable>-->
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

// import { isClient } from "@vueuse/shared";
// import { useDraggable } from "@vueuse/core";
// import { UseDraggable as Draggable } from "@vueuse/components";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js";

let videoPlayer = useVideoPlayerStore();
let videoFirstPlaySrc1 = ref([`http://mist.nottv.io:8080/spring.mp4`]);
let videoFirstPlaySrc2 = ref([`http://mist.nottv.io:8080/hls/spring/index.m3u8`]);
let videoFirstPlaySrc3 = ref([`ws://mist.nottv.io:8080/spring.mp4`]);
videoPlayer.videoName = "Spring";
videoPlayer.paused = false;

function muteVideo() {
    document.getElementById("videoPlayer").muted = true;
    videoPlayer.muted = true;
}

function pauseVideo() {
    document.getElementById("videoPlayer").pause();
    videoPlayer.paused = true;
}

function playVideo() {
    document.getElementById("videoPlayer").play();
    videoPlayer.paused = false;
}

function unMuteVideo() {
    document.getElementById("videoPlayer").muted = false;
    videoPlayer.muted = false;
}

function loadVideo1() {
    videoPlayer.loadVideo1()
    document.getElementById("src1").src = videoPlayer.videoSourceIdSrc1;
    document.getElementById("src1").type = videoPlayer.videoSourceTypeSrc1;
    document.getElementById("src2").src = videoPlayer.videoSourceIdSrc2;
    document.getElementById("src2").type = videoPlayer.videoSourceTypeSrc2;
    document.getElementById("src3").src = videoPlayer.videoSourceIdSrc3;
    document.getElementById("src3").type = videoPlayer.videoSourceTypeSrc3;
    document.getElementById("videoPlayer").load();
}
function loadVideo2() {
    videoPlayer.loadVideo2()
    document.getElementById("src1").src = videoPlayer.videoSourceIdSrc1;
    document.getElementById("src1").type = videoPlayer.videoSourceTypeSrc1;
    document.getElementById("src2").src = videoPlayer.videoSourceIdSrc2;
    document.getElementById("src2").type = videoPlayer.videoSourceTypeSrc2;
    document.getElementById("src3").src = videoPlayer.videoSourceIdSrc3;
    document.getElementById("src3").type = videoPlayer.videoSourceTypeSrc3;
    document.getElementById("videoPlayer").load();
}
function loadVideo3() {
    videoPlayer.loadVideo3()
    document.getElementById("src1").src = videoPlayer.videoSourceIdSrc1;
    document.getElementById("src1").type = videoPlayer.videoSourceTypeSrc1;
    document.getElementById("src2").src = videoPlayer.videoSourceIdSrc2;
    document.getElementById("src2").type = videoPlayer.videoSourceTypeSrc2;
    document.getElementById("src3").src = videoPlayer.videoSourceIdSrc3;
    document.getElementById("src3").type = videoPlayer.videoSourceTypeSrc3;
    document.getElementById("videoPlayer").load();
}
function loadVideo4() {
    videoPlayer.loadVideo4()
    document.getElementById("src1").src = videoPlayer.videoSourceIdSrc1;
    document.getElementById("src1").type = videoPlayer.videoSourceTypeSrc1;
    document.getElementById("src2").src = videoPlayer.videoSourceIdSrc2;
    document.getElementById("src2").type = videoPlayer.videoSourceTypeSrc2;
    document.getElementById("src3").src = videoPlayer.videoSourceIdSrc3;
    document.getElementById("src3").type = videoPlayer.videoSourceTypeSrc3;
    document.getElementById("videoPlayer").load();
}
function loadVideo5() {
    videoPlayer.loadVideo5()
    document.getElementById("src1").src = videoPlayer.videoSourceIdSrc1;
    document.getElementById("src1").type = videoPlayer.videoSourceTypeSrc1;
    document.getElementById("src2").src = videoPlayer.videoSourceIdSrc2;
    document.getElementById("src2").type = videoPlayer.videoSourceTypeSrc2;
    document.getElementById("src3").src = videoPlayer.videoSourceIdSrc3;
    document.getElementById("src3").type = videoPlayer.videoSourceTypeSrc3;
    document.getElementById("videoPlayer").load();
}

// const el = ref<HTMLElement | null>(null)
// const innerWidth = isClient ? window.innerWidth : 200
// const { x, y, style } = useDraggable(el, {
//     initialValue: { x: innerWidth / 4.2, y: 80 },
//     preventDefault: true,
// })

</script>

<script>
export default {
    name: "VideoPlayerApp",
    methods: {
        play() {
            this.$refs.videoPlayerApp.play();
        },
        pause() {
            this.$refs.videoPlayerApp.pause();
        },
        mute() {
            if (this.$refs.videoPlayerApp.muted === false) {
                this.$refs.videoPlayerApp.muted = true;
            } else {
                this.$refs.videoPlayerApp.muted = false;
            }
        },
        setSpeed(speed) {
            this.$refs.videoPlayerApp.playbackRate = speed;
        },
    },
};
</script>

<style>
.videoContainer {
    position:fixed;
    top: 0rem;
    left:0;
    width:100%;
}

.disabled {
    pointer-events: none;
}

</style>
