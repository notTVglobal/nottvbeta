<template>
  <div class="mx-0 md:mx-4 mt-4 mb-2 px-2 md:px-6 py-1">
    <div
        class="text-sm font-semibold  text-white text-center w-full border-2 rounded uppercase px-0 md:px-6 py-1 "
        :class="[ goLiveStore.isLive ? 'bg-gray-600 border-gray-600 ' : 'bg-red-600 border-red-600' ]">
      <span v-if="goLiveStore.isLive">Now Live</span><span v-else>Live Setup</span>
    </div>
    <div class="shadow overflow-hidden border-2 border-red-600 rounded p-2 md:p-6"
         :class="[ goLiveStore.isLive ? 'bg-gray-100' : 'bg-red-100' ]">

      <GoLiveHeader />
      <GoLiveAuxVideoPlayer />
      <GoLivePushDestinations />

    </div>

    <GoLiveCommercialBreaks />

  </div>
</template>

<script setup>
import { onMounted, watchEffect } from 'vue'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useVideoAuxPlayerStore } from '@/Stores/VideoAuxPlayerStore'
import { useGoLiveStore } from '@/Stores/GoLiveStore'
import { useMistStore } from '@/Stores/MistStore'

import GoLiveHeader from '@/Components/Pages/GoLive/GoLiveHeader.vue'
import GoLiveAuxVideoPlayer from '@/Components/Pages/GoLive/GoLiveAuxVideoPlayer.vue'
import GoLivePushDestinations from '@/Components/Pages/GoLive/GoLivePushDestinations.vue'
import GoLiveCommercialBreaks from '@/Components/Pages/GoLive/GoLiveCommercialBreaks.vue'
import videojs from 'video.js'

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()
const videoAuxPlayerStore = useVideoAuxPlayerStore()
const goLiveStore = useGoLiveStore()
const mistStore = useMistStore()

// Initialize fetching of server information
goLiveStore.updateAndGetStreamKey()
goLiveStore.fetchStreamInfo(goLiveStore?.selectedShow?.mist_stream_wildcard.name)
goLiveStore.fetchRtmpUri()

onMounted(async() => {
  watchEffect(() => {
    // This code will run initially and re-run every time selectedShow or its mist_stream_wildcard.id changes
    const wildcardId = goLiveStore.wildcardId
    if (wildcardId) {
      goLiveStore.fetchPushDestinations()
      goLiveStore.reloadPlayer()
    }
  })
})

// const reloadPlayer = () => {
//   const videoPlayerStore = useVideoPlayerStore
//   let source = null
//   if (goLiveStore?.selectedShow?.mist_stream_wildcard?.name) {
//     source = goLiveStore?.selectedShow?.mist_stream_wildcard?.name
//     goLiveStore.fetchStreamInfo(goLiveStore?.selectedShow?.mist_stream_wildcard?.name)
//   } else if (goLiveStore?.episode?.mist_stream_wildcard?.name) {
//     source = goLiveStore?.episode?.mist_stream_wildcard?.name
//     goLiveStore.fetchStreamInfo(goLiveStore?.episode?.mist_stream_wildcard?.name)
//   }
//   let sourceUrl = videoPlayerStore.mistServerUri + 'hls/' + source + '/index.m3u8'
//   console.log('source url: ' + sourceUrl)
//   let sourceType = 'application/vnd.apple.mpegurl'
//   let videoJs = videojs('aux-player')
//   videoJs.src({'src': sourceUrl, 'type': sourceType})
//   // videoAuxPlayerStore.loadNewLiveSource(source, sourceType)
//   console.log('reload player')
// }
</script>