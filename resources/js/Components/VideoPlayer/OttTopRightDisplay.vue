<template>
    <div>

        <div v-if="videoPlayerStore.ott === 1" class="channels w-full h-full bg-green-800 p-2 overflow-y-scroll">

            <h1 class="text-xs font-semibold uppercase mb-3 w-full bg-green-900 text-white p-2">CHANNELS</h1>
            <div class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-white cursor-pointer border-b border-0.2 border-green-800" @click="changeChannel('one')">ONE</div>
            <div class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-white cursor-pointer border-b border-0.2 border-green-800" @click="changeChannel('pacific')">PACIFIC</div>
            <div class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-white cursor-pointer border-b border-0.2 border-green-800" @click="changeChannel('west')">WEST</div>
            <div class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-white cursor-pointer border-b border-0.2 border-green-800" @click="changeChannel('central')">CENTRAL</div>
            <div class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-white cursor-pointer border-b border-0.2 border-green-800" @click="changeChannel('ontario')">ONTARIO</div>
            <div class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-white cursor-pointer border-b border-0.2 border-green-800" @click="changeChannel('east')">EAST</div>
            <div class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-white cursor-pointer border-b border-0.2 border-green-800" @click="changeChannel('usa')">U.S.A</div>
            <div class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-white cursor-pointer border-b border-0.2 border-green-800" @click="changeChannel('world')">WORLD</div>

            <div class="mt-4">The channels above are our live channels, designed to best serve Canadians living across 5 different timezones.</div>

            <div class="mt-4">Channel one is our premiere promotional channel featuring handpicked content and creators from the notTV network.</div>

            <div class="mt-4">Categories will be turned into channels when more creators join notTV. E.g., News, Music, Talk, Education, etc.</div>
        </div>

        <div v-if="videoPlayerStore.ott === 2" class="now-playing w-full h-full bg-purple-800 p-2">
            <h1 class="text-xs font-semibold uppercase mb-3 w-full bg-purple-900 text-white p-2">NOW PLAYING INFO</h1>
            <div class="flex justify-between">
                <div>
                    <div><span class="text-xs uppercase">Name: </span><Link :href="`#`">{{ streamStore.name }}</Link></div>
                    <div><span class="text-xs uppercase">Description: </span>{{ streamStore.description }}</div>
                    <div><span class="text-xs uppercase">Team: </span><Link :href="`#`">{{ streamStore.teamName }}</Link></div>
                </div>

                <div><Link :href="`#`"><img :src="`/storage/images/EBU_Colorbars.svg.png`" alt="poster" class="h-16 w-12  object-cover hover:opacity-75 transition ease-in-out duration-150">
<!--                    <img :src="`/storage/images/${streamStore.posterUrl}`">-->

                </Link></div>
            </div>

            <div class="creators-section mt-6">
                <div class="creators-header w-full p-1 bg-purple-900 text-white uppercase text-xs">Creators</div>
                <div class="creators-list py-2">
                    &lt;Creators here&gt;
                </div>
            </div>

            <div class="creators-section mt-6">
                <div class="creators-header w-full p-1 bg-purple-900 text-white uppercase text-xs">Bonus Content</div>
                <div class="creators-list py-2">
                    &lt;Content here&gt;
                </div>
            </div>

            <div class="py-2 mt-24 text-sm uppercase">
                Copyright &lt;Team Name&gt; here.
            </div>


        </div>
        <div v-if="videoPlayerStore.ott === 3" class="now-playing w-full h-full bg-orange-800 p-2">
            <h1 class="text-xs font-semibold uppercase mb-3 w-full bg-orange-900 text-white p-2">PLAYLIST</h1>
            <div>
                Add a loop here to display the playlist... scrollable. If the current channel is the users_channel,
                display the playlist for the user. If the current channel is another channel (e.g., stream), display
                the stream_playlist.
            </div>
        </div>
        <div v-if="videoPlayerStore.ott === 4" class="now-playing w-full bg-indigo-800 px-2 pt-2 overflow-y-scroll ">
            <h1 class="text-xs font-semibold uppercase mb-3 w-full bg-indigo-900 text-white p-2">CHAT</h1>
            <div class="h-[calc(100vh-19rem)] w-full overflow-y-scroll">
                <VideoOTTChat :user="props.user"/>
            </div>
        </div>
        <div v-if="videoPlayerStore.ott === 5" class="now-playing w-full h-full bg-yellow-500 text-black p-2">
            <h1 class="text-xs font-semibold uppercase mb-3 w-full bg-yellow-600 text-black p-2">FILTERS</h1>
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
    if (name==='one') {
        let source = 'mist1pull1'
        videoPlayerStore.videoName = 'notTV One'
        streamStore.currentChannel = 'notTV One'
        playVideo(source)
    }
    if (name==='pacific') {
        let source = 'mist1pull2'
        videoPlayerStore.videoName = 'notTV Pacific'
        streamStore.currentChannel = 'notTV Pacific'
        playVideo(source)
    }
    if (name==='west') {
        let source = 'mist1pull3'
        videoPlayerStore.videoName = 'notTV West'
        streamStore.currentChannel = 'notTV West'
        videoPlayerStore.loadNewSourceFromMist(source)
    }
    if (name==='central') {
        let source = 'mist1pull4'
        videoPlayerStore.videoName = 'notTV Central'
        streamStore.currentChannel = 'notTV Central'
        videoPlayerStore.loadNewSourceFromMist(source)
    }
    if (name==='ontario') {
        let source = 'mist1pull5'
        videoPlayerStore.videoName = 'notTV Ontario'
        streamStore.currentChannel = 'notTV Ontario'
        videoPlayerStore.loadNewSourceFromMist(source)
    }
    if (name==='east') {
        let source = 'mist1pull6'
        videoPlayerStore.videoName = 'notTV East'
        streamStore.currentChannel = 'notTV East'
        videoPlayerStore.loadNewSourceFromMist(source)
    }
    if (name==='usa') {
        let source = 'mist1pull7'
        videoPlayerStore.videoName = 'notTV U.S.A.'
        streamStore.currentChannel = 'notTV U.S.A.'
        videoPlayerStore.loadNewSourceFromMist(source)
    }
    if (name==='world') {
        let source = 'mist1pull8'
        videoPlayerStore.videoName = 'notTV World'
        streamStore.currentChannel = 'notTV World'
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
