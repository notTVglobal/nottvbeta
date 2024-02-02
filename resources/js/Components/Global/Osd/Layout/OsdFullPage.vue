<template>
  <div>
    <div class="osdFullPageTop">
      <div class="flex justify-between">
        <div>
          <div v-if="nowPlayingStore.displayCurrentViewerCount && channelStore.currentChannelId === 1"
               class="drop-shadow">
            <CurrentViewers/>
          </div>
        </div>
        <div>
            <span v-if="nowPlayingStore.isLive"
                  class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-white bg-opacity-80 drop-shadow bg-red-800 last:mr-0 mr-1 mb-1">
                live
            </span>
        </div>
      </div>
    </div>
    <div v-if="appSettingStore.osd"
         :class="ottPanelsOpen"
         class="osdFullPageBottom">

      <OsdNowPlaying />

    </div>


  </div>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'
import { computed } from 'vue'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useAppSettingStore } from '@/Stores/AppSettingStore'

const appSettingStore = useAppSettingStore()
import { useNowPlayingStore } from '@/Stores/NowPlayingStore'
import { useChannelStore } from '@/Stores/ChannelStore'
import { useUserStore } from '@/Stores/UserStore'
import CurrentViewers from '@/Components/Global/Osd/Elements/CurrentViewers'
import OsdNowPlaying from '@/Components/Global/Osd/Elements/OsdNowPlaying.vue'

const videoPlayerStore = useVideoPlayerStore()
const channelStore = useChannelStore()
const nowPlayingStore = useNowPlayingStore()
const userStore = useUserStore()

const ottPanelsOpen = computed(() => {
  return (videoPlayerStore.ottChannels || videoPlayerStore.ottPlaylist || videoPlayerStore.ottFilters || videoPlayerStore.ottChat) ? 'hidden' : ''
})

</script>
