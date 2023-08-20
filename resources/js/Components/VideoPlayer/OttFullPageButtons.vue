<template>
    <div v-if="videoPlayerStore.showOttButtons">

        <div :class="{OttButtonsMobile:userStore.isMobile}">
            <button @click="openChannels"
                    class="opacity-80 chatButtonForStreamPage w-20 h-20 bottom-6 mr-72 rounded-full bg-green-400 text-green-100
                                       hover:bg-green-600 hover:text-green-300 cursor-pointer grid justify-center content-center text-xs">
                <font-awesome-icon icon="fa-rocket" class="ml-3.5 text-3xl mb-1"/><div>CHANNELS</div>
            </button>

            <button @click="openPlaylist"
                    class="opacity-80 chatButtonForStreamPage w-20 h-20 bottom-6 mr-48 rounded-full bg-orange-400 text-orange-100
                                       hover:bg-orange-600 hover:text-orange-300 cursor-pointer grid justify-center content-center text-xs">
                <font-awesome-icon icon="fa-list" class="ml-2 text-3xl mb-1"/><div>PLAYLIST</div>
            </button>

            <button @click="openChat"
                    class="opacity-80 chatButtonForStreamPage w-20 h-20 bottom-6 mr-24 rounded-full bg-blue-400 text-blue-100
                                       hover:bg-blue-600 hover:text-blue-300 cursor-pointer grid justify-center content-center text-xs">
                <font-awesome-icon icon="fa-comments" class="text-3xl mb-1"/><div>CHAT</div>
            </button>

            <button @click="openFilters"
                    class="opacity-80 chatButtonForStreamPage w-20 h-20 bottom-6 rounded-full bg-yellow-400 text-yellow-100
                                       hover:bg-yellow-600 hover:text-yellow-300 cursor-pointer grid justify-center content-center text-xs">
                <font-awesome-icon icon="fa-filter" class="ml-1 text-3xl mb-1"/><div>FILTERS</div>
            </button>
        </div>
    </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useChatStore } from "@/Stores/ChatStore"
import { useUserStore } from "@/Stores/UserStore"

let videoPlayerStore = useVideoPlayerStore()
let chatStore = useChatStore()
let userStore = useUserStore()

function openChannels() {
    videoPlayerStore.toggleChannels()
}

function openPlaylist() {
    videoPlayerStore.togglePlaylist()
}
function openChat() {
    chatStore.toggleChatOn()
    videoPlayerStore.showOttButtons = false
    videoPlayerStore.showOSD = false
}

function openFilters() {
    videoPlayerStore.toggleFilters()
}
</script>
