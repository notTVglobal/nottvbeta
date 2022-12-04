<template>
    <div>

        <div v-show="videoPlayerStore.ott === 1" class="channels w-full h-full bg-green-800 p-2">
            <h1 class="text-xs font-semibold uppercase">CHANNELS</h1>
            <div>
                Display the channels list here.
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
        <div v-show="videoPlayerStore.ott === 4" class="now-playing w-full bg-indigo-800 px-2 pt-2 overflow-y-scroll ">
            <h1 class="text-xs font-semibold uppercase">CHAT</h1>
            <div class="h-full w-full overflow-y-scroll">
                <VideoOTTChat :user="props.user"/>
            </div>
        </div>
        <div v-show="videoPlayerStore.ott === 5" class="now-playing w-full h-full bg-yellow-500 text-black p-2">
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

</script>
