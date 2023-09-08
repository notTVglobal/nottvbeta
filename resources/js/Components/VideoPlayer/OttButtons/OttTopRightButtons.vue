<template>
    <div class="videoOTT fixed top-40 lg:top-72 w-full lg:w-96 right-0 z-30">
    <div class="flex flex-row justify-around bg-red-500 w-full text-xs uppercase font-semibold"
         :class="[{'h-7':!userStore.isMobile}, { 'h-8':userStore.isMobile }]">

        <button class="h-full w-full border-r-2 border-r-gray-900"
                :class="infoClass"
                @click="videoPlayerStore.toggleOttInfo()">
            INFO </button>
        <button class="h-full w-full border-r-2 border-r-gray-900"
                :class="channelsClass"
                @click="videoPlayerStore.toggleOttChannels()">
            CHANNELS </button>
        <button class="h-full w-full border-r-2 border-r-gray-900"
                :class="playlistClass"
                @click="videoPlayerStore.toggleOttPlaylist()">
            PLAYLIST </button>
        <button class="h-full w-full border-r-2 border-r-gray-900"
                :class="chatClass"
                @click="videoPlayerStore.toggleOttChat()">
            CHAT </button>
        <button class="h-full w-full"
                :class="filtersClass"
                @click="videoPlayerStore.toggleOttFilters()">
            FILTERS </button>

    </div>
    </div>
</template>

<script setup>
import { computed, ref } from "vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useUserStore } from "@/Stores/UserStore";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

const infoClass = computed(() => ({
    // all users
    'bg-purple-700': videoPlayerStore.ott === 1,
    'hover:bg-purple-700': videoPlayerStore.ott !== 1,
    'bg-purple-900': videoPlayerStore.ott !== 1,
}))

const channelsClass = computed(() => ({
    // user is !subscriber
    'bg-gray-700': videoPlayerStore.ott === 2 && !userStore.userIsSubscriber,
    'hover:bg-gray-700': videoPlayerStore.ott !== 2 && !userStore.userIsSubscriber,
    'bg-gray-900': videoPlayerStore.ott !== 2 && !userStore.userIsSubscriber,
    // user is subscriber
    'bg-green-700': videoPlayerStore.ott === 2 && userStore.userIsSubscriber,
    'hover:bg-green-700': videoPlayerStore.ott !== 2 && userStore.userIsSubscriber,
    'bg-green-900': videoPlayerStore.ott !== 2 && userStore.userIsSubscriber
}))

const playlistClass = computed(() => ({
    // user is !subscriber
    'bg-gray-700': videoPlayerStore.ott === 3 && !userStore.userIsSubscriber,
    'hover:bg-gray-700': videoPlayerStore.ott !== 3 && !userStore.userIsSubscriber,
    'bg-gray-900': videoPlayerStore.ott !== 3 && !userStore.userIsSubscriber,
    // user is subscriber
    'bg-orange-700': videoPlayerStore.ott === 3 && userStore.userIsSubscriber,
    'hover:bg-orange-700': videoPlayerStore.ott !== 3 && userStore.userIsSubscriber,
    'bg-orange-900': videoPlayerStore.ott !== 3 && userStore.userIsSubscriber
}))

const chatClass = computed(() => ({
    // all users
    'bg-indigo-700': videoPlayerStore.ott === 4,
    'hover:bg-indigo-700': videoPlayerStore.ott !== 4,
    'bg-indigo-900': videoPlayerStore.ott !== 4
}))

const filtersClass = computed(() => ({
    // user is !subscriber
    'bg-gray-700': videoPlayerStore.ott === 5 && !userStore.userIsSubscriber,
    'hover:bg-gray-700': videoPlayerStore.ott !== 5 && !userStore.userIsSubscriber,
    'bg-gray-900': videoPlayerStore.ott !== 5 && !userStore.userIsSubscriber,
    // user is subscriber
    'bg-yellow-700': videoPlayerStore.ott === 5 && userStore.userIsSubscriber,
    'hover:bg-yellow-700': videoPlayerStore.ott !== 5 && userStore.userIsSubscriber,
    'bg-yellow-900': videoPlayerStore.ott !== 5 && userStore.userIsSubscriber
}))



</script>
