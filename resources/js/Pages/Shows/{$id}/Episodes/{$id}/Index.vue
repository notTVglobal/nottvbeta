<template>

  <Head :title="`${show.name}: ${episode.name}`"/>


  <div class="place-self-center flex flex-col gap-y-3 overflow-x-hidden">
    <div id="topDiv" class="text-white bg-gray-900 rounded py-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <div v-if="can.editShow || can.manageShow"
           class="flex justify-end">

        <div class="flex flex-col">
          <div class="flex flex-end flex-wrap-reverse justify-end gap-2 mr-4 my-4">
              <button
                  @click="appSettingStore.btnRedirect(`/teams/${team.slug}/manage`)"
                  class="px-4 py-2 h-fit text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
              >Back to<br />
                Team Page
              </button>
              <button
                  v-if="can.manageShow"
                  @click="appSettingStore.btnRedirect(`/shows/${show.slug}/manage`)"
                  class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
              >Back to<br />
                Manage Show
              </button>
              <button
                  v-if="can.manageShow"
                  @click="appSettingStore.btnRedirect(`/shows/${show.slug}/episode/${episode.slug}/manage`)"
                  class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
              >Back to<br />
                Manage Episode
              </button>
          </div>
          <div class="flex flex-end flex-wrap-reverse justify-end gap-2 mr-4">
            <button
                v-if="can.editShow"
                @click="appSettingStore.btnRedirect(`/shows/${show.slug}/episode/${episode.slug}/edit`)"
                class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
            >Edit
            </button>
          </div>
        </div>
      </div>

        <header class="p-5 mb-6">
          <div class="flex flex-col md:flex-row flex-wrap justify-between px-5">
            <div class="w-full md:w-3/4">
              <div class="mb-4">
                <h3 class="mb-1 inline-flex items-center text-3xl font-semibold relative">


                  {{ episode.name }}


                </h3>
                <div class="mb-1">
                  <span class="font-semibold text-xl hover:text-blue-500 hover:cursor-pointer">
                      <Link :href="`/shows/${show.slug}/`">{{ show.name }}</Link>
                  </span>
                </div>
                <Link :href="`/teams/${team.slug}`" class="text-blue-300 hover:text-blue-500"><span
                    class="text-sm uppercase font-semibold">{{ team.name }}</span></Link>
                <div class="text-xs space-y-1">

                </div>
              </div>
              <div v-if="episode.release_dateTime" class="text-yellow-500">
                {{ formatDate(episode.release_dateTime) }}
              </div>
              <ConvertDateTimeToTimeAgo v-if="episode.scheduled_release_dateTime"
                                        :dateTime="episode.scheduled_release_dateTime" :class="`text-green-400`"/>


              <div class="text-gray-500 mt-1" v-if="!episode.episode_number">Episode {{ episode.id }}</div>
              <div class="text-gray-500 mt-1" v-if="episode.episode_number">Episode {{
                  episode.episode_number
                }}
              </div>
            </div>

            <div class="flex flex-col text-left md:text-right w-full md:w-1/4">
            <span class="text-lg uppercase justify-end tracking-wider text-yellow-700">{{
                show.category.name
              }}</span>
              <span class="tracking-wide text-yellow-500">{{ show.subCategory.name }}</span>
            </div>

          </div>

          <p v-if="episode.video.upload_status === 'processing' && !episode.video.video_url"
             class="mt-12 px-3 py-3 text-gray-50 mr-1 lg:mr-36 bg-black w-full text-center lg:text-left">
            The episode video is currently processing. Please check back later.
          </p>

          <div class="flex flex-wrap mt-12 m-auto lg:mx-0 justify-center lg:justify-start space-x-3 space-y-3">
            <div></div>
            <button v-if="episode.video.isAvailable"
                    class="flex bg-blue-500 text-white font-semibold ml-4 px-4 py-4 hover:bg-blue-700 rounded transition ease-in-out duration-150 items-center disabled:bg-gray-600 disabled:cursor-not-allowed"
                    @click="playEpisode">
              <svg class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg"
                   viewBox="0 0 485 485">
                <path d="M413.974,71.026C368.171,25.225,307.274,0,242.5,0S116.829,25.225,71.026,71.026C25.225,116.829,0,177.726,0,242.5
		s25.225,125.671,71.026,171.474C116.829,459.775,177.726,485,242.5,485s125.671-25.225,171.474-71.026
		C459.775,368.171,485,307.274,485,242.5S459.775,116.829,413.974,71.026z M242.5,455C125.327,455,30,359.673,30,242.5
		S125.327,30,242.5,30S455,125.327,455,242.5S359.673,455,242.5,455z"/>
                <polygon points="181.062,336.575 343.938,242.5 181.062,148.425 	"/>
              </svg>
              <span v-if="nowPlayingStore?.activeMedia?.details?.primaryName === episode?.name"
                    class="ml-2">Now Playing</span>
              <span v-else class="ml-2">Watch Episode</span>
            </button>

            <div class="p-3 border-2 border-green-500">
              <div class="text-green-500 mb-2">Coming Soon! (These buttons are currently being built)</div>
              <div class="flex flex-row">
                <button v-if="userStore.isVip || userStore.isAdmin"
                        disabled
                        class="flex bg-blue-500 text-white font-semibold ml-4 px-4 py-4 hover:bg-blue-400 rounded transition ease-in-out duration-150 items-center disabled:bg-gray-600 disabled:cursor-not-allowed">
                  <span class=""><font-awesome-icon icon="fa-circle-down" class="mr-2"/>Save For Later</span>
                </button>

                <button v-if="userStore.isVip || userStore.isAdmin"
                        disabled
                        class="flex bg-blue-500 text-white font-semibold ml-4 px-4 py-4 hover:bg-blue-400 rounded transition ease-in-out duration-150 items-center disabled:bg-gray-600 disabled:cursor-not-allowed">
                  <span class=""><font-awesome-icon icon="fa-share" class="mr-2"/>Share</span>
                </button>
              </div>

            </div>

          </div>

        </header>

        <div class="my-6 py-5 px-8">
          <div class="font-semibold text-xs uppercase mb-3">EPISODE DESCRIPTION</div>
          <div class="description">{{ episode.description }}</div>
        </div>


        <div
            class="flex flex-wrap justify-center shadow overflow-hidden border-y border-gray-200 w-full bg-black text-light text-2xl sm:rounded-lg p-5">
          <!--            <div class="flex flex-wrap items-start ml-5 py-0">-->
          <div class="max-w-[50%] ml-5 py-0">
            <SingleImage :image="image" :key="image"/>

          </div>

          <!--                                <img :src="'/storage/images/' + props.episode.poster" alt="" class="w-1/2 mx-2">-->


        </div>

        <div class="flex flex-col px-5">
          <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

              <div class="mb-6 p-5">

                <div v-if="episode?.bonusContent">
                  <div class="w-full bg-gray-800 text-2xl p-4 mb-8">BONUS CONTENT</div>
                  <div class="mb-8 p-4">
                <span
                    class="text-orange-500">Bonus content will go here. This includes content mentioned in an episode.</span>
                  </div>
                </div>



                <div class="w-full bg-gray-800 text-2xl p-4 mb-8">CREDITS</div>


                <div class="flex flex-row flex-wrap">
                  <div v-for="creator in props.creators.data"
                       :key="creator.id"
                       class="pb-8 light:bg-light dark:bg-gray-900">

                    <div class="flex flex-col min-w-[8rem] px-6 py-4 font-medium break-words grow-0">
                      <img :src="'/storage/' + creator.profile_photo_path"
                           class="pb-2 rounded-full h-32 w-32 object-cover mb-2">
                      <span class="light:text-gray-800 dark:text-gray-200 w-full text-center">{{ creator.name }}</span>
                    </div>

                    <!--                            For now, we are just displaying the team members here.
                                                    This will make a good component that can be re-used across
                                                    the Show and Episode Index pages. Just pass in the creators prop.

                                                    We will add this when we have our Creators model setup
                                                    and creators attached to the credits table for this
                                                    show.                                                       -->

                    <!--                            <ShowCreatorsList />-->

                  </div>
                </div>

              </div>

              <EpisodeFooter :can="can" :team="team" :episode="episode" :show="show"/>
            </div>
          </div>
        </div>

      </div>
    </div>

</template>


<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useNowPlayingStore } from '@/Stores/NowPlayingStore'
import { useTeamStore } from '@/Stores/TeamStore'
import { useUserStore } from '@/Stores/UserStore'
import EpisodeFooter from '@/Components/Pages/ShowEpisodes/Layout/EpisodeFooter'
import SingleImage from '@/Components/Global/Multimedia/SingleImage'
import Message from '@/Components/Global/Modals/Messages'
import ConvertDateTimeToTimeAgo from '@/Components/Global/DateTime/ConvertDateTimeToTimeAgo.vue'
import { onMounted } from 'vue'

usePageSetup('showEpisodesShow')

const appSettingStore = useAppSettingStore()
const nowPlayingStore = useNowPlayingStore()
const videoPlayerStore = useVideoPlayerStore()
const teamStore = useTeamStore()
const userStore = useUserStore()

let props = defineProps({
  show: Object,
  team: Object,
  episode: Object,
  image: Object,
  creators: Object,
  can: Object,
})

onMounted(() => {
  const topDiv = document.getElementById('topDiv')
  topDiv.scrollIntoView()
})

let playEpisode = () => {
  nowPlayingStore.reset()

  // Determine media type and specific details based on the video type
  const episode = props.episode
  const show = props.show
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
    primaryName: episode.name, // Show or Movie name
    secondaryName: show.name, // Episode name
    primaryUrl: `shows/${show.slug}/episode/${episode.slug}`,
    secondaryUrl: `shows/${show.slug}`,
    channelName: '',
    image: show.image,
    team: props.team,
    creative_commons: episode.creative_commons,
    category: show.category,
    subCategory: show.subCategory,
    release_year: '',
    copyrightYear: episode.copyrightYear,
    logline: '',
    description: episode.description,
    releaseDateTime: episode.release_dateTime,
    episodeNumber: episode.episode_number,
    episodeId: episode.ulid,
  }

  // Set the currently playing media in nowPlayingStore
  nowPlayingStore.setActiveMedia(mediaType, {
    ...commonDetails,
    videoDetails, // Spread in the specific details for internal or external video
  })

  // Load the video source in videoPlayerStore for playback
  // if (isInternalVideo) {
  //   videoPlayerStore.loadNewSourceFromFile(props.episode.video)
  // } else if (isExternalVideo) {
  //   videoPlayerStore.loadNewSourceFromUrl({video_url: props.episode.video.video_url, type: 'video/mp4'})
  // }

  // Load the video source in videoPlayerStore for playback
  if (isInternalVideo) {
    // For internal videos, load using the episode video directly
    videoPlayerStore.loadNewSourceFromFile(episode.video)
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
  // Inertia.visit('/stream');


//
//
//   nowPlayingStore.activeType = 6
//   nowPlayingStore.show.name = props.show.name
//   nowPlayingStore.show.description = props.show.description
//   nowPlayingStore.show.url = `/shows/${props.show.slug}`
//   nowPlayingStore.show.episode.name = props.episode.name
//   nowPlayingStore.show.episode.url = `/shows/${props.show.slug}/episode/${props.episode.slug}`
//   nowPlayingStore.show.episode.image = props.image
//   nowPlayingStore.show.category = props.show.categoryName
//   nowPlayingStore.show.categorySub = props.show.categorySubName
//   nowPlayingStore.show.image = props.show.image
//   videoPlayerStore.makeVideoFullPage()
//   Inertia.visit('/stream')
//   // if video has a file and is !processing, play file.
//   if (props.episode.video.storage_location === 'spaces' && props.episode.video.upload_status !== 'processing') {
//     videoPlayerStore.loadNewSourceFromFile(props.episode.video)
//   } else if
//       // else if url exists, play url
//   (props.episode.video.video_url) {
//     nowPlayingStore.isFromWeb = true
//     nowPlayingStore.show.episode.name = props.episode.name
//     videoPlayerStore.loadNewSourceFromUrl(props.episode.video)
//
//   } else if
//       // else if youtube_url exists, play youtube_url
//   (props.episode.youtube_url) {
//     nowPlayingStore.show.episode.name = props.episode.name
//     videoPlayerStore.loadNewSourceFromYouTube(props.episode.youtube_url)
//   }
}

teamStore.slug = props.team.slug
teamStore.name = props.team.name

function scrollTo(selector) {
  document.querySelector(selector).scrollIntoView({behavior: 'smooth'})
}

//
// function checkForVideo() {
//     if (props.video.file_name && props.video.upload_status !== 'processing') {
//         videoPlayerStore.hasVideo = true;
//     } if (props.episode.youtube_url) {
//         videoPlayerStore.hasVideo = true;
//     } else
//     if (props.episode.video.video_url) {
//         videoPlayerStore.hasVideo = true;
//     } else
//     if (!props.episode.video.video_url && props.video.upload_status === 'processing'){
//         videoPlayerStore.hasVideo = false;
//     } else if (!props.video.file_name && !props.episode.video.video_url) {
//         videoPlayerStore.hasVideo = false;
//     } return true;
// }
//
// checkForVideo()

</script>

<style scoped>
.description {
  white-space: pre-wrap; /* CSS property to preserve whitespace and wrap text */
  @apply tracking-wide
}
</style>