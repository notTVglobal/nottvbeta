<template>
    <Transition
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        enter-active-class="transition duration-300"
        leave-active-class="transition duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div class="chatContainer">
            <div v-if="chat.show" class="fixed bg-blue-800 bottom-0 right-0 bg-opacity-80 text-center p-10 z-40 w-1/4 h-2/5 ">
    <!--            <div class="absolute top-16 left-0 p-5 drop-shadow" v-if="videoPlayer.fullPage"><span class="text-xs uppercase pr-2">CHAT BOX </span><span class="font-semibold">Chat goes here.</span></div>-->
    <!--            <div v-if="!videoPlayer.fullPage" class="bg-gray-800 px-2"><span class="text-xs uppercase pr-2">CHAT BOX </span><span class="font-semibold">Chat goes here</span></div>-->
                <div class="right-0 pt-6 overflow-hidden p-10 chat text-white">
                    <div>CHAT BOX</div>
                    <div class="chatBoxes text-white opacity-100">Chat goes here. It needs to be on top of the video player as an overlay on desktops and tablets, but below the video on mobile devices.</div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script setup>
import { useChatStore } from "@/Stores/ChatStore";

let chat = useChatStore();
chat.show = false;

</script>

<style>
.chatContainer {
    width: 24em;
}
</style>
