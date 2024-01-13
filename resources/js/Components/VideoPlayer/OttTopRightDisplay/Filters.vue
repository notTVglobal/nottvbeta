<template>
    <div v-if="videoPlayerStore.ott === 5" class="ottTopRightDisplay bg-yellow-500 hide-scrollbar">
    <div class="h-full w-full overflow-y-scroll scrollbar-hide">

        <upgrade v-if="videoPlayerStore.ott === 5 && !userStore.isVip && !userStore.isAdmin"/>
        <div v-if="videoPlayerStore.ott === 5 && (userStore.isVip || userStore.isAdmin)"
             class="now-playing w-full h-full bg-yellow-500 text-black p-2 overflow-y-scroll scrollbar-hide mb-64">
            <h1 class="text-xs font-semibold uppercase mb-3 w-full bg-yellow-600 text-black p-2">FILTERS</h1>
            <div class="pb-24 w-full overflow-y-scroll scrollbar-hide"
                 :class="[{'h-[calc(100vh-22rem)]':!userStore.isMobile},{'h-[calc(100vh-20rem)]':userStore.isMobile}]">
                Coming Soon!
            </div>
        </div>

    </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useStreamStore } from "@/Stores/StreamStore"
import { useChatStore } from "@/Stores/ChatStore.js"
import { useUserStore } from "@/Stores/UserStore"
import Upgrade from "@/Components/VideoPlayer/OttTopRightDisplay/Upgrade.vue";

let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let chat = useChatStore()
let userStore = useUserStore()

let props = defineProps ({
    user: Object,
})

let playVideo = (source) => {
    videoPlayerStore.loadNewSourceFromMist(source)
}

const ottDisplayShow = computed(() => ({
    'hidden': !videoPlayerStore.ott
}))

const ottChannels = computed(() => ({
    channelsOttMobile: userStore.isMobile,
    channelsOttDesktop: !userStore.isMobile
}))

</script>

<style scoped>


</style>
