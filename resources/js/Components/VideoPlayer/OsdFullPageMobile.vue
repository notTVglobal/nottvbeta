<template>
<div>
    <div class="videoFullPageMobileOSD absolute w-full left-0 p-5 drop-shadow z-50">
        <div>
            <span class="text-xs uppercase pr-2">Now playing: </span>
            <span class="font-semibold">{{ videoName }}</span>
        </div>

        <div v-if="isLive" class="absolute pt-1 left-0 px-5 drop-shadow z-50">
                                    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-white bg-opacity-80 bg-red-800 uppercase last:mr-0 mr-1">
                                        live
                                    </span>
            <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-white bg-opacity-50 bg-black uppercase last:mr-0 mr-1">
                                        <font-awesome-icon icon="fa-solid fa-user" class="pr-1" /> 88
                                    </span>
        </div>
    </div>
    <div class="absolute w-fit top-96 left-0 p-5 drop-shadow z-50">
        <button @click="backToPage" class="p-2 bg-gray-600 text-white" >Back to Page</button>
    </div>
</div>
</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useStreamStore } from "@/Stores/StreamStore"
import { useUserStore } from "@/Stores/UserStore"
import { useChatStore } from "@/Stores/ChatStore"

let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let userStore = useUserStore()
let chatStore = useChatStore()

function backToPage() {
    // videoPlayerStore.makeVideoTopRight();
    // chatStore.showChat = false;
    // streamStore.showOSD = false;
    window.history.back()
}

let isMobile = userStore.isMobile;
let videoName = videoPlayerStore.videoName;
let isLive = streamStore.isLive;
let currentPage = videoPlayerStore.currentPage;

</script>
