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
      <div v-if="nowPlayingStore.show?.name || nowPlayingStore.movie?.name || nowPlayingStore.videoFile?.name"
           class="w-fit flex flex-col justify-start text-xs uppercase text-gray-500 break-words uppercase">
        Now Playing
      </div>
      <div>
        <button v-if="nowPlayingStore.show?.name" class="cursor-pointer"
                @click.prevent="Inertia.visit(nowPlayingStore.show?.url)">
          {{ nowPlayingStore.show?.name }}
        </button>
      </div>
      <div>
        <button v-if="nowPlayingStore.show?.episode.name" class="text-sm uppercase"
                @click="Inertia.visit(nowPlayingStore.show?.episode.url)">
          {{ nowPlayingStore.show?.episode.name }}
        </button>
        <button v-if="nowPlayingStore.movie?.name" class="cursor-pointer"
                @click.prevent="Inertia.visit(nowPlayingStore.movie?.url)">
          {{ nowPlayingStore.movie?.name }}
        </button>
        <span v-if="nowPlayingStore.isFromWeb" class="ml-2 text-yellow-500">(web)</span>
      </div>
      <div v-if="nowPlayingStore.videoFile?.name" class="break-words uppercase">
        {{ nowPlayingStore.videoFile?.name }}
      </div>
      <div v-if="nowPlayingStore.channel?.name" class="-ml-3 break-words">
        <span class="text-xs uppercase pr-2">Channel: </span>
        <span class="text-xs font-semibold">{{ nowPlayingStore.channel?.name }}</span>
      </div>
    </div>


  </div>
</template>

<script setup>
import { Inertia } from "@inertiajs/inertia"
import { computed } from 'vue'
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
const appSettingStore = useAppSettingStore()
import { useNowPlayingStore } from "@/Stores/NowPlayingStore"
import { useChannelStore } from "@/Stores/ChannelStore"
import { useUserStore } from "@/Stores/UserStore"
import CurrentViewers from "@/Components/Global/Osd/Elements/CurrentViewers"

const videoPlayerStore = useVideoPlayerStore()
const channelStore = useChannelStore()
const nowPlayingStore = useNowPlayingStore()
const userStore = useUserStore()

const ottPanelsOpen = computed(() => {
  return (videoPlayerStore.ottChannels || videoPlayerStore.ottPlaylist || videoPlayerStore.ottFilters || videoPlayerStore.ottChat) ? 'hidden' : '';
});

</script>
