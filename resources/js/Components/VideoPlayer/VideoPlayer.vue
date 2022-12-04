<template>

    <div :class="videoPlayerStore.videoContainerClass">
        <div :class="videoPlayerStore.class"
             @mouseenter="videoPlayerStore.showControls = true"
             @mouseleave="videoPlayerStore.showControls = false"
             >


            <Teleport to="body">
                <Login v-if="$page.props.user===null" :show="showLogin" @close="showLogin = false" />
            </Teleport>

            <video-player :options="videoOptions" v-touch="()=>videoPlayerStore.toggleControls()"/>

            <div v-show="$page.props.user!=null" class="absolute top-16 p-5 right-4 opacity-10 z-50">
                <img :src="`/storage/images/logo_white_512.png`" class="w-20 pt-2">
            </div>

            <div v-show="videoPlayerStore.showControls===true" v-if="videoPlayerStore.fullPage && $page.props.user!=null">
                <div class="absolute w-full flex justify-between top-16 left-0 p-5 drop-shadow z-50">
                    <div>
                        <span class="text-xs uppercase pr-2">Now playing: </span>
                        <span class="font-semibold">{{ videoPlayerStore.videoName }}</span>
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
                <div v-if="videoPlayerStore.currentPage!='stream' && $page.props.user!=null" class="absolute w-full flex justify-between bottom-6 left-0 p-5 drop-shadow z-50 hidden md:block">
                    <div>
                        <button class="p-2 bg-gray-800 text-white hover:bg-gray-600" @click="videoPlayerStore.makeVideoTopRight()">Back to Page</button>
                    </div>
                </div>

                <button v-if="!chatStore.showChat && $page.props.user!=null" @click="chatStore.showChat = true"
                        class="opacity-80 chatButtonForStreamPage w-20 h-20 bottom-6 rounded-full bg-orange-400 text-orange-100
                               hover:bg-orange-600 hover:text-orange-300 cursor-pointer grid justify-center content-center">
                    <font-awesome-icon icon="fa-comments" class="text-3xl"/><div>CHAT</div>
                </button>

                <VideoControls v-if="$page.props.user!=null" :show="true"/>

            </div>

            <ChatForStreamPageV2 v-if="$page.props.user!=null" :user="props.user"/>

            <div v-if="!videoPlayerStore.fullPage && $page.props.user!=null">

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

                <VideoControls v-if="$page.props.user!=null" :show="videoPlayerStore.showControls===true" class="z-50"
                />


            </div>

            </div>
    </div>
</template>

<script setup>
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js"
import {useStreamStore} from "@/Stores/StreamStore";
import ChatForStreamPageV2 from "@/Components/Chat/ChatForStreamPageV2"
import Login from "@/Components/Welcome/Login.vue"
import { ref } from 'vue'
import VideoOTTButtons from "@/Components/VideoPlayer/VideoOTTButtons.vue";
import VideoOTT from "@/Components/VideoPlayer/VideoOTT.vue";
import {useChatStore} from "@/Stores/ChatStore";
import VideoControls from "@/Components/VideoPlayer/VideoControls";

let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let chatStore = useChatStore()

videoPlayerStore.paused = false
chatStore.showChat = false
videoPlayerStore.showControls = false

let showLogin = ref(false)

let props = defineProps({
    src: String,
    user: Object,
})

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
