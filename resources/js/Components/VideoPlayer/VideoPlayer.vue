<template>

    <div :class="videoPlayerStore.videoContainerClass">
        <div :class="videoPlayerStore.class">


            <Teleport to="body">
                <Login v-if="!videoPlayerStore.loggedIn" :show="showLogin" @close="showLogin = false" />
            </Teleport>

            <video-player :options="videoOptions" @click="videoPlayerStore.makeVideoFullPage()"/>

            <div v-if="!videoPlayerStore.fullPage" class="absolute top-56 w-full">
                <videoOTTButtons class="videoOTT"/>
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

                <ChatForStreamPageV2 :user="props.user"/>
                <button v-if="!chatStore.showChat" @click="chatStore.showChat = true"
                        class="opacity-80 chatButtonForStreamPage w-20 h-20 rounded-full bg-orange-400 text-orange-100
        hover:bg-orange-600 hover:text-orange-300 cursor-pointer grid justify-center content-center">
                    <font-awesome-icon icon="fa-comments" class="text-3xl"/><div>CHAT</div>
                </button>

            </div>

<div v-if="!videoPlayerStore.fullPage" class="overflow-y-scroll ">
            <div class="absolute flex justify-between top-0 bg-gray-800 px-2 w-full z-50">
                <div>
                    <span class="text-xs uppercase pr-2">Now playing: </span>
                    <span class="font-semibold text-xs">{{ videoPlayerStore.videoName }}</span>
                </div>
                <div v-if="streamStore.isLive" class="absolute py-6 left-0 pl-2 drop-shadow z-50 w-full">
                    <div class="flex justify-between">
                    <div>
                        <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-white bg-opacity-80 bg-red-800 uppercase last:mr-0 mr-1">
                        live
                        </span>
                    </div>
                    <div class="opacity-10">
                        <img :src="`/storage/images/logo_white_512.png`" class="w-10 pt-2 mr-4">
                    </div>
                </div>
                </div>
            </div>

            <VideoOTT :user="props.user"  class="videoOTT absolute top-60 mt-2 w-full h-[calc(100vh-4rem)] overflow-y-scroll"/>

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
import ChatForStreamPageV2 from "@/Components/Chat/ChatForStreamPageV2"
import Login from "@/Components/Login.vue"
import { ref } from 'vue'
import VideoOTTButtons from "@/Components/VideoPlayer/VideoOTTButtons.vue";
import VideoOTT from "@/Components/VideoPlayer/VideoOTT.vue";
import {useChatStore} from "@/Stores/ChatStore";

let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let chatStore = useChatStore()

videoPlayerStore.videoName = "Spring"
videoPlayerStore.paused = false
chatStore.showChat = false

let showLogin = ref(false)


let props = defineProps({
    src: String,
    user: Object,
})

</script>

<script>
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js"
import VideoPlayer from '@/Components/VideoPlayer/VideoJs'
import VideoOtt from '@/Components/VideoPlayer/VideoOTT'
import VideoOttButtons from '@/Components/VideoPlayer/VideoOTTButtons'
import { ref } from 'vue'

export default {
    name: 'VideoPlayer',
    components: {
        VideoPlayer
    },
    data() {
        const videoPlayerStore = useVideoPlayerStore()
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
                            videoSource,
                        type: 'application/x-mpegURL'
                    }
                ]
            }
        };
    }
};



</script>
