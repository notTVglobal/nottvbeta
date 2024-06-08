<template>
  <div>

    <!--                <div-->
    <!--                    v-if="props.show.firstPlayEpisode.upload_status === 'processing' && !props.show.firstPlayVideoFromUrl"-->
    <!--                    class="ml-3 px-3 py-3 text-gray-50 bg-black w-full text-center lg:text-left">-->
    <!--                  The first episode is currently processing. <br>Please check back later.-->
    <!--                </div>-->
    <button v-if="show?.firstPlayEpisode"
            :class="buttonClass"
            @click="handlePlayEpisode">
      <svg class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg"
           viewBox="0 0 485 485">
        <path d="M413.974,71.026C368.171,25.225,307.274,0,242.5,0S116.829,25.225,71.026,71.026C25.225,116.829,0,177.726,0,242.5
                                                s25.225,125.671,71.026,171.474C116.829,459.775,177.726,485,242.5,485s125.671-25.225,171.474-71.026
                                                C459.775,368.171,485,307.274,485,242.5S459.775,116.829,413.974,71.026z M242.5,455C125.327,455,30,359.673,30,242.5
                                                S125.327,30,242.5,30S455,125.327,455,242.5S359.673,455,242.5,455z"/>
        <polygon points="181.062,336.575 343.938,242.5 181.062,148.425 	"/>
      </svg>

      <span v-if="watchText" class="ml-2">{{ watchText }}</span>
    </button>

    <RestartVideoModal :is-visible="showModal"
                       @restart="restartEpisode"
                       @close="showModal = false" />
  </div>
</template>
<script setup>
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useNowPlayingStore } from '@/Stores/NowPlayingStore'
import { useShowStore } from '@/Stores/ShowStore'
import { computed, onMounted, ref } from 'vue'
import RestartVideoModal from '@/Components/Global/Modals/RestartVideoModal.vue'

const videoPlayerStore = useVideoPlayerStore()
const nowPlayingStore = useNowPlayingStore()
const showStore = useShowStore()

const props = defineProps({
  show: Object,
  team: Object,
})

const isNowPlaying = computed(() => {
  return nowPlayingStore?.activeMedia?.details?.primaryName === props.show?.firstPlayEpisode?.name;
});

const watchText = computed(() => {
  if (isNowPlaying.value) {
    return 'Now Playing';
  } else {
    if (props.show.episodePlayOrder === 'oldest') {
      return 'Watch First Episode';
    } else if (props.show.episodePlayOrder === 'newest') {
      return 'Watch Latest Episode';
    }
  }
});

const buttonClass = computed(() => {
  return [
    'flex text-white font-semibold ml-4 px-4 py-4 rounded transition ease-in-out duration-150 items-center disabled:bg-gray-600 disabled:cursor-not-allowed',
    {
      'bg-green-700 hover:bg-green-800': isNowPlaying.value,
      'bg-green-500 hover:bg-green-400': !isNowPlaying.value,
    }
  ];
});

const showModal = ref(false)

const handlePlayEpisode = () => {
  if (isNowPlaying.value) {
    showModal.value = true;
  } else {
    playEpisode();
  }
};

const restartEpisode = () => {
  showModal.value = false;
  playEpisode();
};

const playEpisode = () => {

  // nowPlayingStore.reset()

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

}
</script>