<template>
    <div v-if="videoPlayerStore.ott !== 0" class="fixed top-44 lg:top-78 right-0 w-full h-full lg:w-96 mt-3 lg:mt-2 overflow-y-none z-40 lg:border-t lg:border-gray-900">
    <div class="h-full w-full overflow-y-scroll scrollbar-hide">

        <div v-if="videoPlayerStore.ott === 1" class="now-playing w-full h-full bg-purple-800 p-2 overflow-y-scroll scrollbar-hide mb-64">
            <h1 class="text-xs font-semibold uppercase mb-3 w-full bg-purple-900 text-white p-2">NOW PLAYING INFO</h1>

            <div class="pb-24 w-full overflow-y-scroll scrollbar-hide"
                 :class="[{'h-[calc(100vh-22rem)]':!userStore.isMobile},{'h-[calc(100vh-20rem)]':userStore.isMobile}]">
                <div class="flex justify-between">
                    <div>
                        <div><span class="text-xs uppercase">Name: </span><Link :href="`#`">{{ streamStore.name }}</Link></div>
                        <div><span class="text-xs uppercase">Description: </span>{{ streamStore.description }}</div>
                        <div><span class="text-xs uppercase">Team: </span><Link :href="`#`">{{ streamStore.teamName }}</Link></div>
                    </div>

                    <div><Link :href="`#`"><img :src="`/storage/images/EBU_Colorbars.svg.png`" alt="poster" class="h-16 w-12  object-cover hover:opacity-75 transition ease-in-out duration-150">
    <!--                    <img :src="`/storage/images/${streamStore.posterUrl}`">-->

                    </Link></div>
                </div>

                <div class="creators-section mt-6">
                    <div class="creators-header w-full p-1 bg-purple-900 text-white uppercase text-xs">Creators</div>
                    <div class="creators-list py-2">
                        &lt;Creators here&gt;
                    </div>
                </div>

                <div class="creators-section mt-6">
                    <div class="creators-header w-full p-1 bg-purple-900 text-white uppercase text-xs">Bonus Content</div>
                    <div class="creators-list py-2">
                        &lt;Content here&gt;
                    </div>
                </div>

                <div class="py-2 mt-24 text-sm uppercase">
                    Copyright &lt;Team Name&gt; here.
                </div>
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
import VideoOTTChat from "@/Components/VideoPlayer/Chat/VideoOTTChat"
import Channels from "@/Components/VideoPlayer/Channels/Channels"
import ChannelFooter from "@/Components/VideoPlayer/Channels/ChannelFooter.vue";

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
    'hidden': videoPlayerStore.ottClass !== 'OttOpen'
}))

const ottChannels = computed(() => ({
    channelsOttMobile: userStore.isMobile,
    channelsOttDesktop: !userStore.isMobile
}))

</script>

<style scoped>


</style>
