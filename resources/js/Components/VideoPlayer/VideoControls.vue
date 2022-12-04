<template>
    <Transition
        enter-from-class="opacity-0 scale-75"
        enter-to-class="opacity-100 scale-100"
        enter-active-class="transition duration-300"
        leave-active-class="transition duration-200"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-75"
    >

        <div v-if="show">

            <div v-if="videoPlayerStore.loggedIn">

                <div v-if="videoPlayerStore.fullPage">

                    <div class="absolute flex justify-center space-x-4 bottom-6 px-2 w-full z-50">
                        <button class="bg-gray-800 rounded-full p-2 hover:bg-gray-600" @click="videoPlayerStore.unmute()">UNMUTE</button>
                        <button class="bg-gray-800 rounded-full p-2 hover:bg-gray-600" @click="videoPlayerStore.back()">BACK</button>
                        <button class="bg-gray-800 rounded-full p-2 hover:bg-gray-600" @click="videoPlayerStore.pause()">PAUSE</button>
                        <button class="bg-gray-800 rounded-full p-2 hover:bg-gray-600" @click="videoPlayerStore.next()">NEXT</button>
                    </div>

                </div>


                <div v-if="!videoPlayerStore.fullPage">

                    <div class="absolute flex justify-between bottom-2 px-2 w-full z-50">
                        <button class="bg-gray-800 rounded-full p-2 hover:bg-gray-600" @click="videoPlayerStore.makeVideoFullPage()">BIG</button>
                        <button class="bg-gray-800 rounded-full p-2 hover:bg-gray-600" @click="videoPlayerStore.unmute()">UNMUTE</button>
                        <button class="bg-gray-800 rounded-full p-2 hover:bg-gray-600" @click="videoPlayerStore.back()">BACK</button>
                        <button class="bg-gray-800 rounded-full p-2 hover:bg-gray-600" @click="videoPlayerStore.pause()">PAUSE</button>
                        <button class="bg-gray-800 rounded-full p-2 hover:bg-gray-600" @click="videoPlayerStore.next()">NEXT</button>

                    </div>
                </div>


            </div>
        </div>

    </Transition>

</template>

<script setup>
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js"
let videoPlayerStore = useVideoPlayerStore()

defineProps({
    show: Boolean
});


</script>
