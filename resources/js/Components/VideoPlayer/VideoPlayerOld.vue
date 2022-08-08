<!--<template>-->
<!--    <div class="videoContainer">-->
<!--        <div :class="videoPlayer.class">-->
<!--            <div class="absolute top-16 left-0 p-5 drop-shadow" v-if="videoPlayer.fullPage">-->
<!--                <span class="text-xs uppercase pr-2">Now playing: </span>-->
<!--                <span class="font-semibold">{{ videoPlayer.videoName }}</span>-->
<!--            </div>-->

<!--            <div>-->
<!--                <video-js-->
<!--                    controls-->
<!--                    autoplay-->
<!--                    muted-->
<!--                    loop-->
<!--                    preload="auto"-->
<!--                    id="videoPlayer"-->
<!--                    class="video-js vjs-big-play-centered object-contain w-full"-->
<!--                    ref="video-player"-->
<!--                    type="application/x-mpegURL"-->
<!--                >-->
<!--                    <source id="src1" :src="videoFirstPlaySrc1" type="video/mp4"/>-->
<!--                    <source id="src2" :src="videoFirstPlaySrc2" type="application/x-mpegURL"/>-->
<!--                    <source id="src3" :src="videoFirstPlaySrc3" type="video/mp4"/>-->
<!--                </video-js>-->
<!--            </div>-->

<!--            <div v-if="videoPlayer.fullPage" class="flex flex-col-4 gap-4 fixed ml-6 bottom-16">-->
<!--                <button v-if="videoPlayer.paused" @click="playVideo" class="hover:text-blue-600">play</button>-->
<!--                <button v-if="!videoPlayer.paused" @click="pauseVideo" class="hover:text-blue-600">pause</button>-->
<!--                <button v-if="videoPlayer.muted" @click="unMuteVideo" class="text-red-500 hover:text-blue-600">unmute</button>-->
<!--                <button v-if="!videoPlayer.muted" @click="muteVideo" class="hover:text-blue-600">mute</button>-->
<!--            </div>-->
<!--            <div v-if="videoPlayer.fullPage" class="fixed bottom-0 ml-3 my-3">-->
<!--                <button @click="loadVideo1" class="bg-gray-300 text-black p-1 m-2">Spring</button>-->
<!--                <button @click="loadVideo2" class="bg-gray-300 text-black p-1 m-2">Dune</button>-->
<!--                <button @click="loadVideo3" class="bg-gray-300 text-black p-1 m-2">1984</button>-->
<!--                <button @click="loadVideo4" class="bg-gray-300 text-black p-1 m-2">The Terminator</button>-->
<!--                <button @click="loadVideo5" class="bg-gray-300 text-black p-1 m-2">Natural World</button>-->
<!--            </div>-->

<!--            <div v-if="!videoPlayer.fullPage" class="bg-gray-800 px-2">-->
<!--                <span class="text-xs uppercase pr-2">Now playing: </span>-->
<!--                <span class="font-semibold">{{ videoPlayer.videoName }}</span>-->
<!--            </div>-->
<!--            <div v-if="!videoPlayer.fullPage" class="flex flex-col-4 gap-4 my-3">-->
<!--                <button v-if="videoPlayer.paused" @click="playVideo" class="hover:text-blue-600">play</button>-->
<!--                <button v-if="!videoPlayer.paused" @click="pauseVideo" class="hover:text-blue-600">pause</button>-->
<!--                <button v-if="videoPlayer.muted" ref="unmute" class="text-red-500 hover:text-blue-600">unmute</button>-->
<!--                <button v-if="!videoPlayer.muted" ref="mute" class="hover:text-blue-600">mute</button>-->
<!--            </div>-->
<!--            <div v-if="!videoPlayer.fullPage" class="my-3">-->
<!--                <button @click="loadVideo1" class="bg-gray-300 text-black p-1 m-2">Spring</button>-->
<!--                <button @click="loadVideo2" class="bg-gray-300 text-black p-1 m-2">Dune</button>-->
<!--                <button @click="loadVideo3" class="bg-gray-300 text-black p-1 m-2">1984</button>-->
<!--                <button @click="loadVideo4" class="bg-gray-300 text-black p-1 m-2">The Terminator</button>-->
<!--                <button @click="loadVideo5" class="bg-gray-300 text-black p-1 m-2">Natural World</button>-->
<!--            </div>-->

<!--        </div>-->
<!--    </div>-->
<!--</template>-->

<!--<script setup>-->
<!--import { ref } from "vue";-->
<!--import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js";-->

<!--let videoPlayer = useVideoPlayerStore();-->
<!--let videoFirstPlaySrc1 = ref([`http://mist.nottv.io:8080/ctd1984.mp4`]);-->
<!--let videoFirstPlaySrc2 = ref([`http://mist.nottv.io:8080/hls/ctd1984/index.m3u8`]);-->
<!--let videoFirstPlaySrc3 = ref([`ws://mist.nottv.io:8080/ctd1984.mp4`]);-->
<!--videoPlayer.videoName = "Conan The Destroyer";-->
<!--videoPlayer.paused = false;-->

<!--let video = videojs(document.getElementById("videoPlayer"));-->

<!--function muteVideo() {-->
<!--    document.getElementById("videoPlayer").muted = true;-->
<!--    video.muted = true;-->
<!--}-->

<!--function pauseVideo() {-->
<!--    document.getElementById("videoPlayer").pause();-->
<!--    video.pause();-->
<!--}-->

<!--function playVideo() {-->
<!--    document.getElementById("videoPlayer").play();-->
<!--    video.play();-->
<!--}-->

<!--function unMuteVideo() {-->
<!--    document.getElementById("videoPlayer").muted = false;-->
<!--    videoPlayer.muted = false;-->
<!--}-->

<!--function loadVideo1() {-->
<!--    videoPlayer.loadVideo1()-->
<!--    document.getElementById("src1").src = videoPlayer.videoSourceIdSrc1;-->
<!--    document.getElementById("src1").type = videoPlayer.videoSourceTypeSrc1;-->
<!--    document.getElementById("src2").src = videoPlayer.videoSourceIdSrc2;-->
<!--    document.getElementById("src2").type = videoPlayer.videoSourceTypeSrc2;-->
<!--    document.getElementById("src3").src = videoPlayer.videoSourceIdSrc3;-->
<!--    document.getElementById("src3").type = videoPlayer.videoSourceTypeSrc3;-->
<!--    document.getElementById("videoPlayer").load();-->
<!--}-->
<!--function loadVideo2() {-->
<!--    videoPlayer.loadVideo2()-->
<!--    document.getElementById("src1").src = videoPlayer.videoSourceIdSrc1;-->
<!--    document.getElementById("src1").type = videoPlayer.videoSourceTypeSrc1;-->
<!--    document.getElementById("src2").src = videoPlayer.videoSourceIdSrc2;-->
<!--    document.getElementById("src2").type = videoPlayer.videoSourceTypeSrc2;-->
<!--    document.getElementById("src3").src = videoPlayer.videoSourceIdSrc3;-->
<!--    document.getElementById("src3").type = videoPlayer.videoSourceTypeSrc3;-->
<!--    document.getElementById("videoPlayer").load();-->
<!--}-->
<!--function loadVideo3() {-->
<!--    videoPlayer.loadVideo3()-->
<!--    document.getElementById("src1").src = videoPlayer.videoSourceIdSrc1;-->
<!--    document.getElementById("src1").type = videoPlayer.videoSourceTypeSrc1;-->
<!--    document.getElementById("src2").src = videoPlayer.videoSourceIdSrc2;-->
<!--    document.getElementById("src2").type = videoPlayer.videoSourceTypeSrc2;-->
<!--    document.getElementById("src3").src = videoPlayer.videoSourceIdSrc3;-->
<!--    document.getElementById("src3").type = videoPlayer.videoSourceTypeSrc3;-->
<!--    document.getElementById("videoPlayer").load();-->
<!--}-->
<!--function loadVideo4() {-->
<!--    videoPlayer.loadVideo4()-->
<!--    document.getElementById("src1").src = videoPlayer.videoSourceIdSrc1;-->
<!--    document.getElementById("src1").type = videoPlayer.videoSourceTypeSrc1;-->
<!--    document.getElementById("src2").src = videoPlayer.videoSourceIdSrc2;-->
<!--    document.getElementById("src2").type = videoPlayer.videoSourceTypeSrc2;-->
<!--    document.getElementById("src3").src = videoPlayer.videoSourceIdSrc3;-->
<!--    document.getElementById("src3").type = videoPlayer.videoSourceTypeSrc3;-->
<!--    document.getElementById("videoPlayer").load();-->
<!--}-->
<!--function loadVideo5() {-->
<!--    videoPlayer.loadVideo5()-->
<!--    document.getElementById("src1").src = videoPlayer.videoSourceIdSrc1;-->
<!--    document.getElementById("src1").type = videoPlayer.videoSourceTypeSrc1;-->
<!--    document.getElementById("src2").src = videoPlayer.videoSourceIdSrc2;-->
<!--    document.getElementById("src2").type = videoPlayer.videoSourceTypeSrc2;-->
<!--    document.getElementById("src3").src = videoPlayer.videoSourceIdSrc3;-->
<!--    document.getElementById("src3").type = videoPlayer.videoSourceTypeSrc3;-->
<!--    document.getElementById("videoPlayer").load();-->
<!--}-->


<!--</script>-->


<!--<script>-->
<!--import videojs from 'video.js';-->

<!--export default {-->
<!--    props: ["src"],-->
<!--    data() {-->
<!--        return {-->
<!--            player: null,-->
<!--        };-->
<!--    },-->
<!--    mounted(){-->
<!--        const element = this.$refs['video-player']-->

<!--        this.player = videojs(element, {-->
<!--            fluid: true,-->
<!--        });-->
<!--        this.player.volume(0.5);-->

<!--    },-->
<!--    name: "video-player",-->
<!--    methods: {-->
<!--        play() {-->
<!--            this.player.play();-->
<!--        },-->
<!--        pause() {-->
<!--            this.player.pause();-->
<!--        },-->
<!--        mute() {-->
<!--            const element = this.$refs['video-player']-->
<!--            this.player = videojs(element);-->
<!--            if (this.player.muted === false) {-->
<!--                this.player.muteVideo();-->
<!--            } else {-->
<!--                this.player.unMuteVideo();-->
<!--            }-->
<!--        },-->
<!--        setSpeed(speed) {-->
<!--            this.$refs['video-player'].playbackRate = speed;-->
<!--        },-->
<!--    },-->
<!--};-->
<!--</script>-->

<!--<style>-->
<!--.videoContainer {-->
<!--    position:fixed;-->
<!--    top: 0rem;-->
<!--    left:0;-->
<!--    width:100%;-->
<!--}-->

<!--.disabled {-->
<!--    pointer-events: none;-->
<!--}-->

<!--</style>-->
