<template>

  <Head :title="props.show.name"/>

  <div id="topDiv" class="place-self-center flex flex-col h-screen">
    <div class="min-h-screen w-full bg-gray-900 text-gray-50 pt-6 mt-16 overflow-y-scroll">

      <PublicNavigationMenu/>
      <PublicResponsiveNavigationMenu/>

      <header class="container mx-auto px-4 gap-y-3 flex justify-end">

        <button
            @click="appSettingStore.btnRedirect(`/teams/${team.slug}`)"
            class="px-4 py-2 mr-2 h-fit text-white bg-orange-600 hover:bg-orange-500 rounded-lg shadow-md"
        >Visit the<br/>
          Team Page
        </button>

      </header>

      <main>
        <div class="container mx-auto px-4 gap-y-3 mt-12 rounded sm:rounded-lg shadow">
          <div class="show-details border-b border-gray-800 pb-12 flex flex-col md:flex-row">
            <div class="items-center relative">
              <!--                        <SingleImage :image="props.show.image" :poster="props.show.poster" :alt="'show cover'" class="h-96 min-w-[16rem] w-64 object-cover mb-6 lg:mb-0 m-auto lg:m-0"/>-->
              <div v-if="show.status.id === 9" class="absolute flex justify-end w-full -mt-3 z-50">
                <CreatorsOnlyBadge/>
              </div>
              <SingleImage :image="props.show.image" :alt="'show cover'"
                           class="h-96 min-w-[16rem] w-64 object-cover mb-6 lg:mb-0 m-auto lg:m-0"/>

            </div>
            <div class="lg:ml-12 lg:mr-0">
              <div @click="Inertia.visit(`/teams/${team.slug}`)"
                   class="text-gray-200 hover:text-blue-500 hover:cursor-pointer tracking-wide"> {{ team.name }}
              </div>
              <h2 class="font-semibold text-4xl text-center lg:text-left">{{ show.name }}</h2>
              <div class="text-gray-400 text-center lg:text-left">
                <div class="mt-1">
                  <span class="uppercase tracking-wider text-yellow-700">{{ show.category.name }}</span>
                  &nbsp;&middot;&nbsp;
                  <span class="tracking-wide text-yellow-500">{{ show.subCategory.name }}</span>
                  <span v-if="show.last_release_year"> &nbsp;&middot;&nbsp; {{
                      show.first_release_year
                    }}-{{ show.last_release_year }}</span>
                  <span v-if="!show.last_release_year && show.first_release_year"> &nbsp;&middot;&nbsp; {{
                      show.first_release_year
                    }}</span>
                  <span v-if="!show.last_release_year && !show.first_release_year"> &nbsp;&middot;&nbsp; {{
                      show.copyrightYear
                    }}</span>
                </div>
                <div class="flex flex-row">

                </div>

                <!--                                This will need a change in the database to allow the creator to update
                                                    the year(s) if they are incorrect. It can automatically set the first release year
                                                    to the same year that the show is created. And the final release year to the year that
                                                    the show was last updated (created_at and updated_at timestamps) -->
                <!--                                <span>{{show.copyrightYear}}-{{new Date().getFullYear()}}</span>-->

              </div>

              <div class="flex flex-wrap my-2 m-auto lg:mx-0 justify-center lg:justify-start space-x-4 space-y-2">
                <div></div>
                <!--                <div-->
                <!--                    v-if="props.show.firstPlayEpisode.upload_status === 'processing' && !props.show.firstPlayVideoFromUrl"-->
                <!--                    class="ml-3 px-3 py-3 text-gray-50 bg-black w-full text-center lg:text-left">-->
                <!--                  The first episode is currently processing. <br>Please check back later.-->
                <!--                </div>-->
                <button v-if="show?.firstPlayEpisode"
                        class="flex bg-blue-500 text-white font-semibold ml-4 px-4 py-4 hover:bg-blue-400 rounded transition ease-in-out duration-150 items-center disabled:bg-gray-600 disabled:cursor-not-allowed"
                        @click="playEpisode">
                  <svg class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg"
                       viewBox="0 0 485 485">
                    <path d="M413.974,71.026C368.171,25.225,307.274,0,242.5,0S116.829,25.225,71.026,71.026C25.225,116.829,0,177.726,0,242.5
                                                s25.225,125.671,71.026,171.474C116.829,459.775,177.726,485,242.5,485s125.671-25.225,171.474-71.026
                                                C459.775,368.171,485,307.274,485,242.5S459.775,116.829,413.974,71.026z M242.5,455C125.327,455,30,359.673,30,242.5
                                                S125.327,30,242.5,30S455,125.327,455,242.5S359.673,455,242.5,455z"/>
                    <polygon points="181.062,336.575 343.938,242.5 181.062,148.425 	"/>
                  </svg>

                  <span
                      v-if="nowPlayingStore?.activeMedia?.details?.primaryName === show?.firstPlayEpisode?.name"
                      class="ml-2">Now Playing</span>
                  <span v-else class="ml-2">Watch Now</span>
                </button>


                <ComingSoonShareAndSaveButtons/>

              </div>

              <SocialMediaBadgeLinks :socialMediaLinks="show.socialMediaLinks"/>

              <div class="description mt-12 pr-6 text-gray-300 mr-1 lg:mr-36 w-full text-center lg:text-left">
                {{ show.description }}
              </div>

            </div>
          </div>
        </div>
      </main>

      <div class="flex flex-col px-5 border-b border-blue-50">
        <div class="-my-2 overflow-x-hidden sm:-mx-6 lg:-mx-8">
          <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">


            <div class="mb-6 p-5">


              <ShowEpisodesList :episodes="episodes" :show="show"/>

              <div hidden class="container mx-auto px-4 mb-12">
                <div class="w-full bg-gray-800 text-2xl p-4 mb-4">CREDITS</div>

                <div class="flex flex-row flex-wrap justify-start">
                  <div v-for="creator in creators" :key="creator.id" class="pb-8 mx-auto lg:mx-0">
                    <div class="flex flex-col items-center max-w-[8rem] px-3 py-4 font-medium break-words">
                      <img v-if="!creator.profile_photo_path" :src="creator.profile_photo_url"
                           class="rounded-full h-20 w-20 min-h-20 min-w-20 object-cover mb-2">
                      <img v-if="creator.profile_photo_path" :src="'/storage/' + creator.profile_photo_path"
                           class="rounded-full h-20 w-20 min-h-20 min-w-20 object-cover mb-2">
                      <span class="text-gray-200 text-center text-sm">{{ creator.name }}</span>
                    </div>
                  </div>
                </div>

                <!-- Paginator -->
                <!--                <Pagination :data="props.creators" class="mb-6 pb-6 border-b border-gray-800"/>-->
              </div>


              <!--                            For now, we are just displaying the team members here.
                                              This will make a good component that can be re-used across
                                              the Show and Episode Index pages. Just pass in the creators prop.

                                              We will add this when we have our Creators model setup
                                              and creators attached to the credits table for this
                                              show.                                                       -->

              <!--                            <ShowCreatorsList />-->
              <div v-if="show?.bonusContent" class="container mx-auto px-4 mb-12">
                <div class="w-full bg-gray-800 text-2xl p-4 mb-8">BONUS CONTENT</div>
              </div>


            </div>
            <div class="container mx-auto px-4 gap-y-3">
              <ShowFooter :team="props.team" :show="props.show"/>
            </div>
          </div>
        </div>
      </div>

      <Footer/>

    </div>
  </div>

</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { useNowPlayingStore } from '@/Stores/NowPlayingStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useTeamStore } from '@/Stores/TeamStore'
import ShowEpisodesList from '@/Components/Pages/Shows/Elements/ShowEpisodesList'
import ShowFooter from '@/Components/Pages/Shows/Layout/ShowFooter'
import SingleImage from '@/Components/Global/Multimedia/SingleImage'
import Pagination from '@/Components/Global/Paginators/PaginationDark'
import Message from '@/Components/Global/Modals/Messages.vue'
import CreatorsOnlyBadge from '@/Components/Global/Badges/CreatorsOnlyBadge.vue'
import { onMounted, ref } from 'vue'
import SocialMediaBadgeLinks from '@/Components/Global/Badges/SocialMediaBadgeLinks.vue'
import SaveForLaterButton from '@/Components/Global/UserActions/SaveForLaterButton.vue'
import ShareButton from '@/Components/Global/UserActions/ShareButton.vue'
import ComingSoonShareAndSaveButtons from '@/Components/Global/UserActions/ComingSoonShareAndSaveButtons.vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import Footer from '@/Components/Global/Layout/Footer.vue'
// import ShowCreatorsList from "@/Components/Pages/Shows/ShowCreatorsList"


const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()
const nowPlayingStore = useNowPlayingStore()
const teamStore = useTeamStore()
const userStore = useUserStore()

let props = defineProps({
  show: Object,
  episodes: Object,
  creators: Object,
  team: Object,
})

appSettingStore.currentPage = `teams.${props.team.slug}`
appSettingStore.setPrevUrl()


const socialMediaLinks = [
  {
    www_url: props.show.www_url,
    instagram_name: props.show.instagram_name,
    telegram_url: props.show.telegram_url,
    twitter_handle: props.show.twitter_handle,
  },
]

console.log('social media: ' + socialMediaLinks[0].www_url)

// Function to handle scrolling
const scrollToTop = () => {
  requestAnimationFrame(() => {
    const topDiv = document.getElementById('topDiv')
    if (topDiv) {
      // Smooth scroll to the 'topDiv' element
      topDiv.scrollIntoView({behavior: 'smooth'})
    } else {
      // Fallback: smooth scroll to the top of the page
      window.scrollTo({top: 0, behavior: 'smooth'})
    }
  })
}
scrollToTop() // Optionally scroll to top when the component mounts

onMounted(() => {
  const topDiv = document.getElementById('topDiv')
  topDiv.scrollIntoView()
})

let playEpisode = () => {

  nowPlayingStore.reset()

  // Determine media type and specific details based on the video type
  const episode = props.show.firstPlayEpisode
  const mediaType = episode.video ? episode.video.mediaType : null // Use the new 'mediaType' from the backend

  const isInternalVideo = mediaType === 'show'
  const isExternalVideo = mediaType === 'externalVideo'
  const isBitchute = mediaType === 'bitchute'

  const videoDetails = {
    // Assuming video details are structured correctly in your episode data
    video_url: episode.video ? episode.video.video_url : '',
    type: episode.video ? episode.video.type : 'video/mp4', // MIME type for video.js
  }

  // Common details for nowPlayingStore
  const commonDetails = {
    primaryName: props.show.firstPlayEpisode.name, // Show or Movie name
    secondaryName: props.show.name, // Episode name
    primaryUrl: `shows/${props.show.slug}/episode/${props.show.firstPlayEpisode.slug}`,
    secondaryUrl: `shows/${props.show.slug}`,
    channelName: '',
    image: props.show.image,
    team: props.team,
    creative_commons: props.show.firstPlayEpisode.creative_commons,
    category: props.show.category,
    subCategory: props.show.subCategory,
    release_year: '',
    copyrightYear: props.show.firstPlayEpisode.copyrightYear,
    description: props.show.firstPlayEpisode.description,
    releaseDateTime: props.show.firstPlayEpisode.release_dateTime,
    episodeNumber: props.show.firstPlayEpisode.episode_number,
    episodeId: props.show.firstPlayEpisode.ulid,
  }

  // Set the currently playing media in nowPlayingStore
  nowPlayingStore.setActiveMedia(mediaType, {
    ...commonDetails,
    videoDetails, // Spread in the specific details for internal or external video
  })

// Load the video source in videoPlayerStore for playback
  if (isInternalVideo) {
    // For internal videos, load using the episode video directly
    // videoPlayerStore.loadNewSourceFromFile(episode.video);


    videoPlayerStore.loadNewVideo(episode.video)
    // videoPlayerStore.loadVideoFromFile(episode.video);

  } else if (isExternalVideo) {
    // For external videos, focus on the video_url and type provided within the episode's video details
    if (episode.video && episode.video.video_url) {
      videoPlayerStore.loadNewSourceFromUrl({
        video_url: episode.video.video_url,
        type: episode.video.type, // This assumes that 'type' is correctly set to 'video/mp4' or appropriate video MIME type
      })
    }
  } else if (isBitchute) {
    console.log('it\'s bitchute!')
    videoPlayerStore.loadNewSourceFromUrl({
      video_url: videoPlayerStore.mistServerUri + episode.video.mist_stream.name + '.mp4',
      type: episode.video.mist_stream.mime_type, // This assumes that 'type' is correctly set to 'video/mp4' or appropriate video MIME type
    })
  }


  appSettingStore.ott = 1

  if (videoPlayerStore.player) {
    console.log('player is initialized...')
    console.log('disposing player...')
    setTimeout(() => {
      videoPlayerStore.disposePlayer()
    }, 1000) // Delay the disposal by 1000 milliseconds (1 second)
  }

}

let thisYear = new Date().getFullYear()

teamStore.slug = props.team.slug
teamStore.name = props.team.name


</script>
<script>
import NoLayout from '@/Layouts/NoLayout'

export default {
  layout: NoLayout,
}
</script>

<style scoped>
.description {
  overflow: hidden;
  white-space: pre-wrap; /* CSS property to preserve whitespace and wrap text */
  text-overflow: ellipsis;
  @apply tracking-wide
}
</style>



