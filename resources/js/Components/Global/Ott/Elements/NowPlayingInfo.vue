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
         :class="[appSettingStore.fullPage ? '' : 'ottTopRightDisplay','hide-scrollbar']">
      <div class="h-full w-full overflow-y-scroll scrollbar-hide">
        <div class="w-full h-full px-4 bg-gray-900 overflow-y-scroll scrollbar-hide">
          <h1 class="text-xs font-semibold uppercase w-full bg-purple-900 text-white p-2 mt-4 mb-4">
            NOW PLAYING
            <span class="text-gray-500 tracking-widest" v-if="nowPlayingStore.activeMedia.type==='channel'">&nbsp;&nbsp;{{ channelStore.currentChannelName }}&nbsp;Channel</span>
            <span class="text-gray-500 tracking-widest" v-if="nowPlayingStore.activeMedia.type==='externalVideo' || nowPlayingStore.activeMedia.type==='bitchute'">&nbsp;&nbsp;external video</span>

          </h1>

          <div class="pb-24 w-full overflow-y-scroll scrollbar-hide"
               :class="[{'h-[calc(100vh-22rem)]':!userStore.isMobile},{'h-[calc(100vh-20rem)]':userStore.isMobile}]">

            <div > <!-- Example for 'show' -->
              <div class="flex flex-col">
                <div v-if="nowPlayingStore.activeMedia.details?.category?.name" class="flex flex-row mt-2">
                  <div class="flex flex-wrap mb-4">
                    <!-- Categories -->
                    <div class="text-yellow-700 uppercase tracking-wider">{{ nowPlayingStore.activeMedia.details?.category?.name }}</div>
                    &nbsp;&middot;&nbsp;
                    <div class="text-yellow-500 tracking-wide">{{ nowPlayingStore.activeMedia.details?.subCategory?.name }}</div>
                  </div>
                </div>
                <div class="flex flex-row">
                  <!-- Image -->
                  <div class="showOrMovieImage">
                    <Link @click.prevent="goToPage" :href="`#`">
                      <SingleImage
                          :image="nowPlayingStore.activeMedia.details?.image"
                          :alt="nowPlayingStore.activeMedia.details?.primaryName"
                          class="h-16 w-12 min-w-12 mr-2 object-cover hover:opacity-75 transition ease-in-out duration-150"
                      />
                    </Link>
                  </div>


                  <div class="flex flex-col ml-3 -mt-2 break-words">
                    <h3 v-if="nowPlayingStore.activeMedia.details?.primaryUrl">
                      <!-- Render as a link if the URL exists -->
                      <Link class="hover:text-blue-500 hover:cursor-pointer break-words" @click.prevent="goToPage" :href="`#`">
                        <!-- Title (with link) -->
                        {{ nowPlayingStore.activeMedia.details.primaryName }}
                      </Link>
                    </h3>
                    <h3 v-else>
                      <!-- Just display the name without a link if the URL does not exist -->
                      <!-- Title (no link) -->
                      {{ nowPlayingStore.activeMedia.details.primaryName }}
                    </h3>
                    <h4 v-if="nowPlayingStore.activeMedia.details?.secondaryUrl">
                      <!-- Render as a link if the URL exists -->
                      <Link class="hover:text-blue-500 hover:cursor-pointer break-words" :href="`/${nowPlayingStore.activeMedia.details.secondaryUrl}`">
                        <!-- Title (with link) -->
                        {{ nowPlayingStore.activeMedia.details.secondaryName }}
                      </Link>
                    </h4>
                    <h3 v-else>
                      <!-- Just display the name without a link if the URL does not exist -->
                      <!-- Title (no link) -->
                      {{ nowPlayingStore.activeMedia.details.secondaryName }}
                    </h3>
<!--                    <div class="showOrMovieTitle">{{ nowPlayingStore.show?.name }}</div>-->
<!--                    <div class="showEpisodeTitle">{{ nowPlayingStore.show?.episode?.name }}</div>-->
                    <!-- Release Date -->

                    <div class="releaseYear text-gray-400" v-if="!nowPlayingStore.activeMedia.details?.releaseDateTime">{{ nowPlayingStore.activeMedia.details?.release_year }}</div>
                    <ConvertDateTimeToTimeAgo v-if="nowPlayingStore.activeMedia.details?.releaseDateTime" :dateTime="nowPlayingStore.activeMedia.details?.releaseDateTime" :class="`text-yellow-400`" />

                  </div>
                </div>


                <!-- Logline and Description -->
                <div v-if="nowPlayingStore.activeMedia.details?.logline" class="flex flex-col mt-4 break-words">
                  <div class="text-xs uppercase text-gray-500 font-semibold tracking-wider mb-1">Logline</div>
                  <div class="showEpisodeOrMovieDescription">{{ nowPlayingStore.activeMedia.details?.logline }}</div>
                </div>
                <div v-if="nowPlayingStore.activeMedia.details?.description" class="flex flex-col my-4 break-words">
                  <div class="text-xs uppercase text-gray-500 font-semibold tracking-wider mb-1">Description</div>
                  <div class="showEpisodeOrMovieDescription description">{{ nowPlayingStore.activeMedia.details?.description }}</div>
                </div>

                <!-- Episode Number & Episode ID -->
                <div v-if="nowPlayingStore.activeMedia.details?.episodeNumber" class="flex flex-row space-x-2 text-gray-400">
                  <div class="text-xs">
                    Episode {{ nowPlayingStore.activeMedia.details?.episodeNumber }}
                  </div>
                  <div class="text-xs">
                    Episode ID: {{ nowPlayingStore.activeMedia.details?.episodeId }}
                  </div>
                </div>
                <!-- Copyright and Team -->
                <div class="flex flex-row flex-wrap">
                    <div>
                      <!--                    If there is a copyright year display it... we need to remove the &copy; and replace it with whichever creative commons icon it needs -->
                      <span class="text-xs font-semibold text-gray-500" v-if="nowPlayingStore.activeMedia.details?.copyrightYear">{{ nowPlayingStore.activeMedia.details?.copyrightYear }}</span>
                      <span class="text-xs font-semibold text-gray-500" v-if="nowPlayingStore.activeMedia.details?.creative_commons?.id === 7">&nbsp;&copy;&nbsp;</span>
                      <!--                    If there is no copyright year then it's probably public domain... so we'll just display the creative commons name-->
                      <span v-if="nowPlayingStore.activeMedia.details?.creative_commons?.name" class="text-xs font-semibold text-gray-500">&nbsp;&bull;&nbsp;{{ nowPlayingStore.activeMedia.details?.creative_commons?.name }}&nbsp;&bull;&nbsp;</span>

                    </div>
                    <div>
                      <Link class="text-sm link-dark" :href="`/teams/${nowPlayingStore.activeMedia.details?.team?.slug}`">{{ nowPlayingStore.activeMedia.details?.team?.name }}</Link></div>
                </div>
                <!-- Creators -->
                <div v-if="nowPlayingStore.activeMedia.details?.creators" class="flex flex-col mt-6">
                  <h1 class="tracking-wider text-xs font-semibold uppercase w-full bg-purple-900 text-white p-2 mt-2 mb-4">
                    <span v-if="nowPlayingStore.activeMedia.type === 'show'">Team</span>
                    <span v-if="nowPlayingStore.activeMedia.type === 'movie'">Credits</span>
                  </h1>
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
                <div v-if="nowPlayingStore.activeMedia.details?.bonusContent" class="flex flex-col mt-6">
                  <h1 class="tracking-wider text-xs font-semibold uppercase w-full bg-purple-900 text-white p-2 mt-2 mb-4">
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
import { useChannelStore } from '@/Stores/ChannelStore'
import { useStreamStore } from '@/Stores/StreamStore'
import { useUserStore } from '@/Stores/UserStore'
import { useChatStore } from '@/Stores/ChatStore'
import SingleImage from '@/Components/Global/Multimedia/SingleImage'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { router } from '@inertiajs/vue3'
import ConvertDateTimeToTimeAgo from '@/Components/Global/DateTime/ConvertDateTimeToTimeAgo.vue'

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()
const nowPlayingStore = useNowPlayingStore()
const channelStore = useChannelStore()
const streamStore = useStreamStore()
const chatStore = useChatStore()
const userStore = useUserStore()


let props = defineProps({
  user: Object,
})

onMounted(() => {
  // nowPlayingStore.fetchFirstPlayData();
});

const goToPage = () => {
  router.visit(`/${nowPlayingStore.activeMedia.details?.primaryUrl}`)
  appSettingStore.ott = 0
}

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

// const nowPlayingContent = computed(() => {
//   switch (nowPlayingStore.activeType) {
//     case nowPlayingStore.type[6]: // 'show'
//       return {
//         title: nowPlayingStore.show?.name,
//         description: nowPlayingStore.show.description,
//         image: nowPlayingStore.show?.image,
//         // Add other show-specific properties if needed
//       }
//     case nowPlayingStore.type[7]: // 'movie'
//       return {
//         title: nowPlayingStore.movie?.name,
//         description: nowPlayingStore.movie.description,
//         image: nowPlayingStore.movie?.image,
//         // Add other movie-specific properties if needed
//       }
//     case nowPlayingStore.type[4]: // 'videoFile'
//       return {
//         title: nowPlayingStore.videoFile?.name,
//         description: '', // Add description if available
//         image: null, // If no image for videoFile, handle accordingly
//         // Add other videoFile-specific properties if needed
//       }
//       // Add other cases for different types
//     default:
//       return {title: '', description: '', image: null} // Default or empty state
//   }
// })

// Helper method to get image URL
const getImageUrl = (image) => {
  if(image) {
    return image ? `${image.cdn_endpoint}/${image.folder}/${image.name}` : ''
  }

}

</script>

<style scoped>
.description {
  white-space: pre-wrap; /* CSS property to preserve whitespace and wrap text */
  @apply tracking-wide
}
</style>
