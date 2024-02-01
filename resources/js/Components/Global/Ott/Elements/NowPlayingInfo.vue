<template>
  <Transition
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      enter-active-class="transition duration-3000"
      leave-active-class="transition duration-2000"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
  >

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


    <div v-if="shouldDisplayOtt"
         :class="[appSettingStore.fullPage ? 'playlistFullPageContainer' : 'ottTopRightDisplay','bg-purple-800 hide-scrollbar']">
      <div class="h-full w-full overflow-y-scroll scrollbar-hide">
        <div class="now-playing w-full h-full px-2 bg-purple-800 overflow-y-scroll scrollbar-hide">
          <h1 class="text-xs font-semibold uppercase w-full bg-purple-900 text-white p-2 mt-2 mb-2">
            NOW PLAYING</h1>

          <div class="pb-24 w-full overflow-y-scroll scrollbar-hide"
               :class="[{'h-[calc(100vh-22rem)]':!userStore.isMobile},{'h-[calc(100vh-20rem)]':userStore.isMobile}]">

            <div > <!-- Example for 'show' -->
              <div class="flex flex-col">
                <div class="flex flex-row">
                  <!-- Image -->
                  <div class="showOrMovieImage">
                    <Link :href="`${nowPlayingStore.show?.url}`">
                      <SingleImage
                          :image="nowPlayingStore.show?.image"
                          :alt="nowPlayingStore.show?.name"
                          class="h-16 w-12 object-cover hover:opacity-75 transition ease-in-out duration-150"
                      />
                    </Link>
                  </div>

                  <!-- Title and Episode -->
                  <div class="flex flex-col">
                    <Link :href="`${nowPlayingStore.show?.url}`"><div class="showOrMovieTitle">{{ nowPlayingStore.show?.name }}</div></Link>
                    <div class="showEpisodeTitle">{{ nowPlayingStore.show?.episode?.name }}</div>
                  </div>
                </div>
                <div class="flex flex-row">
                  <div class="flex flex-wrap">
                    <!-- Release Date and Categories -->
                    <div class="releaseDateTime">{{ useTimeAgo(nowPlayingStore.show?.episode.releaseDateTime) }}</div>
                    <div class="showOrMovieCategory">{{ nowPlayingStore.show?.category }}</div>
                    <div class="showOrMovieCategorySub">{{ nowPlayingStore.show?.categorySub }}</div>
                  </div>
                </div>
                <!-- Description -->
                <div class="flex flex-row">
                  <div class="showEpisodeOrMovieDescription">{{ nowPlayingStore.show?.episode.description }}</div>
                </div>
                <!-- Creators -->
                <div class="flex flex-col mt-6">
                  <h1 class="tracking-wider text-xs font-semibold uppercase w-full bg-purple-900 text-white p-2 mt-2 mb-2">
                    Creators</h1>
                  <div>

                  </div>
<!--                  <div v-if="nowPlayingStore.showCreators.length > 0" class="teamMembers">-->
<!--                    <div v-for="creator in nowPlayingStore.showCreators" :key="creator.slug" class="creator">-->
<!--                      <img :src="getImageUrl(creator.image)" :alt="creator.name" class="creator-image">-->
<!--                      <p>{{ creator.name }}</p>-->
<!--                      &lt;!&ndash; Add other creator details here &ndash;&gt;-->
<!--                    </div>-->
<!--                  </div>-->
                </div>
                <!-- Bonus Content -->
                <div class="flex flex-col mt-6">
                  <h1 class="tracking-wider text-xs font-semibold uppercase w-full bg-purple-900 text-white p-2 mt-2 mb-2">
                    Bonus Content</h1>

                  <div class="showEpisodeOrMovieBonusContent">{{ nowPlayingStore.bonusContent }}</div>
                  <!-- You'll need to add logic for bonus content -->
                </div>
              </div>
            </div>
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

          <!--                <div v-if="videoPlayerStore.nowPlayingTeam.name" class="py-2 mt-24 text-sm uppercase">-->
          <!--                    Copyright <Link :href="`/teams/${videoPlayerStore.nowPlayingTeam.slug}`">{{ videoPlayerStore.nowPlayingTeam.name }}</Link>.-->
          <!--                </div>-->
          <!--            </div>-->

        </div>

      </div>
    </div>
  </Transition>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { useTimeAgo } from '@vueuse/core'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useNowPlayingStore } from '@/Stores/NowPlayingStore'
import { useStreamStore } from '@/Stores/StreamStore'
import { useUserStore } from '@/Stores/UserStore'
import { useChatStore } from '@/Stores/ChatStore'
import SingleImage from '@/Components/Global/Multimedia/SingleImage'

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()
const nowPlayingStore = useNowPlayingStore()
const streamStore = useStreamStore()
const chatStore = useChatStore()
const userStore = useUserStore()


let props = defineProps({
  user: Object,
})

onMounted(() => {
  console.log(nowPlayingStore); // Check the store's content
  nowPlayingStore.fetchFirstPlayData();
});

// let playVideo = (source) => {
//   videoPlayerStore.loadNewSourceFromMist(source)
// }

// const ottDisplayShow = computed(() => ({
//   'hidden': !videoPlayerStore.ott,
// }))

// const ottChannels = computed(() => ({
//     channelsOttMobile: userStore.isMobile,
//     channelsOttDesktop: !userStore.isMobile
// }))

const shouldDisplayOtt = computed(() => {
  return appSettingStore.ott === 1
})

const nowPlayingContent = computed(() => {
  switch (nowPlayingStore.activeType) {
    case nowPlayingStore.type[6]: // 'show'
      return {
        title: nowPlayingStore.show?.name,
        description: nowPlayingStore.show.description,
        image: nowPlayingStore.show?.image,
        // Add other show-specific properties if needed
      }
    case nowPlayingStore.type[7]: // 'movie'
      return {
        title: nowPlayingStore.movie?.name,
        description: nowPlayingStore.movie.description,
        image: nowPlayingStore.movie?.image,
        // Add other movie-specific properties if needed
      }
    case nowPlayingStore.type[4]: // 'videoFile'
      return {
        title: nowPlayingStore.videoFile?.name,
        description: '', // Add description if available
        image: null, // If no image for videoFile, handle accordingly
        // Add other videoFile-specific properties if needed
      }
      // Add other cases for different types
    default:
      return {title: '', description: '', image: null} // Default or empty state
  }
})

// Helper method to get image URL
const getImageUrl = (image) => {
  if(image) {
    return image ? `${image.cdn_endpoint}/${image.folder}/${image.name}` : ''
  }

}

</script>

<style scoped>


</style>
