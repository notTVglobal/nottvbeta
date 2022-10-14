<template>
    <Head title="Schedule" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-start flex flex-col justify-start w-50 h-screen w-3/4 px-10 break-words overflow-y-scroll ">
        <div class="fixed flex flex-col w-64 p-5 mb-10 bg-blue-400 ">
            <div class="text-3xl font-semibold">Chat</div>
        </div>
            <div class="mt-28 pl-32 pb-32">
                <ChatContainer />
            </div>

    </div>
</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"
import {onMounted, ref, watch} from "vue";
import InputMessage from "@/Components/Chat/InputMessage"
import ChatMessages from "@/Components/Chat/MessagesContainer"
import ChatContainer from "@/Components/Chat/ChatContainer"
import throttle from "lodash/throttle";
import {Inertia} from "@inertiajs/inertia";

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

videoPlayer.currentView = 'chat'



onMounted(() => {
    videoPlayer.makeVideoTopRight();
});




</script>


