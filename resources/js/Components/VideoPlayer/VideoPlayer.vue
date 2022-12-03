<template>

    <div :class="videoPlayerStore.videoContainerClass">
        <div :class="videoPlayerStore.class">


            <Teleport to="body">
                <Login v-if="!videoPlayerStore.loggedIn" :show="showLogin" @close="showLogin = false" />
            </Teleport>

            <video-player :options="videoOptions" @click="videoPlayerStore.makeVideoFullPage()"/>

            <div v-if="!videoPlayerStore.fullPage" class="video-bottom-menu flex flex-row justify-around h-12 bg-red-500 w-full">
                <button class="h-full w-full bg-green-900 hover:bg-green-700" @click="videoPlayerStore.ott = 1"> BACK </button>
                <button class="h-full w-full"
                        :class="{ 'bg-purple-900': videoPlayerStore.ott != 2, 'bg-purple-700': videoPlayerStore.ott === 2, 'hover:bg-purple-700':videoPlayerStore.ott != 2 }"
                        @click="videoPlayerStore.toggleOtt(2)">
                    INFO </button>
                <button class="h-full w-full"
                        :class="{ 'bg-orange-900': videoPlayerStore.ott != 3, 'bg-orange-700': videoPlayerStore.ott === 3, 'hover:bg-orange-700':videoPlayerStore.ott != 3 }"
                        @click="videoPlayerStore.toggleOtt(3)">
                    PLAYLIST </button>
                <button class="h-full w-full bg-indigo-900"
                        :class="{ 'bg-indigo-900': videoPlayerStore.ott != 4, 'bg-indigo-700': videoPlayerStore.ott === 4, 'hover:bg-indigo-700':videoPlayerStore.ott != 4 }"
                        @click="videoPlayerStore.toggleOtt(4)">
                    CHAT </button>
                <button class="h-full w-full bg-yellow-900"
                        :class="{ 'bg-yellow-900': videoPlayerStore.ott != 5, 'bg-yellow-700': videoPlayerStore.ott === 5, 'hover:bg-yellow-700':videoPlayerStore.ott != 5 }"
                        @click="videoPlayerStore.toggleOtt(5)">
                    FILTERS </button>

            </div>

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
                <div v-if="videoPlayerStore.currentPage!='stream'" class="absolute w-full flex justify-between bottom-16 left-0 p-5 drop-shadow z-50 hidden md:block">
                    <div>
                        <button class="p-2 bg-gray-800 text-white" @click="videoPlayerStore.makeVideoTopRight()">Back to Page</button>
                    </div>
                </div>
            </div>

<div v-if="!videoPlayerStore.fullPage" class="h-[calc(100vh-4rem)] bg-black">
            <div class="absolute flex justify-between top-0 bg-gray-800 px-2 w-full z-50">
                <div>
                    <span class="text-xs uppercase pr-2">Now playing: </span>
                    <span class="font-semibold text-xs">{{ videoPlayerStore.videoName }}</span>
                </div>
                <div v-if="streamStore.isLive" class="absolute py-6 left-0 pl-2 drop-shadow z-50">
                    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-white bg-opacity-80 bg-red-800 uppercase last:mr-0 mr-1">
                        live
                    </span>
                </div>
            </div>

    <div v-show="videoPlayerStore.ott === 2" class="now-playing w-full h-full bg-purple-800 p-2">
        <h1 class="text-xs font-semibold uppercase mb-3">NOW PLAYING INFO</h1>
        <div class="flex justify-between">
            <div>
                <div>Name: {{ streamStore.name }}</div>
                <div>Description: {{ streamStore.description }}</div>
                <div>Team: {{ streamStore.teamName }}</div>
            </div>
            <div><img :src="`/storage/images/${streamStore.posterUrl}`"></div>
        </div>
        <div class="py-2">
            Click the poster to go to the show page.
        </div>
        <div class="py-2">
            Creators here.
        </div>
        <div class="py-2">
            Copyright Team Name here.
        </div>


    </div>
    <div v-show="videoPlayerStore.ott === 3" class="now-playing w-full h-full bg-orange-800 p-2">
        <h1 class="text-xs font-semibold uppercase">PLAYLIST</h1>
        <div>
            Add a loop here to display the playlist... scrollable. If the current channel is the users_channel,
            display the playlist for the user. If the current channel is another channel (e.g., stream), display
            the stream_playlist.
        </div>
    </div>
    <div v-show="videoPlayerStore.ott === 4" class="now-playing w-full h-full bg-indigo-800 p-2">
        <h1 class="text-xs font-semibold uppercase">CHAT</h1>
        <div>
            Insert chat here
        </div>
    </div>
    <div v-show="videoPlayerStore.ott === 5" class="now-playing w-full h-full bg-yellow-500 text-black p-2">
        <h1 class="text-xs font-semibold uppercase">FILTERS</h1>
        <div>
            Coming Soon!
        </div>
    </div>

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
                controls: false,
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
