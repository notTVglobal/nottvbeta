<template>
    <Transition
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        enter-active-class="transition duration-3000"
        leave-active-class="transition duration-2000"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >


        <div v-if="videoPlayerStore.showChannels" class="channelsForStreamPageStandard w-96 max-w-[12rem] bottom-0 bg-opacity-80 bg-green-600 text-sm text-white pb-12
                chat-mask overflow-y-auto scroll-smooth hover:scroll-auto break-words">

<!--        Previous version (full screen background for Ott) -->
<!--        <div v-if="videoPlayerStore.showChannels" class="channelsForStreamPageStandard max-w-screen h-screen left-0 bottom-0 sm:bottom-8 bg-opacity-80 bg-green-600 text-sm text-white pb-12-->
<!--            chat-mask overflow-y-auto scroll-smooth hover:scroll-auto break-words">-->


                <div class="flex flex-col p-5 mt-10">
                    <div class="text-3xl text-center font-semibold uppercase mb-3 w-full bg-green-900 text-white p-2">CHANNELS</div>
                </div>

                <div class="absolute">
                    <div class="relative h-[calc(h-100%-16rem)] top-0 pr-5 space-y-2">

                            <div class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md"
                                 :class="{ active:videoPlayerStore.currentChannelName==='one' }"
                                 @click="videoPlayerStore.changeChannel('one')">ONE</div>
                            <div class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md"
                                 :class="{ active:videoPlayerStore.currentChannelName==='pacific' }"
                                 @click="videoPlayerStore.changeChannel('pacific')">PACIFIC</div>
                            <div class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md"
                                 :class="{ active:videoPlayerStore.currentChannelName==='west' }"
                                 @click="videoPlayerStore.changeChannel('west')">WEST</div>
                            <div class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md"
                                 :class="{ active:videoPlayerStore.currentChannelName==='central' }"
                                 @click="videoPlayerStore.changeChannel('central')">CENTRAL</div>
                            <div class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md"
                                 :class="{ active:videoPlayerStore.currentChannelName==='ontario' }"
                                 @click="videoPlayerStore.changeChannel('ontario')">ONTARIO</div>
                            <div class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md"
                                 :class="{ active:videoPlayerStore.currentChannelName==='east' }"
                                 @click="videoPlayerStore.changeChannel('east')">EAST</div>
                            <div class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md"
                                 :class="{ active:videoPlayerStore.currentChannelName==='usa' }"
                                 @click="videoPlayerStore.changeChannel('usa')">U.S.A</div>
                            <div class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md"
                                 :class="{ active:videoPlayerStore.currentChannelName==='world' }"
                                 @click="videoPlayerStore.changeChannel('world')">WORLD</div>

                        <div class="px-4 py-2 bg-green-700 border-b border-0.2 border-green-800 shadow-md space-y-2">
                            <div class="">The channels above are our live channels, designed to best serve Canadians living across 5 different timezones.</div>
                            <div class="">Channel one is our premiere promotional channel featuring handpicked content and creators from the notTV network.</div>
                            <div class="">Categories will be turned into channels when more creators join notTV. E.g., News, Music, Talk, Education, etc.</div>
                        </div>


                    </div>

                </div>







            <button v-touch="()=>videoPlayerStore.toggleChannels()"
                    v-if="videoPlayerStore.showChannels" class="fixed bottom-5 right-32 opacity-100 w-15 h-15 p-4 rounded-full
                    text-gray-50 bg-green-900 hover:bg-green-800 grid justify-center content-center
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

<style scoped>
.active {
    background-color: darkgreen;
}

</style>
