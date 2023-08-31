<template>
    <Transition
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        enter-active-class="transition duration-300"
        leave-active-class="transition duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >

        <div v-if="show" class="flex justify-center">

<!-- Video TopRight Controls -->
                    <div class="absolute flex justify-between bottom-2 px-2 w-96 z-50">

                        <Link v-touch="()=>(route('stream'))" @click="videoPlayerStore.makeVideoFullPage()" :href="route('stream')" :active="route().current('stream')">
                        <button class="text-xs md:text-md bg-gray-800 rounded-full p-2 hover:bg-gray-600">
                            BIG</button></Link>

                        <button v-if="videoPlayerStore.muted"
                                class="text-xs md:text-md bg-gray-800 rounded-full p-2 hover:bg-gray-600"
                                @click="videoPlayerStore.unmute()">
                            UNMUTE</button>

                        <button v-if="!videoPlayerStore.muted"
                                class="text-xs md:text-md bg-gray-800 rounded-full p-2 hover:bg-gray-600"
                                @click="videoPlayerStore.mute()">
                            MUTE</button>

                        <button
                            class="text-xs md:text-md bg-gray-800 rounded-full p-2 hover:bg-gray-600 cursor-not-allowed"
                            @click="videoPlayerStore.back()"
                            disabled >
                            PREV</button>

                        <button v-if="!videoPlayerStore.paused"
                                class="text-xs md:text-md bg-gray-800 rounded-full p-2 hover:bg-gray-600"
                                @click="videoPlayerStore.pause()">
                            PAUSE</button>

                        <button v-if="videoPlayerStore.paused"
                                class="text-xs md:text-md bg-gray-800 rounded-full p-2 hover:bg-gray-600"
                                @click="videoPlayerStore.play()">
                            PLAY</button>

                        <button
                            class="text-xs md:text-md bg-gray-800 rounded-full p-2 hover:bg-gray-600 cursor-not-allowed"
                            @click="videoPlayerStore.next()"
                            disabled >
                            NEXT</button>


                    </div>


        </div>

    </Transition>
</template>

<script setup>
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js"
import {useChatStore} from "@/Stores/ChatStore"
import {useUserStore} from "@/Stores/UserStore"

let videoPlayerStore = useVideoPlayerStore()
let chatStore = useChatStore()
let userStore = useUserStore()

defineProps({
    show: Boolean
});


</script>

<style scoped>
.videoControlsMobile {
    bottom: 12rem !important;
    margin-left: 0;
    margin-right: 0;
    padding-bottom: 0rem;
    right:0px;
    width: 100%;
    justify-content: center;
}
.videoControlsStandard {
    margin-left: 0;
    margin-right: 0;
    width: 100%;
    justify-content: center;
}

</style>
