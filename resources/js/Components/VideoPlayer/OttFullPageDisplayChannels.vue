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

                        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:disabledChannel disabled:text-gray-300 disabled:cursor-not-allowed"
                                :class="{ activeChannel:videoPlayerStore.currentChannelName==='one' }"
                                @click="videoPlayerStore.changeChannel('one')">
                            ONE</button>
                        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                                :class="{ activeChannel:videoPlayerStore.currentChannelName==='ambient' }"
                                @click="videoPlayerStore.changeChannel('ambient')">
                            AMBIENT</button>
                        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                                :class="{ activeChannel:videoPlayerStore.currentChannelName==='news' }"
                                @click="videoPlayerStore.changeChannel('news')">
                            NEWS</button>
                        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                                :class="{ activeChannel:videoPlayerStore.currentChannelName==='talk' }"
                                @click="videoPlayerStore.changeChannel('talk')">
                            TALK</button>
                        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                                :class="{ activeChannel:videoPlayerStore.currentChannelName==='documentary' }"
                                @click="videoPlayerStore.changeChannel('documentary')">
                            DOCUMENTARY</button>
                        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                                :class="{ activeChannel:videoPlayerStore.currentChannelName==='music' }"
                                @click="videoPlayerStore.changeChannel('music')">
                            MUSIC</button>
                        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                                :class="{ activeChannel:videoPlayerStore.currentChannelName==='drama' }"
                                @click="videoPlayerStore.changeChannel('drama')">
                            DRAMA</button>
                        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                                :class="{ activeChannel:videoPlayerStore.currentChannelName==='comedy' }"
                                @click="videoPlayerStore.changeChannel('comedy')">
                            COMEDY</button>
                        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                                :class="{ activeChannel:videoPlayerStore.currentChannelName==='education' }"
                                @click="videoPlayerStore.changeChannel('education')">
                            EDUCATION</button>
                        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                                :class="{ activeChannel:videoPlayerStore.currentChannelName==='spirituality' }"
                                @click="videoPlayerStore.changeChannel('spirituality')">
                            SPIRITUALITY</button>
                        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                                disabled
                                :class="{ activeChannel:videoPlayerStore.currentChannelName==='reality' }"
                                @click="videoPlayerStore.changeChannel('reality')">
                            REALITY</button>
                        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                                disabled
                                :class="{ activeChannel:videoPlayerStore.currentChannelName==='variety' }"
                                @click="videoPlayerStore.changeChannel('variety')">
                            VARIETY</button>
                        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                                disabled
                                :class="{ activeChannel:videoPlayerStore.currentChannelName==='sports' }"
                                @click="videoPlayerStore.changeChannel('sports')">
                            SPORTS</button>
                        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                                disabled
                                :class="{ activeChannel:videoPlayerStore.currentChannelName==='kids' }"
                                @click="videoPlayerStore.changeChannel('kids')">
                            KIDS</button>
                        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                                :class="{ activeChannel:videoPlayerStore.currentChannelName==='local' }"
                                @click="videoPlayerStore.changeChannel('local')">
                            LOCAL</button>
                        <button class="w-full px-4 py-2 bg-green-700 hover:bg-green-500 text-xl text-white text-center cursor-pointer border-b border-0.2 border-green-800 shadow-md
                                disabled:bg-gray-600 disabled:text-gray-300 disabled:cursor-not-allowed"
                                disabled
                                :class="{ activeChannel:videoPlayerStore.currentChannelName==='world' }"
                                @click="videoPlayerStore.changeChannel('world')">
                            WORLD</button>

                        <div class="px-4 pt-2 pb-24 bg-green-700 border-b border-0.2 border-green-800 shadow-md space-y-2">
                            <div class="">Channel One is our premiere promotional channel featuring handpicked content and creators from the notTV network.</div>
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
.activeChannel {
    background-color: darkgreen;
    color: #ffcc66;
    border-right-color: #ffcc66;
    border-right-width: 2px;
}
/* tec21: I set a disabledChannel class in the app.css file .. trying to get a specific colour of
            green on the background of a disabled button. But this can wait until there is more logic
            built in to the channelController to manage channels. */

</style>
