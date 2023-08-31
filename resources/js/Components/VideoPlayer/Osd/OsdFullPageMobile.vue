<template>
<div>
    <div class="videoFullPageMobileOSD absolute w-full left-0 p-5 drop-shadow z-50">
        <div v-if="videoPlayerStore.videoName !== null">
            <span class="text-xs uppercase pr-2">Now playing: </span>
            <span class="font-semibold">{{ videoPlayerStore.videoName }}</span>
        </div>
        <div v-if="channelStore.currentChannelName !== null">
            <span class="text-xs uppercase pr-2">Channel: </span>
            <span class="text-xs font-semibold">{{ channelStore.currentChannelName }}</span>
        </div>

        <div class="flex flex-row space-x-1 absolute pt-1 left-0 pl-5 drop-shadow z-50">
            <div v-if="channelStore.isLive" class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-white bg-opacity-80 bg-red-800 uppercase last:mr-0 mr-1">
                live
            </div>
            <div v-if="channelStore.currentChannelId !== null">
                <CurrentViewers />
            </div>
        </div>
    </div>

</div>
</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useStreamStore } from "@/Stores/StreamStore"
import { useUserStore } from "@/Stores/UserStore"
import { useChatStore } from "@/Stores/ChatStore"
import { useChannelStore } from "@/Stores/ChannelStore"
import CurrentViewers from "@/Components/VideoPlayer/CurrentViewers/CurrentViewers";

let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let userStore = useUserStore()
let chatStore = useChatStore()
let channelStore = useChannelStore()

let isMobile = userStore.isMobile;
let videoName = videoPlayerStore.videoName;
let isLive = streamStore.isLive;
let currentPage = videoPlayerStore.currentPage;

</script>
