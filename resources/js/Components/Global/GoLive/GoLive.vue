<template>
  <div :class="containerClass">
    <div
        class="text-sm font-semibold text-white text-center w-full border-2 rounded uppercase"
        :class="liveStatusClass"
    >
      <span v-if="goLiveStore.isLive">Now Live</span>
      <span v-else>Live Setup</span>
    </div>
    <div
        class="shadow overflow-hidden border-2 rounded"
        :class="contentClass"
    >
      <GoLiveHeader/>
      <GoLiveAuxVideoPlayer/>
      <GoLivePushDestinations :key="goLiveStore.selectedShowId"/>
    </div>
    <GoLiveCommercialBreaks/>
  </div>
</template>
<script setup>
import { useGoLiveStore } from '@/Stores/GoLiveStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'


import GoLiveHeader from '@/Components/Pages/GoLive/GoLiveHeader.vue'
import GoLiveAuxVideoPlayer from '@/Components/Pages/GoLive/GoLiveAuxVideoPlayer.vue'
import GoLivePushDestinations from '@/Components/Pages/GoLive/GoLivePushDestinations.vue'
import GoLiveCommercialBreaks from '@/Components/Pages/GoLive/GoLiveCommercialBreaks.vue'
import { computed } from 'vue'


const goLiveStore = useGoLiveStore()
const appSettingStore = useAppSettingStore()


const containerClass = computed(() => {
  return appSettingStore.isSmallScreen ? 'mx-0 mt-4 mb-2 px-2 py-1' : 'mx-0 md:mx-4 mt-4 mb-2 px-2 md:px-6 py-1'
})

const liveStatusClass = computed(() => {
  return [
    goLiveStore.isLive ? 'bg-gray-600 border-gray-600' : 'bg-red-600 border-red-600',
    appSettingStore.isSmallScreen ? 'px-0 py-1' : 'px-0 md:px-6 py-1',
  ]
})

const contentClass = computed(() => {
  return [
    goLiveStore.isLive ? 'bg-gray-100' : 'bg-red-100',
    appSettingStore.isSmallScreen ? 'p-2' : 'p-2 md:p-6',
    'border-red-600',
  ]
})

// Initialize fetching of server information
goLiveStore.updateAndGetStreamKey()
goLiveStore.fetchStreamInfo()
goLiveStore.fetchRtmpUri()
</script>