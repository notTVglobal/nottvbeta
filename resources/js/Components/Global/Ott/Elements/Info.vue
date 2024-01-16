<template>

        <!-- Video OSD (on screen display) when video is TopRight and the user is logged in. -->
<!--    <div class="absolute flex justify-between top-0 drop-shadow pt-3 px-10 lg:px-2 w-full z-50">-->
<!--        <div v-if="videoPlayerStore.videoName !== ''">-->
<!--            <span class="text-xs uppercase pr-2">Now playing: </span>-->
<!--            <span class="font-semibold text-xs">{{ videoPlayerStore.videoName }}</span>-->
<!--        </div>-->
<!--        <div v-if="channelStore.currentChannelName !== '' || null">-->
<!--            <span class="text-xs uppercase pr-2">Channel: </span>-->
<!--            <span class="text-xs font-semibold">{{ channelStore.currentChannelName }}</span>-->
<!--        </div>-->
<!--        <div v-if="channelStore.isLive" class="absolute pt-6 left-0 pl-10 lg:pl-2 drop-shadow z-50 w-full">-->
<!--            <div class="flex justify-between">-->
<!--                <div>-->
<!--                            <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-white bg-opacity-80 bg-red-800 uppercase last:mr-0 mr-1">-->
<!--                            live-->
<!--                            </span>-->
<!--                </div>-->

<!--            </div>-->
<!--        </div>-->
<!--    </div>-->

    <div v-if="appSettingStore.ott === 1" class="ottTopRightDisplay bg-purple-800 hide-scrollbar">
    <div class="h-full w-full overflow-y-scroll scrollbar-hide">

        <div v-if="appSettingStore.ott === 1" class="now-playing w-full h-full px-2 bg-purple-800 overflow-y-scroll scrollbar-hide">
            <h1 class="text-xs font-semibold uppercase w-full bg-purple-900 text-white p-2 mt-2 mb-2">NOW PLAYING INFO</h1>

            <div class="pb-24 w-full overflow-y-scroll scrollbar-hide"
                 :class="[{'h-[calc(100vh-22rem)]':!userStore.isMobile},{'h-[calc(100vh-20rem)]':userStore.isMobile}]">
                <div class="flex justify-between">
                    <div>
                        <div class="flex flex-wrap">
                            <div><Link :href="`${videoPlayerStore.nowPlayingUrl}`"><SingleImage :image="videoPlayerStore.nowPlayingImage" :alt="`${videoPlayerStore.nowPlayingName}`" :class="`h-16 w-12  object-cover hover:opacity-75 transition ease-in-out duration-150`"/></Link></div>
                            <div class="pl-3 text-xl font-semibold"><Link :href="`${videoPlayerStore.nowPlayingUrl}`">{{ videoPlayerStore.nowPlayingName }}</Link></div>
                        </div>
                        <div class="p-2">{{ videoPlayerStore.nowPlayingDescription }}</div>
                    </div>

<!--                        <img :src="`/storage/images/EBU_Colorbars.svg.png`" alt="poster" class="h-16 w-12  object-cover hover:opacity-75 transition ease-in-out duration-150">-->
    <!--                    <img :src="`/storage/images/${streamStore.posterUrl}`">-->

                </div>

<!--                <div v-if="videoPlayerStore.nowPlayingCreators" class="creators-section mt-6">-->
<!--                    <div class="creators-header w-full p-1 bg-purple-900 text-white uppercase text-xs">Creators</div>-->
<!--                    <div class="creators-list py-2">-->
<!--                        <div v-for="creator in videoPlayerStore.validCreators" :key="creator.id">-->
<!--                            <SingleImage />-->
<!--                            <div>{{creator.name}}</div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->

<!--                <div v-if="videoPlayerStore.nowPlayingBonusContent" class="bonus-content-section mt-6">-->
<!--                    <div class="bonus-content-header w-full p-1 bg-purple-900 text-white uppercase text-xs">Bonus Content</div>-->
<!--                    <div class="bonus-content-list py-2">-->
<!--                        <div v-for="bonus in videoPlayerStore.nowPlayingBonusContent" :key="bonus.id">-->
<!--                            <SingleImage :image="bonus.image"/>-->
<!--                            <div>{{bonus.name}}</div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->

                <div v-if="videoPlayerStore.nowPlayingTeam.name" class="py-2 mt-24 text-sm uppercase">
                    Copyright <Link :href="`/teams/${videoPlayerStore.nowPlayingTeam.slug}`">{{ videoPlayerStore.nowPlayingTeam.name }}</Link>.
                </div>
            </div>

        </div>

    </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useNowPlayingStore } from "@/Stores/NowPlayingStore.js"
import { useStreamStore } from "@/Stores/StreamStore"
import { useUserStore } from "@/Stores/UserStore"
import { useChatStore } from "@/Stores/ChatStore.js"
import SingleImage from "@/Components/Global/Multimedia/SingleImage.vue";

const videoPlayerStore = useVideoPlayerStore()
const nowPlayingStore = useNowPlayingStore()
const streamStore = useStreamStore()
const chatStore = useChatStore()
const userStore = useUserStore()


let props = defineProps ({
    user: Object,
})

let playVideo = (source) => {
    videoPlayerStore.loadNewSourceFromMist(source)
}

const ottDisplayShow = computed(() => ({
    'hidden': !videoPlayerStore.ott
}))

// const ottChannels = computed(() => ({
//     channelsOttMobile: userStore.isMobile,
//     channelsOttDesktop: !userStore.isMobile
// }))



</script>

<style scoped>


</style>
