<template>
    <div>
        <div class="osdFullPageTop">
            <div class="flex justify-between">
                <div>
                    <div v-if="channelStore.currentChannelId" class="drop-shadow">
                        <CurrentViewers />
                    </div>
                </div>
                <div>
                    <span v-if="channelStore.isLive" class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-white bg-opacity-80 drop-shadow bg-red-800 last:mr-0 mr-1 mb-1">
                        live
                    </span>
                </div>
            </div>
        </div>
        <div class="osdFullPageBottom">
            <div v-if="showStore.name" class="break-words">
                <div class="text-xs uppercase text-gray-500">Now Playing</div>
                <div class="uppercase"><Link :href="`${showStore.url}`">{{ showStore.name }}</Link></div>
                <div class="text-sm uppercase">
                    <span v-if="showStore.episodeName"><Link :href="`${showStore.episodeUrl}`">{{ showStore.episodeName }}</Link></span>
                </div>

            </div>
            <div v-if="channelStore.currentChannelName" class="break-words">
                <span class="text-xs uppercase pr-2">Channel: </span>
                <span class="text-xs font-semibold">{{ channelStore.currentChannelName }}</span>
            </div>
            <div v-if="videoPlayerStore.nowPlayingName !== ''">
                <div class="break-words">
                    <span class="text-xs uppercase pr-2">Now playing: </span>
                    <span class="font-semibold">{{ videoPlayerStore.nowPlayingType }}</span>
                </div>
                <div class="break-words">
                    <span class="text-xs uppercase pr-2">Filename: </span>
                    <span class="font-semibold"><Link :href="`${videoPlayerStore.nowPlayingUrl}`">{{ videoPlayerStore.nowPlayingName }}</Link></span>
                </div>
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
