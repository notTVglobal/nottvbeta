<template>
    <Transition
        enter-from-class="opacity-0 scale-125"
        enter-to-class="opacity-100 scale-100"
        enter-active-class="transition duration-300"
        leave-active-class="transition duration-200"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-125"
    >
        <div v-if="welcomeStore.showOverlay===true">
                    <div class="grid md:grid-cols-1 grid-cols-1 align-items-center -mt-32">
                        <JetApplicationLogo class="block md:w-auto p-10"/>
                            <div class="my-4 text-center">
<!--                                <button-->
<!--                                    class="text-2xl font-semibold bg-green-500 hover:bg-green-600 text-white px-5 py-3 rounded disabled:bg-gray-400"-->
<!--                                    @click="watchNow"-->
<!--                                ><font-awesome-icon icon="fa-solid fa-play" /> Watch Now  </button>-->


                            </div>
<!--                            <div class="my-2 text-center">OR</div>-->
<!--                            <div class="text-center font-semibold text-2xl px-5 py-3">SCROLL DOWN</div>-->
                            <div class="text-center font-semibold text-2xl px-5 py-3">Returning Soon!</div>

                    </div>
                </div>

    </Transition>

</template>

<script setup>
import { useWelcomeStore } from "@/Stores/WelcomeStore.js"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import JetApplicationLogo from '@/Jetstream/ApplicationLogo.vue';

let welcomeStore = useWelcomeStore()
let videoPlayerStore = useVideoPlayerStore()

defineProps({
    show: Boolean
});

function watchNow(){
    welcomeStore.showOverlay = false;
    videoPlayerStore.play();
    videoPlayerStore.unmute();
    // transition out welcomeOverlay
    // unmute video
    // play video
}

</script>
