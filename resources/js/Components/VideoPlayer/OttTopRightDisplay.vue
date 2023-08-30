<template>
    <div v-if="videoPlayerStore.ott !== 0" class="fixed top-44 lg:top-78 right-0 w-full h-full lg:w-96 mt-4 overflow-y-none z-40"
    :class="{'lg:mt-3':userStore.isMobile, 'lg:mt-2':!userStore.isMobile}">
    <div class="h-full w-full overflow-y-scroll scrollbar-hide"
         :class="{'':userStore.isMobile}">

        <div v-if="videoPlayerStore.ott === 2" class="channels w-full h-full bg-gray-800 p-2 mb-64"
             :class="ottChannels">
            <h1 class="text-xs font-semibold uppercase mb-3 w-full bg-green-900 text-white p-2">CHANNELS</h1>

            <div class="overflow-y-scroll scrollbar-hide pb-24 w-full "
                 >

                <Channels/>
            </div>
        </div>

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
        <div v-if="videoPlayerStore.ott === 3" class="now-playing w-full h-full bg-orange-800 p-2 overflow-y-scroll scrollbar-hide mb-64">
            <h1 class="text-xs font-semibold uppercase mb-3 w-full bg-orange-900 text-white p-2">PLAYLIST</h1>
            <div class="pb-24 w-full overflow-y-scroll scrollbar-hide"
                 :class="[{'h-[calc(100vh-22rem)]':!userStore.isMobile},{'h-[calc(100vh-20rem)]':userStore.isMobile}]">
                Add a loop here to display the playlist... scrollable. If the current channel is the users_channel,
                display the playlist for the user. If the current channel is another channel (e.g., stream), display
                the stream_playlist.
            </div>
        </div>
<!--        <div v-if="videoPlayerStore.ott === 4" class="now-playing w-full bg-red-800 px-2 pt-2 overflow-y-scroll scrollbar-hide">-->
        <div v-if="videoPlayerStore.ott === 4" class="w-full h-full px-2 overflow-y-scroll scrollbar-hide">
<!--            <h1 class="text-xs font-semibold uppercase mb-3 w-full bg-indigo-900 text-white p-2">CHAT</h1>-->

            <!--            tec21: this one below works, 29MAR2023. The Ott divs need to be turned into pages on mobile devices so there is no scroll bar overlap. -->
            <!--            <div class="h-[calc(100vh-28rem)] w-full overflow-y-scroll scrollbar-hide">-->
            <div class="w-full overflow-y-scroll scrollbar-hide"
                 :class="[{'h-[calc(100vh-22rem)]':!userStore.isMobile},{'h-[calc(100vh-20rem)]':userStore.isMobile}]">

                <VideoOTTChat :user="props.user"/>
            </div>
        </div>
        <div v-if="videoPlayerStore.ott === 5"
             class="now-playing w-full h-full bg-yellow-500 text-black p-2 overflow-y-scroll scrollbar-hide mb-64">
            <h1 class="text-xs font-semibold uppercase mb-3 w-full bg-yellow-600 text-black p-2">FILTERS</h1>
            <div class="pb-24 w-full overflow-y-scroll scrollbar-hide"
                 :class="[{'h-[calc(100vh-22rem)]':!userStore.isMobile},{'h-[calc(100vh-20rem)]':userStore.isMobile}]">
                Coming Soon!
            </div>
        </div>
    </div>
    </div>
    <div v-if="!userStore.isMobile && videoPlayerStore.ott" class="ottTopRightDisplayBG fixed top-44 lg:top-78 right-0 w-full h-full lg:w-96 mt-4 lg:mt-2 z-20 bg-gray-900"></div>
</template>

<script setup>
import { computed } from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useStreamStore } from "@/Stores/StreamStore"
import { useChatStore } from "@/Stores/ChatStore.js"
import { useUserStore } from "@/Stores/UserStore"
import VideoOTTChat from "@/Components/Chat/VideoOTTChat"
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
