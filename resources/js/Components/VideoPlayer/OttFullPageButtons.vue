<template>
    <div v-if="videoPlayerStore.showOttButtons">
        <button @click="videoPlayerStore.toggleChannels()"
                class="opacity-80 chatButtonForStreamPage w-20 h-20 bottom-6 mr-24 rounded-full bg-green-400 text-green-100
                                   hover:bg-green-600 hover:text-green-300 cursor-pointer grid justify-center content-center text-xs">
            <font-awesome-icon icon="fa-rocket" class="ml-4 text-3xl"/><div>CHANNELS</div>
        </button>

        <button @click="videoPlayerStore.toggleChat()"
                class="opacity-80 chatButtonForStreamPage w-20 h-20 bottom-6 rounded-full bg-orange-400 text-orange-100
                                   hover:bg-orange-600 hover:text-orange-300 cursor-pointer grid justify-center content-center">
            <font-awesome-icon icon="fa-comments" class="text-3xl"/><div>CHAT</div>
        </button>
    </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"

let videoPlayerStore = useVideoPlayerStore()
</script>
