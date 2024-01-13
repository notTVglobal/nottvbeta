<template>
    <div v-if="videoPlayerStore.ott === 1" class="ottTopRightDisplay bg-purple-800 hide-scrollbar">
    <div class="h-full w-full overflow-y-scroll scrollbar-hide">

        <div v-if="videoPlayerStore.ott === 1" class="now-playing w-full h-full bg-purple-800 p-2 overflow-y-scroll scrollbar-hide mb-64">
            <h1 class="text-xs font-semibold uppercase mb-3 w-full bg-purple-900 text-white p-2">NOW PLAYING INFO</h1>

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
import { useStreamStore } from "@/Stores/StreamStore"
import { useChatStore } from "@/Stores/ChatStore.js"
import { useUserStore } from "@/Stores/UserStore"
import SingleImage from "@/Components/Multimedia/SingleImage.vue";

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
