<template>
    <Transition
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        enter-active-class="transition duration-300"
        leave-active-class="transition duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
<!--&lt;!&ndash; this transition doesn't seem to be working&ndash;&gt;-->
<!--        <div v-if="show">-->

<!-- Video Welcome Controls -->

                    <div :class="videoControlsClass">
                        <button
                            class="text-xs md:text-md  md:text-md bg-gray-800 rounded-full p-2 hover:bg-gray-600"
                            @click="videoPlayerStore.fullscreen()">
                            FULLSCREEN</button>

                        <button v-if="videoPlayerStore.muted===true"
                                class="text-xs md:text-md  md:text-md bg-gray-800 rounded-full p-2 hover:bg-gray-600"
                                @click="videoPlayerStore.unmute()">
                            UNMUTE</button>

                        <button v-if="videoPlayerStore.muted===false"
                                class="text-xs md:text-md  bg-gray-800 rounded-full p-2 hover:bg-gray-600"
                                @click="videoPlayerStore.mute()">
                            MUTE</button>

                        <button v-if="!videoPlayerStore.paused"
                                class="text-xs md:text-md bg-gray-800 rounded-full p-2 hover:bg-gray-600"
                                @click="videoPlayerStore.pause()">
                            PAUSE</button>

                        <button v-if="videoPlayerStore.paused"
                                class="text-xs md:text-md bg-gray-800 rounded-full p-2 hover:bg-gray-600"
                                @click="videoPlayerStore.play()">
                            PLAY</button>

                    </div>

    </Transition>
</template>

<script setup>
import { computed } from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore"
import { useUserStore } from "@/Stores/UserStore"

let videoPlayerStore = useVideoPlayerStore()
let chatStore = useChatStore()
let userStore = useUserStore()

defineProps({
    show: Boolean
});

function backToPage() {
    // videoPlayerStore.makeVideoTopRight();
    // chatStore.showChat = false;
    // streamStore.showOSD = false;
    window.history.back()
}


const videoControlsClass = computed(() => ({
    videoControlsWelcomeMobile: userStore.isMobile,
    videoControlsWelcomeStandard: !userStore.isMobile
}))


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


</style>
