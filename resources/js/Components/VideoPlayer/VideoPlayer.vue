<template>

    <div :class="videoPlayerStore.videoContainerClass">
        <div :class="videoPlayerStore.class">


            <Teleport to="body">
                <Login v-if="!videoPlayerStore.loggedIn" :show="showLogin" @close="showLogin = false" />
            </Teleport>

            <video-player :options="videoOptions" @click="videoPlayerStore.makeVideoFullPage()"/>

            <div v-if="videoPlayerStore.fullPage">
                <div class="absolute w-full flex justify-between top-16 left-0 p-5 drop-shadow z-50">
                    <div>
                        <span class="text-xs uppercase pr-2">Now playing: </span>
                        <span class="font-semibold">{{ videoPlayerStore.videoName }}</span>
                    </div>
                    <div class="opacity-10">
                        <img :src="`/storage/images/logo_white_512.png`" class="w-20 pt-2">
                    </div>
                    <div v-if="streamStore.isLive" class="absolute py-6 left-0 px-5 drop-shadow z-50">
                    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-white bg-opacity-80 bg-red-800 uppercase last:mr-0 mr-1">
                        live
                    </span>
                        <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-white bg-opacity-50 bg-black uppercase last:mr-0 mr-1">
                        <font-awesome-icon icon="fa-solid fa-eye" class="pr-1" /> 88
                    </span>
                    </div>
                </div>
                <div class="absolute w-full flex justify-between bottom-16 left-0 p-5 drop-shadow z-50">
                    <div>
                        <span class="text-xs uppercase pr-2">BOTTOM: </span>
                        <span class="font-semibold"> AREA</span>
                    </div>
                    <div class="opacity-10">
                        <img :src="`/storage/images/logo_white_512.png`" class="w-20 pt-2">
                    </div>
                    MORE TEXT
                </div>
            </div>

<div v-if="!videoPlayerStore.fullPage">
            <div class="absolute w-full flex justify-between top-0 bg-gray-800 px-2 w-full z-50">
                <div>
                    <span class="text-xs uppercase pr-2">Now playing: </span>
                    <span class="font-semibold">{{ videoPlayerStore.videoName }}</span>
                </div>
                <div v-if="streamStore.isLive">
                    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-white bg-opacity-80 bg-red-800 uppercase last:mr-0 mr-1">
                        live
                    </span>
                    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-white bg-opacity-50 bg-black uppercase last:mr-0 mr-1">
                        <font-awesome-icon icon="fa-solid fa-eye" class="pr-1" /> 99
                    </span>
                </div>
            </div>
                <div class="absolute w-full flex justify-between bottom-0 bg-gray-800 px-2 w-full z-50">
                    <div>
                        <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-white bg-opacity-80 bg-red-800 uppercase last:mr-0 mr-1">BOTTOM: </span>
                        <span class="font-semibold"> AREA</span>
                    </div>
                    <div class="">
                        IS IT FIXED?
                    </div>
                    <img :src="`/storage/images/logo_white_512.png`" class="w-20 pt-2 pb-6 opacity-10">
                </div>
    ANOTHER TEST TEXT
    <div class="h-96 bg-green-200 bottom-100 z-50">Something is covering this... it's a big black box. Figure it out.</div>

</div>


            <div v-if="!videoPlayerStore.loggedIn" class="welcomeOverlay">
                <div class="landscape:hidden">
                <div class="absolute top-0 right-0 p-5 drop-shadow">
                    <div class="grid grid-rows-1 place-content-end pt-2">
                        <img :src="`/storage/images/logo_white_512.png`" class="w-20 pt-2">
                    </div>
                    <button @click="showLogin = true" class="text-2xl uppercase p-2">
                    <span class="underline text-blue-400 hover:text-blue-600">Log in</span> to chat</button>
                </div>
                </div>
                <div class="portrait:hidden">
                    <div class="absolute top-0 right-0 p-5 drop-shadow">
                        <div class="grid grid-rows-1 place-content-end pt-2">
                            <img :src="`/storage/images/logo_white_512.png`" class="w-20 pt-2">
                        </div>
                    </div>
                    <div class="absolute bottom-0 right-0 py-8 px-5 drop-shadow">
                        <button @click="showLogin = true" class="text-2xl uppercase p-2">
                            <span class="underline text-blue-400 hover:text-blue-600">Log in</span> to chat</button>
                    </div>
                </div>

            </div>

            </div>
    </div>
</template>

<script setup>
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js"
import {useStreamStore} from "@/Stores/StreamStore";
import Login from "@/Components/Login.vue"
import { ref } from 'vue'
import videojs from "video.js";

let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()

videoPlayerStore.videoName = "Spring"
videoPlayerStore.paused = false

let showLogin = ref(false)

// import VideoPlayer from '@/Components/VideoPlayer/VideoJs'
// const videoPlayer = ref('VideoPlayer')

let props = defineProps({
    src: String,
    // videoOptions: {
    //     autoplay: true,
    //     muted: true,
    //     controls: true,
    //     sources: [
    //         {
    //             src:
    //                 'https://streams.not.tv/hls/naturalworld/index.m3u8',
    //
    //             type: 'application/x-mpegURL'
    //         }
    //     ]
    // }
})

// function loadVideo1() {
//     videoPlayerStore.loadVideo1()
//     document.getElementById("src1").src = videoPlayerStore.videoSourceIdSrc1;
//     document.getElementById("src1").type = videoPlayerStore.videoSourceTypeSrc1;
//     document.getElementById("src2").src = videoPlayerStore.videoSourceIdSrc2;
//     document.getElementById("src2").type = videoPlayerStore.videoSourceTypeSrc2;
//     document.getElementById("src3").src = videoPlayerStore.videoSourceIdSrc3;
//     document.getElementById("src3").type = videoPlayerStore.videoSourceTypeSrc3;
//     document.getElementById("VideoPlayer").load();
// }



// const props = defineProps({
//     videoOptions: {
//         autoplay: true,
//         muted: true,
//         controls: true,
//         sources: [
//             {
//                 src:
//                     'https://mist.nottv.io/hls/dune1984/index.m3u8',
//                 // 'ws://mist.nottv.io:8080/ctd1984.mp4',
//                 type: 'application/x-mpegURL'
//             }
//         ]
//     }
// })


// let videoOptions = reactive({
//     autoplay: true,
//     muted: true,
//     controls: true,
//     sources: [{
//         src: 'https://mist.nottv.io/hls/spring/index.m3u8',
//     // 'ws://mist.nottv.io:8080/ctd1984.mp4',
//         type: 'application/x-mpegURL'
//         }]
// })

</script>

<script>
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js"
import VideoPlayer from '@/Components/VideoPlayer/VideoJs'
import { ref } from 'vue'

export default {
    name: 'VideoPlayer',
    components: {
        VideoPlayer
    },
    data() {
        const videoPlayerStore = useVideoPlayerStore()
        // videoPlayerStore.videoSource = 'https://streams.not.tv/hls/naturalworld/index.m3u8'
        const videoSource = videoPlayerStore.videoSource
        return {
            videoOptions: {
                autoplay: true,
                muted: true,
                controls: true,
                enableSourceset: true,
                sources: [
                    {
                        src:
                            // source,
                            videoSource,
                            // 'https://multiplatform-f.akamaihd.net/i/multi/will/bunny/big_buck_bunny_,640x360_400,640x360_700,640x360_1000,950x540_1500,.f4v.csmil/master.m3u8',
                            // 'ws://mist.nottv.io:8080/ctd1984.mp4',
                            // 'https://streams.not.tv/spring.mp4',
                        type: 'application/x-mpegURL'
                    }
                ]
            }
        };
    }
};



</script>
