<template>
    <Transition
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        enter-active-class="transition duration-3000"
        leave-active-class="transition duration-2000"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >


        <div v-if="videoPlayerStore.showChannels" class="channelsForStreamPageStandard max-w-screen h-screen left-0 bottom-0 sm:bottom-8 bg-opacity-80 bg-blue-600 text-sm text-white pb-12
            chat-mask overflow-y-auto scroll-smooth hover:scroll-auto break-words">

        <div class=" top-60">CHANNELS HERE</div>


            <button v-touch="()=>videoPlayerStore.toggleChannels()"
                    v-if="videoPlayerStore.showChannels" class="fixed top-20 right-6 opacity-100 w-15 h-15 p-4 rounded-full bg-orange-800
                    text-gray-50 hover:bg-blue-800 hover:text-blue-200 grid justify-center content-center
                    cursor-pointer font-semibold text-xs">
                CLOSE CHANNELS
            </button>
        </div>

    </Transition>
</template>

<script setup>
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore";

let videoPlayerStore = useVideoPlayerStore();

defineProps({
    user: Object,
})



</script>
