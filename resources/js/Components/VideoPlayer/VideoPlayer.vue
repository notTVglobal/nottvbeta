<template>
    <div class="videoContainer">
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

                    <div
                        ref="player"
                        style="
                            position: absolute;
                            top:12px;
                            right:0;
                            left:0;
                            bottom:0;
                            min-width:24rem;
                            min-height:16rem;
                            "
                            class="shrink"
                    >TEST</div>

            <div v-if="videoPlayerStore.fullPage" class="absolute top-16 left-0 p-5 drop-shadow z-50">
                <span class="text-xs uppercase pr-2">Now playing: </span>
                <span class="font-semibold">{{ videoPlayerStore.videoName }}</span>
            </div>
            <div v-if="!videoPlayerStore.fullPage" class="absolute top-0 bg-gray-800 px-2 z-50">
                <span class="text-xs uppercase pr-2">Now playing: </span>
                <span class="font-semibold">{{ videoPlayerStore.videoName }}</span>
            </div>



            <div v-if="videoPlayerStore.fullPage" class="flex flex-col-4 gap-4 fixed ml-6 px-2 bottom-16">
                <button v-if="videoPlayerStore.paused" @click="playVideo" class="hover:text-blue-600">play</button>
                <button v-if="!videoPlayerStore.paused" @click="pauseVideo" class="hover:text-blue-600">pause</button>
                <button v-if="videoPlayerStore.muted" @click="unMuteVideo" class="text-red-500 hover:text-blue-600">
                    unmute
                </button>
                <button v-if="!videoPlayerStore.muted" @click="muteVideo" class="hover:text-blue-600">mute</button>
            </div>
            <div v-if="videoPlayerStore.fullPage" class="fixed bottom-0 ml-3 my-3">
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
import { ref, reactive, onMounted, nextTick } from 'vue'
import Artplayer from 'artplayer'
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js";
import Hls from 'hls.js'

let videoUrl = `http://mist.nottv.io:8080/hls/ctd1984/index.m3u8`;
let url = `http://mist.nottv.io:8080/hls/ctd1984/index.m3u8`;
let posterUrl = '../../images/poster.jpg'
let thumbnailsUrl = '../../images/thumbnails.png'
let subtitleUrl = '../../images/subtitle.srt'
let ploadingUrl = '../../images/ploading.gif'
let stateUrl = '../../images/state.png'

let videoPlayerStore = useVideoPlayerStore();
const player = ref(null)

const options = reactive({

    customType: {
        m3u8: function (video, url) {
            if (Hls.isSupported()) {
                const hls = new Hls();
                hls.loadSource(url);
                hls.attachMedia(video);
            } else {
                const canPlay = video.canPlayType('application/vnd.apple.mpegurl');
                if (canPlay === 'probably' || canPlay == 'maybe') {
                    video.src = url;
                } else {
                    console.log('Does not support playback of m3u8');
                }
            }
        },
    },
        url: videoUrl,
        title: 'Video Title',
        poster: posterUrl,
        volume: 0.5,
        isLive: false,
        muted: true,
        autoplay: true,
        pip: true,
        autoSize: true,
        autoMini: true,
        screenshot: false,
        setting: true,
        loop: true,
        flip: false,
        playbackRate: true,
        aspectRatio: false,
        fullscreen: true,
        fullscreenWeb: true,
        subtitleOffset: false,
        miniProgressBar: true,
        mutex: true,
        backdrop: true,
        playsInline: true,
        autoPlayback: true,
        airplay: true,
        theme: '#23ade5',
        lang: navigator.language.toLowerCase(),
        whitelist: ['*'],
        moreVideoAttr: {
            crossOrigin: 'anonymous',
        },
        settings: [
            {
                width: 100,
                html: 'Subtitle',
                tooltip: 'Bilingual',
                icon: `<img width="22" heigth="22" src="/assets/img/subtitle.svg">`,
                selector: [
                    {
                        html: 'Display',
                        tooltip: 'Show',
                        switch: true,
                        onSwitch: function (item) {
                            item.tooltip = item.switch ? 'Hide' : 'Show';
                            art.subtitle.show = !item.switch;
                            return !item.switch;
                        },
                    },
                    {
                        default: true,
                        html: 'Bilingual',
                        url: '/assets/sample/subtitle.srt',
                    },
                    {
                        html: 'Chinese',
                        url: '/assets/sample/subtitle.cn.srt',
                    },
                    {
                        html: 'Japanese',
                        url: '/assets/sample/subtitle.jp.srt',
                    },
                ],
                onSelect: function (item) {
                    art.subtitle.switch(item.url, {
                        name: item.html,
                    });
                    return item.html;
                },
            },
        ],
        contextmenu: [
            {
                html: 'Custom menu',
                click: function (contextmenu) {
                    console.info('You clicked on the custom menu');
                    contextmenu.show = true;
                },
            },
        ],
        layers: [
            {
                html: '<img width="100" src="../../images/poster.jpg">',
                click: function () {
                    window.open('https://not.tv');
                    console.info('You clicked on the custom layer');
                },
                style: {
                    position: 'absolute',
                    top: '20px',
                    right: '20px',
                    opacity: '.9',
                    display: 'none',
                },
            },
        ],
        quality: [
            {
                default: true,
                html: 'SD 480P',
                url: videoUrl,
            },
            {
                html: 'HD 720P',
                url: videoUrl,
            },
        ],
        thumbnails: {
            url: thumbnailsUrl,
            number: 60,
            column: 10,
        },
        subtitle: {
            url: subtitleUrl,
            type: 'srt',
            style: {
                color: '#fe9200',
                fontSize: '20px',
            },
            encoding: 'utf-8',
        },
        highlight: [
            {
                time: 15,
                text: 'One more chance',
            },
            {
                time: 30,
                text: '谁でもいいはずなのに',
            },
            {
                time: 45,
                text: '夏の想い出がまわる',
            },
            {
                time: 60,
                text: 'こんなとこにあるはずもないのに',
            },
            {
                time: 75,
                text: '终わり',
            },
        ],
        controls: [
            {
                position: 'right',
                html: 'Control',
                tooltip: 'Control Tooltip',
                click: function () {
                    console.info('You clicked on the custom control');
                },
            },
        ],
        icons: {
            loading: '`<img src="${ploadingUrl}">`',
            state: '`<img width="150" heigth="150" src="${stateUrl}">`',
            indicator: '`<img width="16" heigth="16" src="../../images/indicator.svg">`',
        },
    });

onMounted(() => {
    nextTick(() => {
        new Artplayer({
            ...options,
            container: player.value,
        });
    });
});

defineExpose({
    player,
    options
});

videoPlayerStore.videoName = "Conan The Destroyer";
videoPlayerStore.paused = false;

function newMuted() {
    props.muted = true
}
function webFull() {
    console.log('You clicked WebFull button');
    player.fullScreenWebOn = 1;
}
function loadVideo1() {
    videoPlayerStore.loadVideo1()
    document.getElementById("src1").src = videoPlayerStore.videoSourceIdSrc1;
    document.getElementById("src1").type = videoPlayerStore.videoSourceTypeSrc1;
    document.getElementById("src2").src = videoPlayerStore.videoSourceIdSrc2;
    document.getElementById("src2").type = videoPlayerStore.videoSourceTypeSrc2;
    document.getElementById("src3").src = videoPlayerStore.videoSourceIdSrc3;
    document.getElementById("src3").type = videoPlayerStore.videoSourceTypeSrc3;
    document.getElementById("videoPlayer").load();
}

function loadVideo2() {
    videoPlayerStore.loadVideo2()
    document.getElementById("src1").src = videoPlayerStore.videoSourceIdSrc1;
    document.getElementById("src1").type = videoPlayerStore.videoSourceTypeSrc1;
    document.getElementById("src2").src = videoPlayerStore.videoSourceIdSrc2;
    document.getElementById("src2").type = videoPlayerStore.videoSourceTypeSrc2;
    document.getElementById("src3").src = videoPlayerStore.videoSourceIdSrc3;
    document.getElementById("src3").type = videoPlayerStore.videoSourceTypeSrc3;
    document.getElementById("videoPlayer").load();
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
    document.getElementById("videoPlayer").load();
}

function loadVideo5() {
    videoPlayerStore.loadVideo5()
    document.getElementById("src1").src = videoPlayerStore.videoSourceIdSrc1;
    document.getElementById("src1").type = videoPlayerStore.videoSourceTypeSrc1;
    document.getElementById("src2").src = videoPlayerStore.videoSourceIdSrc2;
    document.getElementById("src2").type = videoPlayerStore.videoSourceTypeSrc2;
    document.getElementById("src3").src = videoPlayerStore.videoSourceIdSrc3;
    document.getElementById("src3").type = videoPlayerStore.videoSourceTypeSrc3;
    document.getElementById("videoPlayer").load();
}


</script>


<style>
.videoContainer {
    position: fixed;
    top: 0rem;
    left: 0;
    width: 100%;
    height: 100%;
}

</style>
