<template>
<!--    <Transition-->
<!--        enter-from-class="opacity-0"-->
<!--        enter-to-class="opacity-100"-->
<!--        enter-active-class="transition duration-300"-->
<!--        leave-active-class="transition duration-200"-->
<!--        leave-from-class="opacity-100"-->
<!--        leave-to-class="opacity-0"-->
<!--    >-->
<!--&lt;!&ndash; this transition doesn't seem to be working&ndash;&gt;-->
<!--        <div v-if="show">-->
<div>
<!-- Video FullPage Controls -->
<!--    <div class="absolute flex justify-center space-x-4 bottom-12 right-px-2 w-full z-50"-->

<!--    tec21: 2024-01-15 This is old. This was the first version of the buttons.. temporary. -->

                    <div class="videoControlsMobile">
                            <button
                                class="text-xs md:text-md md:text-md bg-gray-600 rounded-full p-2 hover:bg-gray-600"
                                @click="videoPlayerStore.fullscreen()">
                                FULLSCREEN</button>

                            <button v-if="videoPlayerStore.muted===true"
                                    class="text-xs md:text-md md:text-md bg-gray-600 rounded-full p-2"
                                    @click="videoPlayerStore.unmute()">
                                UNMUTE</button>

                            <button v-if="videoPlayerStore.muted===false"
                                    class="text-xs md:text-md bg-gray-600 rounded-full p-2"
                                    @click="videoPlayerStore.mute()">
                                MUTE</button>

                            <button
                                class="text-xs md:text-md bg-gray-600 rounded-full p-2 cursor-not-allowed"
                                @click="videoPlayerStore.back()"
                                disabled >
                                PREV</button>

                            <button v-if="!videoPlayerStore.paused"
                                    class="text-xs md:text-md bg-gray-600 rounded-full p-2"
                                    @click="videoPlayerStore.pause()">
                                PAUSE</button>

                            <button v-if="videoPlayerStore.paused"
                                    class="text-xs md:text-md bg-gray-600 rounded-full p-2"
                                    @click="videoPlayerStore.play()">
                                PLAY</button>

                            <button
                                class="text-xs md:text-md bg-gray-600 rounded-full p-2 cursor-not-allowed"
                                @click="videoPlayerStore.next()"
                                disabled >
                                NEXT</button>
                    </div>

                    <div class="backToPage drop-shadow">
                        <button @click="backToPage" class="p-2 bg-gray-600 hover:bg-gray-800 text-white" >Back to Page</button>
                    </div>


        </div>

<!--    </Transition>-->
</template>

<script setup>
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js"
import {useChatStore} from "@/Stores/ChatStore"
import {useUserStore} from "@/Stores/UserStore"
import {usePage} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";

let videoPlayerStore = useVideoPlayerStore()
let chatStore = useChatStore()
let userStore = useUserStore()

defineProps({
    show: Boolean
});

let showChat = chatStore.showChat;
let isMobile = userStore.isMobile;

function backToPage() {
    let urlPrev = usePage().props.value.urlPrev
    if (urlPrev !== 'empty') {
        Inertia.visit(urlPrev)
    }
}

</script>

<style scoped>
.videoControlsStandard {
    margin-left: 0;
    margin-right: 0;
    width: 100%;
    justify-content: center;
}

</style>
