<template>
  <div class="flex flex-row flex-wrap gap-2 mt-2 items-center">


    <div v-if="show?.nextBroadcast" class="flex flex-row flex-wrap gap-4 mt-4 items-center">
      <div v-if="!showStore?.isLive"
          class="flex flex-col bg-gray-900 border border-yellow-500 p-8 rounded-lg shadow-lg justify-center items-center xl:justify-start xl:items-start transition-transform transform hover:scale-105">
        <h2 class="text-3xl font-bold mb-4 text-yellow-400">Next Broadcast</h2>
        <p class="text-lg">
          <ConvertDateTimeToTimeAgo :dateTime="show?.nextBroadcast?.next_broadcast" :timezone="userStore.timezone"
                                    :class="`text-yellow-500`"/>
        </p>
        <p class="text-xl text-yellow-300 mb-2">
          {{ dayjs(show?.nextBroadcast?.next_broadcast).tz(userStore.timezone).format('MMMM D, YYYY h:mm A') }}
          {{ userStore.timezoneAbbreviation }}
        </p>
        <p class="text-lg text-gray-400">

        </p>
      </div>

      <ZoomLinkButton/>
    </div>


    <div v-if="showStore?.isLive" class="flex flex-col justify-center ml-5 space-y-3">

      <!-- Time Display -->
      <div class="flex items-center text-yellow-400">
        <span class="mr-1">Started</span>
        <ConvertDateTimeToTimeAgo
            :key="scheduleStore.baseTime"
            v-if="showStore?.liveScheduledStartTime"
            :dateTime="showStore?.liveScheduledStartTime"
            class="text-yellow-400"
        />
      </div>

      <!-- Watch Button -->
      <button
          @click="watchNow"
          :class="isWatchingLive ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700'"
          class="btn text-white px-6 py-2 rounded-md shadow-md transition duration-300 ease-in-out"
      >
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
import dayjs from 'dayjs'
import utc from 'dayjs/plugin/utc'
import timezone from 'dayjs/plugin/timezone'

const userStore = useUserStore()
const showStore = useShowStore()
const videoPlayerStore = useVideoPlayerStore()
const nowPlayingStore = useNowPlayingStore()
const scheduleStore = useScheduleStore()
const teamStore = useTeamStore()

dayjs.extend(utc)
dayjs.extend(timezone)

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
    primaryName: props.show?.name ?? 'Unknown Name', // Fallback to 'Unknown Name' if null or undefined
    primaryUrl: `shows/${props.show?.slug ?? 'unknown'}`, // Fallback to 'unknown' if slug is null or undefined
    channelName: '', // Assuming this is intentionally left empty
    image: props.show?.image ?? 'default-image.jpg', // Fallback to a default image if null or undefined
    team: props.team ?? 'Unknown Team', // Fallback to 'Unknown Team' if null or undefined
    creative_commons: props.show?.firstPlayEpisode?.creative_commons ?? 'Unknown License', // Fallback to 'Unknown License'
    category: props.show?.category?.name ?? 'Uncategorized', // Fallback to 'Uncategorized'
    subCategory: props.show?.subCategory?.name ?? 'No Subcategory', // Fallback to 'No Subcategory'
    description: props.show?.description ?? 'No description available', // Fallback to 'No description available'
    // Optional properties can also have fallbacks if needed
    // release_year: '',
    // copyrightYear: props.show?.firstPlayEpisode?.copyrightYear ?? 'Unknown Year',
    // releaseDateTime: props.show?.firstPlayEpisode?.release_dateTime ?? 'Unknown Date',
    // episodeNumber: props.show?.firstPlayEpisode?.episode_number ?? 'Unknown Episode',
    // episodeId: props.show?.firstPlayEpisode?.ulid ?? 'Unknown ID',
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