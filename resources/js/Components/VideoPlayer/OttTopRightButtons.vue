<template>
    <div class="flex flex-row justify-around bg-red-500 w-full text-xs uppercase font-semibold"
         :class="[{'h-7':!userStore.isMobile}, { 'h-8':userStore.isMobile }]">

        <button class="h-full w-full"
                :class="{ 'bg-purple-900': videoPlayerStore.ott !== 1, 'bg-purple-700': videoPlayerStore.ott === 1, 'hover:bg-purple-700':videoPlayerStore.ott != 1 }"
                @click="videoPlayerStore.toggleOttInfo()">
            INFO </button>
        <button class="h-full w-full"
                :class="{ 'bg-green-900': videoPlayerStore.ott !== 2, 'bg-green-700': videoPlayerStore.ott === 2, 'hover:bg-green-700':videoPlayerStore.ott != 2 }"
                @click="videoPlayerStore.toggleOttChannels()">
            CHANNELS </button>
        <button class="h-full w-full"
                :class="{ 'bg-orange-900': videoPlayerStore.ott !== 3, 'bg-orange-700': videoPlayerStore.ott === 3, 'hover:bg-orange-700':videoPlayerStore.ott != 3 }"
                @click="videoPlayerStore.toggleOttPlaylist()">
            PLAYLIST </button>
        <button class="h-full w-full bg-indigo-900"
                :class="{ 'bg-indigo-900': videoPlayerStore.ott !== 4, 'bg-indigo-700': videoPlayerStore.ott === 4, 'hover:bg-indigo-700':videoPlayerStore.ott != 4 }"
                @click="videoPlayerStore.toggleOttChat()">
            CHAT </button>
        <button class="h-full w-full bg-yellow-900"
                :class="{ 'bg-yellow-900': videoPlayerStore.ott !== 5, 'bg-yellow-700': videoPlayerStore.ott === 5, 'hover:bg-yellow-700':videoPlayerStore.ott != 5 }"
                @click="videoPlayerStore.toggleOttFilters()">
            FILTERS </button>

    </div>
</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useUserStore } from "@/Stores/UserStore";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()


</script>
