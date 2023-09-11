<template>
    <Transition
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        enter-active-class="transition duration-300"
        leave-active-class="transition duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >

        <!-- Video OSD (on screen display) when video is TopRight and the user is logged in. -->
        <div class="absolute flex justify-between top-0 drop-shadow pt-3 px-10 lg:px-2 w-full z-50">
            <div v-if="videoPlayerStore.videoName !== ''">
                <span class="text-xs uppercase pr-2">Now playing: </span>
                <span class="font-semibold text-xs">{{ videoPlayerStore.videoName }}</span>
            </div>
            <div v-if="channelStore.currentChannelName !== '' || channelStore.currentChannelName !== null">
                <span class="text-xs uppercase pr-2">Channel: </span>
                <span class="text-xs font-semibold">{{ channelStore.currentChannelName }}</span>
            </div>
            <div v-if="channelStore.isLive" class="absolute pt-6 left-0 pl-10 lg:pl-2 drop-shadow z-50 w-full">
                <div class="flex justify-between">
                    <div>
                            <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-white bg-opacity-80 bg-red-800 uppercase last:mr-0 mr-1">
                            live
                            </span>
                    </div>

                </div>
            </div>
        </div>

    </Transition>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useStreamStore } from "@/Stores/StreamStore"
import { useChannelStore } from "@/Stores/ChannelStore"

let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let channelStore = useChannelStore()

defineProps({
    show: Boolean,
})

</script>
