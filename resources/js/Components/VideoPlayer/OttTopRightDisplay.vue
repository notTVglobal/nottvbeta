<template>
    <div>

        <div v-if="videoPlayerStore.ott === 1" class="channels w-full h-full bg-green-800 p-2 overflow-y-scroll">
            <h1 class="text-xs font-semibold uppercase">CHANNELS</h1>
            <div>
                Display the channels list here.
            </div>
            <div class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-white cursor-pointer border-b border-0.2 border-green-800" @click="changeChannel('live')">LIVE</div>
            <div class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-white cursor-pointer border-b border-0.2 border-green-800" @click="changeChannel('stream')">STREAM</div>
            <div class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-white cursor-pointer border-b border-0.2 border-green-800" @click="changeChannel('vmixsource03')">VMIX SOURCE 03</div>
            <div class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-white cursor-pointer border-b border-0.2 border-green-800" @click="changeChannel('notnowpilotpull')">NOT NOW</div>
        </div>

        <div v-if="videoPlayerStore.ott === 2" class="now-playing w-full h-full bg-purple-800 p-2">
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
        <div v-if="videoPlayerStore.ott === 3" class="now-playing w-full h-full bg-orange-800 p-2">
            <h1 class="text-xs font-semibold uppercase">PLAYLIST</h1>
            <div>
                Add a loop here to display the playlist... scrollable. If the current channel is the users_channel,
                display the playlist for the user. If the current channel is another channel (e.g., stream), display
                the stream_playlist.
            </div>
        </div>
        <div v-if="videoPlayerStore.ott === 4" class="now-playing w-full bg-indigo-800 px-2 pt-2 overflow-y-scroll ">
            <h1 class="text-xs font-semibold uppercase">CHAT</h1>
            <div class="h-[calc(100vh-19rem)] w-full overflow-y-scroll">
                <VideoOTTChat :user="props.user"/>
            </div>
        </div>
        <div v-if="videoPlayerStore.ott === 5" class="now-playing w-full h-full bg-yellow-500 text-black p-2">
            <h1 class="text-xs font-semibold uppercase">FILTERS</h1>
            <div>
                Coming Soon!
            </div>
        </div>
    </div>
</template>

<script setup>
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js"
import {useStreamStore} from "@/Stores/StreamStore";
import { useChatStore } from "@/Stores/ChatStore.js"
import VideoOTTChat from "@/Components/VideoPlayer/VideoOTTChat.vue";

let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let chat = useChatStore()

let props = defineProps ({
    user: Object,
})

function changeChannel(name) {
    if (name==='live') {
        let source = 'vmixlive'
        videoPlayerStore.videoName = 'vMix Live'
        streamStore.currentChannel = 'vMix Live'
        playVideo(source)
    }
    if (name==='vmixsource03') {
        let source = 'vmixsource03pull'
        videoPlayerStore.videoName = 'vMix Source 03 Pull'
        streamStore.currentChannel = 'vMix Source 03'
        playVideo(source)
    }
    if (name==='stream') {
        let source = 'labyrinth'
        videoPlayerStore.videoName = 'Labyrinth'
        streamStore.currentChannel = 'Stream'
        videoPlayerStore.loadNewSourceFromMist(source)
    }
    if (name==='notnowpilotpull') {
        let source = 'notnowpilotpull'
        videoPlayerStore.videoName = 'NotNow.tv Pilot'
        streamStore.currentChannel = 'NotNow'
        videoPlayerStore.loadNewSourceFromMist(source)
    }
    // if (name==='live') {
    //     videoPlayerStore.videoSource = "https://mist2.not.tv/hls/vmixlive/index.m3u8"
    //     videoPlayerStore.videoSourceType = "application/x-mpegURL"
    //     videoPlayerStore.videoName = 'vMix Live'
    //     streamStore.currentChannel = 'Live'
    //     playVideo()
    // }
    // if (name==='stream') {
    //     videoPlayerStore.videoSource = "https://mist2.not.tv/hls/labyrinth/index.m3u8"
    //     videoPlayerStore.videoSourceType = "application/x-mpegURL"
    //     videoPlayerStore.videoName = 'Labyrinth'
    //     streamStore.currentChannel = 'Stream'
    //     videoPlayerStore.loadNewSource()
    // }
}

let playVideo = (source) => {
    videoPlayerStore.loadNewSourceFromMist(source)
}

</script>
