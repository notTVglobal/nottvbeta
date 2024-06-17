<template>
  <div class="flex flex-row flex-wrap gap-2 mt-2 items-center">

    <div v-if="show?.nextBroadcast" class="flex flex-row flex-wrap gap-2 mt-2 items-center">
      <div
           class="flex flex-col bg-gray-300 border border-gray-500 p-6 rounded-lg shadow-lg justify-center items-center xl:justify-start xl:items-start">
        <h2 class="text-2xl font-semibold mb-4 text-gray-800">Next Broadcast</h2>
        <p class="text-lg">
          <ConvertDateTimeToTimeAgo :dateTime="show?.nextBroadcast?.next_broadcast" :timezone="userStore.timezone"
                                    :class="`text-yellow-700`"/>
        </p>
        <p class="text-lg text-gray-800">
          {{ userStore.formatLongDateTimeFromUtcToUserTimezone(show?.nextBroadcast?.next_broadcast) }}
          {{ userStore.timezoneAbbreviation }}
        </p>


    </div>

      <ZoomLinkButton/>
    </div>

    <div v-if="showStore?.isLive" class="flex flex-col justify-center ml-5">

      <div class="flex text-yellow-400">
        Started&nbsp;
        <ConvertDateTimeToTimeAgo
            :key="scheduleStore.baseTime"
            v-if="showStore?.liveScheduledStartTime"
            :dateTime="showStore?.liveScheduledStartTime"
            :class="`text-yellow-400`"/>
      </div>

      <button @click="watchNow" class="btn bg-red-600 hover:bg-red-700 text-white">
        <span v-if="isWatchingLive">Now Playing</span>
        <span v-else>Watch Live Now</span>
      </button>
    </div>
  </div>
</template>
<script setup>
import { useUserStore } from '@/Stores/UserStore'
import { useShowStore } from '@/Stores/ShowStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useNowPlayingStore } from '@/Stores/NowPlayingStore'
import { useScheduleStore } from '@/Stores/ScheduleStore'
import { useTeamStore } from '@/Stores/TeamStore'
import ConvertDateTimeToTimeAgo from '@/Components/Global/DateTime/ConvertDateTimeToTimeAgo.vue'
import { computed, onMounted } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import ZoomLinkButton from '@/Components/Global/Buttons/ZoomLinkButton.vue'

const userStore = useUserStore()
const showStore = useShowStore()
const videoPlayerStore = useVideoPlayerStore()
const nowPlayingStore = useNowPlayingStore()
const scheduleStore = useScheduleStore()
const teamStore = useTeamStore()

const page = usePage()

const props = defineProps({
  show: Object,
  team: Object,
})

onMounted(async () => {
  await showStore.checkIsLive(props.show.slug)
})

// Computed property to determine if the current video source includes the live stream name
const isWatchingLive = computed(() => {
  return videoPlayerStore.videoSource.includes(showStore.liveMistStreamName)
})

const watchNow = async () => {
  if (!page.props.auth.user) {
    router.visit('/')
  }

  const mediaType = 'mistStream' // Use the new 'mediaType' from the backend

  const videoDetails = {
    // Assuming video details are structured correctly in your episode data
    source: showStore.liveMistStreamName,
    type: 'application/x-mpegURL', // MIME type for video.js
  }

  // Common details for nowPlayingStore
  const commonDetails = {
    primaryName: props.show.name, // Show or Movie name
    // secondaryName: props.show.name, // Episode name
    primaryUrl: `shows/${props.show.slug}`,
    // secondaryUrl: `shows/${props.show.slug}`,
    channelName: '',
    image: props.show.image,
    team: props.team,
    creative_commons: props.show.firstPlayEpisode.creative_commons,
    category: props.show?.category.name,
    subCategory: props.show?.subCategory.name,
    // release_year: '',
    // copyrightYear: props.show.firstPlayEpisode.copyrightYear,
    description: props.show.description,
    // releaseDateTime: props.show.firstPlayEpisode.release_dateTime,
    // episodeNumber: props.show.firstPlayEpisode.episode_number,
    // episodeId: props.show.firstPlayEpisode.ulid,
  }

  // Set the currently playing media in nowPlayingStore
  nowPlayingStore.setActiveMedia(mediaType, {
    ...commonDetails,
    videoDetails, // Spread in the specific details for internal or external video
  })

  console.log(showStore.liveMistStreamName)
  // videoPlayerStore.loadNewVideo(videoDetails)
  await videoPlayerStore.loadMistStreamVideo(showStore.liveMistStreamName)

}
</script>