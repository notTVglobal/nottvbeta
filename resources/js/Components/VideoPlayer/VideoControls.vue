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

<!-- Video FullPage Controls -->
                <div v-if="videoPlayerStore.fullPage && ! chatStore.showChat">
                    <div class="videoControlsStandard absolute flex justify-center space-x-4 bottom-12 right-px-2 w-full z-50"
                        :class="{'right-44': chatStore.showChat, videoControlsMobile: userStore.isMobile}">

                        <button v-if="videoPlayerStore.muted===true"
                                class="text-xs md:text-md  md:text-md bg-gray-800 rounded-full p-2 hover:bg-gray-600"
                                @click="videoPlayerStore.unmute()">
                            UNMUTE</button>

                        <button v-if="videoPlayerStore.muted===false"
                                class="text-xs md:text-md  bg-gray-800 rounded-full p-2 hover:bg-gray-600"
                                @click="videoPlayerStore.mute()">
                            MUTE</button>

                        <button
                            class="text-xs md:text-md  bg-gray-800 rounded-full p-2 hover:bg-gray-600 cursor-not-allowed"
                            @click="videoPlayerStore.back()"
                            disabled >
                            BACK</button>

                        <button v-if="!videoPlayerStore.paused"
                                class="text-xs md:text-md  bg-gray-800 rounded-full p-2 hover:bg-gray-600"
                                @click="videoPlayerStore.pause()">
                            PAUSE</button>

                        <button v-if="videoPlayerStore.paused"
                                class="text-xs md:text-md  bg-gray-800 rounded-full p-2 hover:bg-gray-600"
                                @click="videoPlayerStore.play()">
                            PLAY</button>

                        <button
                            class="text-xs md:text-md  bg-gray-800 rounded-full p-2 hover:bg-gray-600 cursor-not-allowed"
                            @click="videoPlayerStore.next()"
                            disabled >
                            NEXT</button>

                    </div>
                </div>

<!-- Video TopRight Controls -->
                <div v-if="!videoPlayerStore.fullPage">
                    <div class="absolute flex justify-between bottom-2 px-2 w-full z-50">

                        <button
                                class="text-xs md:text-md bg-gray-800 rounded-full p-2 hover:bg-gray-600"
                                @click="videoPlayerStore.makeVideoFullPage()">
                            BIG</button>

                        <button v-if="videoPlayerStore.muted===true"
                                class="text-xs md:text-md bg-gray-800 rounded-full p-2 hover:bg-gray-600"
                                @click="videoPlayerStore.unmute()">
                            UNMUTE</button>

                        <button v-if="videoPlayerStore.muted===false"
                                class="text-xs md:text-md bg-gray-800 rounded-full p-2 hover:bg-gray-600"
                                @click="videoPlayerStore.mute()">
                            MUTE</button>

                        <button
                            class="text-xs md:text-md bg-gray-800 rounded-full p-2 hover:bg-gray-600 cursor-not-allowed"
                            @click="videoPlayerStore.back()"
                            disabled >
                            BACK</button>

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
/*.videoControlsMobile {*/
/*    bottom: 12rem !important;*/
/*    margin-left: 0;*/
/*    margin-right: 0;*/
/*    padding-bottom: 0rem;*/
/*    right:0px;*/
/*    width: 100%;*/
/*    justify-content: center;*/
/*}*/
.videoControlsStandard {
    margin-left: 0;
    margin-right: 0;
    width: 100%;
    justify-content: center;
}

</style>
