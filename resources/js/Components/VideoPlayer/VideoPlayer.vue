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


<!-- Video OSD (on screen display) when video is FullPage and the user is logged in. -->
            <div v-show="videoPlayerStore.fullPage===true && $page.props.user!=null" class="absolute h-screen top-16 p-5 right-4 opacity-10 z-50">
                <img :src="`/storage/images/logo_white_512.png`" class="w-20 pt-2">
            </div>

            <div v-show="videoPlayerStore.showControls===true" v-if="videoPlayerStore.fullPage && $page.props.user!=null">

                <div v-if="! chatStore.showChat && userStore.isMobile">
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
                                <font-awesome-icon icon="fa-solid fa-user" class="pr-1" /> 88
                            </span>
                        </div>
                    </div>
                    <div v-if="videoPlayerStore.currentPage!='stream' && $page.props.user!=null" @click="videoPlayerStore.makeVideoTopRight()" class="absolute w-full flex justify-between mb-6 top-32 left-0 p-5 drop-shadow z-50">
                        <div>
                            <button class="p-2 bg-gray-800 text-white hover:bg-gray-600" >Back to Page</button>
                        </div>
                    </div>
                </div>

                <div v-if="!userStore.isMobile">
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
                                <font-awesome-icon icon="fa-solid fa-user" class="pr-1" /> 88
                            </span>
                        </div>
                    </div>
                    <div v-if="videoPlayerStore.currentPage!='stream' && $page.props.user!=null" @click="backToPage" class="absolute w-full flex justify-between mb-6 top-32 left-0 p-5 drop-shadow z-50">
                        <div>
                            <button class="p-2 bg-gray-800 text-white hover:bg-gray-600" >Back to Page</button>
                        </div>
                    </div>
                </div>

                <button v-if="!streamStore.showOSD && $page.props.user!=null" @click="streamStore.toggleChannels()"
                        class="opacity-80 chatButtonForStreamPage w-20 h-20 bottom-6 mr-24 rounded-full bg-green-400 text-green-100
                               hover:bg-green-600 hover:text-green-300 cursor-pointer grid justify-center content-center text-xs">
                    <font-awesome-icon icon="fa-rocket" class="ml-3 text-3xl"/><div>CHANNELS</div>
                </button>

                <button v-if="!streamStore.showOSD && $page.props.user!=null"  @click="streamStore.toggleChat()"
                        class="opacity-80 chatButtonForStreamPage w-20 h-20 bottom-6 rounded-full bg-orange-400 text-orange-100
                               hover:bg-orange-600 hover:text-orange-300 cursor-pointer grid justify-center content-center">
                    <font-awesome-icon icon="fa-comments" class="text-3xl"/><div>CHAT1</div>
                </button>

                <div v-if="userStore.isMobile">
                    <VideoControls v-if="$page.props.user!=null && ! chatStore.showChat" :show="true"/>
                </div>
                <div  v-if="!userStore.isMobile">
                    <VideoControls v-if="$page.props.user!=null" :show="true"/>
                </div>

            </div>

            <div v-if="userStore.isMobile">
                <ChatForStreamPageMobile v-if="$page.props.user!=null" :user="props.user"/>
            </div>
            <div v-if="!userStore.isMobile">
                <ChatForStreamPageStandard v-if="$page.props.user!=null" :user="props.user"/>
            </div>

            <ChannelsForStreamPage v-if="$page.props.user!=null" />


<!-- Video OSD (on screen display) when video is TopRight and the user is logged in. -->
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
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useStreamStore } from "@/Stores/StreamStore"
import { useChatStore } from "@/Stores/ChatStore"
import { useUserStore } from "@/Stores/UserStore"
import { ref } from 'vue'

import ChannelsForStreamPage from "@/Components/Channels/ChannelsForStreamPage"
import ChatForStreamPageStandard from "@/Components/Chat/ChatForStreamPageStandard"
import ChatForStreamPageMobile from "@/Components/Chat/ChatForStreamPageMobile"
import Login from "@/Components/Welcome/Login"
import VideoControls from "@/Components/VideoPlayer/VideoControls"

let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let chatStore = useChatStore()
let userStore = useUserStore()

videoPlayerStore.paused = false
chatStore.showChat = false
streamStore.showChannels = false
streamStore.showOSD = false
videoPlayerStore.showControls = false

let showLogin = ref(false)

let props = defineProps({
    src: String,
    user: Object,
    can: Object,
})

function backToPage() {
    videoPlayerStore.makeVideoTopRight();
    chatStore.showChat = false;
    streamStore.showOSD = false;
}

</script>

<!-- A note about audio Tracks. -->
<!-- https://github.com/videojs/http-streaming/blob/main/docs/multiple-alternative-audio-tracks.md -->
<!--The other property that does not have a mapping in the m3u8 is AudioTrack.kind.
It was decided that we would set the kind to main when default is set to true and
in other cases we set it to alternative unless the track has characteristics which
include public.accessibility.describes-video, in which case we set it to main-desc
(note that this kind indicates that the track is a mix of the main track and description,
so it can be played instead of the main track; a track with kind description only has
the description, not the main track).-->

<!-- Goal: see if we can play 2 audio tracks at the same time. And build a pop-up audio
mixer. This will lead into a feature that allows you to record directly through notTV
whatever it is you are watching/clicking through. A web3 video editor. -->
<!-- -tec21 Dec.4, 2022 -->


<script>
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore"
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
        const videoSourceType = videoPlayerStore.videoSourceType
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
                        type: videoSourceType
                    }
                ]
            }
        };
    }
};



</script>
