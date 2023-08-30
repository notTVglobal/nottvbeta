<template>
    <div v-if="videoPlayerStore.showOttButtons">

        <div class="ottButtonsContainer" :class="ottButtons">
            <button @click="openChannels"
                    class="ottButton bg-green-400 text-green-100 hover:bg-green-600 hover:text-green-300">
                <font-awesome-icon icon="fa-rocket" class="ml-3.5 text-3xl mb-1"/><div>CHANNELS</div>
            </button>

            <button @click="openPlaylist"
                    class="ottButton bg-orange-400 text-orange-100 hover:bg-orange-600 hover:text-orange-300">
                <font-awesome-icon icon="fa-list" class="ml-2 text-3xl mb-1"/><div>PLAYLIST</div>
            </button>

            <button @click="openChat"
                    class="ottButton bg-blue-400 text-blue-100 hover:bg-blue-600 hover:text-blue-300">
                <font-awesome-icon icon="fa-comments" class="text-3xl mb-1"/><div>CHAT</div>
            </button>

            <button @click="openFilters"
                    class="ottButton bg-yellow-400 text-yellow-100 hover:bg-yellow-600 hover:text-yellow-300">
                <font-awesome-icon icon="fa-filter" class="ml-1 text-3xl mb-1"/><div>FILTERS</div>
            </button>
        </div>
    </div>

</template>

<script setup>
import { computed } from "vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useChatStore } from "@/Stores/ChatStore"
import { useUserStore } from "@/Stores/UserStore"

let videoPlayerStore = useVideoPlayerStore()
let chatStore = useChatStore()
let userStore = useUserStore()

function openChannels() {
    videoPlayerStore.toggleChannels()
    videoPlayerStore.showControls = false
}

function openPlaylist() {
    videoPlayerStore.togglePlaylist()
    videoPlayerStore.showControls = false
}
function openChat() {
    chatStore.toggleChatOn()
    videoPlayerStore.showOttButtons = false
    videoPlayerStore.showControls = false
}

function openFilters() {
    videoPlayerStore.toggleFilters()
    videoPlayerStore.showControls = false
}
const ottButtons = computed(() => ({
    OttButtonsMobile: userStore.isMobile,
    OttButtonsDesktop: !userStore.isMobile
}))
</script>
