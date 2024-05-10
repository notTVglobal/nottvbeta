<template>
  <div>
    <div class="container mx-auto px-4 gap-y-3 rounded sm:rounded-lg shadow">
      <div class="show-details border-b border-gray-800 pb-12 flex flex-col xl:flex-row">
        <div class="items-center relative">
          <!--                        <SingleImage :image="props.show.image" :poster="props.show.poster" :alt="'show cover'" class="h-96 min-w-[16rem] w-64 object-cover mb-6 lg:mb-0 m-auto lg:m-0"/>-->
          <div v-if="show.status.id === 9" class="absolute flex justify-end w-full -mt-3 z-50">
            <CreatorsOnlyBadge/>
          </div>
<!--          <SingleImage :image="props.show.image" :alt="'show cover'"-->
<!--                       class="max-h-96 min-w-[16rem] max-w-64 object-cover mb-6 xl:mb-0 m-auto xl:m-0"/>-->
          <SingleImageWithModal :image="props.show.image" :alt="'show cover'"
                                class="max-h-96 min-w-[16rem] max-w-64 object-cover mb-6 xl:mb-0 m-auto xl:m-0"/>

        </div>
        <div class="flex flex-col xl:ml-12 xl:mr-0 w-full justify-center items-center xl:items-start xl:justify-start">
          <div @click="Inertia.visit(`/teams/${team.slug}`)"
               class="text-center xl:text-left text-blue-500 hover:text-blue-400 transition hover:cursor-pointer tracking-wide">
            {{ team.name }}
          </div>
          <h2 class="font-semibold text-4xl text-center lg:text-left">{{ show.name }}</h2>
          <div class="text-gray-400 text-center lg:text-left">
            <div class="mt-1">
              <span class="uppercase tracking-wider text-yellow-700">{{ show?.category?.name }}</span>
              &nbsp;&middot;&nbsp;
              <span class="tracking-wide text-yellow-500">{{ show?.subCategory?.name }}</span>
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

          <div class="">
          <SocialMediaBadgeLinks :socialMediaLinks="show.socialMediaLinks"/>
          </div>


          <div class="flex flex-wrap mt-5 m-auto xl:mx-0 justify-center xl:justify-start">
            <div class="flex flex-wrap gap-x-4 gap-y-2 ">
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
          </div>


          <div class="description mt-2 w-full text-gray-300 text-center xl:text-left">
            <expandable-description :description="show.description" :hideTitle="true"/>
          </div>


        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { Inertia } from '@inertiajs/inertia'
import { useNowPlayingStore } from '@/Stores/NowPlayingStore'
import CreatorsOnlyBadge from '@/Components/Global/Badges/CreatorsOnlyBadge.vue'
import ComingSoonShareAndSaveButtons from '@/Components/Global/UserActions/ComingSoonShareAndSaveButtons.vue'
import SocialMediaBadgeLinks from '@/Components/Global/Badges/SocialMediaBadgeLinks.vue'
import SingleImage from '@/Components/Global/Multimedia/SingleImage'
import ExpandableDescription from '@/Components/Global/Text/ExpandableDescription.vue'
import SingleImageWithModal from '@/Components/Global/Multimedia/SingleImageWithModal.vue'

const nowPlayingStore = useNowPlayingStore()

const props = defineProps({
  show: Object,
  team: Object,
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
    category: props.show?.category,
    subCategory: props.show?.subCategory,
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

</script>
