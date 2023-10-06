<template>
    <div class="osdFullPage">{{ showStore.name }}<span v-if="showStore.episodeName">: {{ showStore.episodeName }}</span>
        <div v-if="channelStore.currentChannelName">
            <span class="text-xs uppercase pr-2">Channel: </span>
            <span class="text-xs font-semibold">{{ channelStore.currentChannelName }}</span>
        </div>
        <div v-if="videoPlayerStore.videoName !== ''">
            <span class="text-xs uppercase pr-2">Now playing: </span>
            <span class="font-semibold"><Link :href="`${videoPlayerStore.nowPlayingUrl}`">{{ videoPlayerStore.videoName }}</Link></span>
        </div>
        <div class="drop-shadow">
            <span v-if="channelStore.isLive" class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-white bg-opacity-80 bg-red-800 last:mr-0 mr-1 mb-1">
                live
            </span>
            <div v-if="channelStore.currentChannelId">
                <CurrentViewers />
            </div>
        </div>
    </div>
</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useStreamStore } from "@/Stores/StreamStore"
import { useShowStore } from "@/Stores/ShowStore"
import { useChannelStore } from "@/Stores/ChannelStore"
import CurrentViewers from "@/Components/VideoPlayer/CurrentViewers/CurrentViewers.vue";

let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let showStore = useShowStore()
let channelStore = useChannelStore()

</script>
