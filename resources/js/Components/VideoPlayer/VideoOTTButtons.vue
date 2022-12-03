<template>
    <div class="flex flex-row justify-around h-6 mt-16 bg-red-500 w-full text-xs uppercase font-semibold">
        <button class="h-full w-full bg-green-900 hover:bg-green-700" @click="videoPlayerStore.ott = 1"> BACK </button>
        <button class="h-full w-full"
                :class="{ 'bg-purple-900': videoPlayerStore.ott != 2, 'bg-purple-700': videoPlayerStore.ott === 2, 'hover:bg-purple-700':videoPlayerStore.ott != 2 }"
                @click="videoPlayerStore.toggleOtt(2)">
            INFO </button>
        <button class="h-full w-full"
                :class="{ 'bg-orange-900': videoPlayerStore.ott != 3, 'bg-orange-700': videoPlayerStore.ott === 3, 'hover:bg-orange-700':videoPlayerStore.ott != 3 }"
                @click="videoPlayerStore.toggleOtt(3)">
            PLAYLIST </button>
        <button class="h-full w-full bg-indigo-900"
                :class="{ 'bg-indigo-900': videoPlayerStore.ott != 4, 'bg-indigo-700': videoPlayerStore.ott === 4, 'hover:bg-indigo-700':videoPlayerStore.ott != 4 }"
                @click="videoPlayerStore.toggleOtt(4)">
            CHAT </button>
        <button class="h-full w-full bg-yellow-900"
                :class="{ 'bg-yellow-900': videoPlayerStore.ott != 5, 'bg-yellow-700': videoPlayerStore.ott === 5, 'hover:bg-yellow-700':videoPlayerStore.ott != 5 }"
                @click="videoPlayerStore.toggleOtt(5)">
            FILTERS </button>

    </div>
</template>

<script setup>
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js"
let videoPlayerStore = useVideoPlayerStore()


</script>
