<template>
<div>
    <div class="osdFullPage">
        <div v-if="channelStore.currentChannelName">
            <span class="text-xs uppercase pr-2">Channel: </span>
            <span class="text-xs font-semibold">{{ channelStore.currentChannelName }}</span>
        </div>
        <div v-if="videoPlayerStore.videoName !== ''">
            <span class="text-xs uppercase pr-2">Now playing: </span>
            <span class="font-semibold">{{ videoPlayerStore.videoName }}</span>
        </div>
        <div class="drop-shadow z-50">
            <span v-if="channelStore.isLive" class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-white bg-opacity-80 bg-red-800 last:mr-0 mr-1">
                live
            </span>
            <div v-if="channelStore.currentChannelId">
                <CurrentViewers />
            </div>
        </div>
    </div>

</div>
</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useStreamStore } from "@/Stores/StreamStore"
import { useChannelStore } from "@/Stores/ChannelStore"
import CurrentViewers from "@/Components/VideoPlayer/CurrentViewers/CurrentViewers.vue";

let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let channelStore = useChannelStore()

</script>
