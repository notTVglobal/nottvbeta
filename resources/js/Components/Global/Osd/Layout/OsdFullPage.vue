<template>
  <div :class="appSettingStore.ott !== 0 ? 'hidden lg:block' : ''">
    <div class="osdFullPageTop">
      <div class="flex justify-between">
        <div class="flex flex-row">
          <div v-if="isLive">
            <span
                class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-white bg-opacity-80 drop-shadow bg-red-800 last:mr-0 mr-1 mb-1">
                live
            </span>
          </div>
          <div v-if="viewerCountIsVisible"
               class="drop-shadow">
            <OsdCurrentViewers/>
          </div>

        </div>

      </div>
    </div>
    <div v-if="appSettingStore.osd"
         :class="ottPanelsOpen"
         class="osdFullPageBottom">

      <OsdNowPlaying />

    </div>
<div :class="isMobileVolumeIndicator" >
  <VideoVolumeIndicatorVertical v-if="appSettingStore.osd && appSettingStore.osdSlot.two && appSettingStore.fullPage"/>

</div>

  </div>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'
import { computed } from 'vue'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useNowPlayingStore } from '@/Stores/NowPlayingStore'
import { useChannelStore } from '@/Stores/ChannelStore'
import { useUserStore } from '@/Stores/UserStore'
import OsdCurrentViewers from '@/Components/Global/Osd/Elements/OsdCurrentViewers'
import OsdNowPlaying from '@/Components/Global/Osd/Elements/OsdNowPlaying.vue'
import OsdIsLive from '@/Components/Global/Osd/Elements/OsdIsLive.vue'
import VideoVolumeIndicatorVertical
  from '@/Components/Global/VideoPlayer/VideoIndicators/VideoVolumeIndicatorVertical.vue'

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()
const channelStore = useChannelStore()
const nowPlayingStore = useNowPlayingStore()
const userStore = useUserStore()

const ottPanelsOpen = computed(() => {
  return (videoPlayerStore.ottChannels || videoPlayerStore.ottPlaylist || videoPlayerStore.ottFilters || videoPlayerStore.ottChat) ? 'hidden' : ''
})

const viewerCountIsVisible = computed(() => {
  return nowPlayingStore.viewerCountIsVisible && channelStore.currentChannelId === 1;
});

const isLive = computed(() => {
  return nowPlayingStore.isLive;
});

const isMobileVolumeIndicator = computed(() => {
  return (userStore.isMobile) ? 'fixed flex justify-end text-right top-56 left-0 right-10 z-50 w-calc([100vw])' : 'fixed flex justify-end text-right top-10 left-0 right-10 z-50 w-calc([100vw])'
})



</script>
