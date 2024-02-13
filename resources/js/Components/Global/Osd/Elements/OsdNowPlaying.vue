<template>
  <div class="ml-6 md:ml-0">

    <div
        class="w-fit flex flex-row justify-start text-xs text-gray-500 break-words uppercase tracking-wider"
        :class="{ 'pt-4': applyPaddingTop }">
      Now Playing
      <span class="text-gray-500 tracking-widest" v-if="nowPlayingStore.activeMedia.type==='channel'">&nbsp;<span class="text-yellow-400">{{ channelStore.currentChannelName }}&nbsp;Channel</span></span>
      <span class="text-xs text-gray-700 tracking-widest"
                        v-if="nowPlayingStore.activeMedia.type==='externalVideo'">&nbsp;&nbsp;external video</span>
    </div>
    <div>
      <div class="flex flex-col">
        <h3 v-if="nowPlayingStore?.activeMedia?.details?.primaryUrl">
          <!-- Render as a link if the URL exists -->
          <Link class="hover:text-blue-500 hover:cursor-pointer"
                :href="`/${nowPlayingStore?.activeMedia?.details?.primaryUrl}`">
            <!-- Title (with link) -->
            {{ nowPlayingStore?.activeMedia?.details?.primaryName }}
          </Link>
        </h3>
        <h3 v-else>
          <!-- Just display the name without a link if the URL does not exist -->
          <!-- Title (no link) -->
          {{ nowPlayingStore.activeMedia.details.primaryName }}
        </h3>
        <h4 v-if="nowPlayingStore?.activeMedia?.details?.secondaryUrl">
          <!-- Render as a link if the URL exists -->
          <Link class="hover:text-blue-500 hover:cursor-pointer"
                :href="`/${nowPlayingStore?.activeMedia?.details?.secondaryUrl}`">
            <!-- Title (with link) -->
            {{ nowPlayingStore?.activeMedia?.details?.secondaryName }}
          </Link>
        </h4>
        <h2 v-else>
          <!-- Just display the name without a link if the URL does not exist -->
          <!-- Title (no link) -->
          {{ nowPlayingStore.activeMedia.details.secondaryName }}
        </h2>
        <!--                    <div class="showOrMovieTitle">{{ nowPlayingStore.show?.name }}</div>-->
        <!--                    <div class="showEpisodeTitle">{{ nowPlayingStore.show?.episode?.name }}</div>-->
        <!-- Release Date -->
        <div class="releaseYear">{{ nowPlayingStore?.activeMedia?.details?.release_year }}</div>
        <!--                    <div class="releaseDateTime">{{ useTimeAgo(nowPlayingStore.show?.episode.releaseDateTime) }}</div>-->
      </div>
    </div>

    <div v-if="nowPlayingStore?.type && nowPlayingStore?.type === 'channel'" class="-ml-3 break-words">
      <span class="text-xs uppercase pr-2">Channel: </span>
      <span class="text-xs font-semibold">{{ nowPlayingStore?.channel?.name }}</span>
    </div>

    <!--    <div>-->
    <!--      <button v-if="nowPlayingStore.show?.episode.name" class="text-sm uppercase"-->
    <!--              @click="Inertia.visit(nowPlayingStore.show?.episode.url)">-->
    <!--        {{ nowPlayingStore.show?.episode.name }}-->
    <!--      </button>-->
    <!--      <button v-if="nowPlayingStore.movie?.name" class="cursor-pointer"-->
    <!--              @click.prevent="Inertia.visit(nowPlayingStore.movie?.url)">-->
    <!--        {{ nowPlayingStore.movie?.name }}-->
    <!--      </button>-->
    <!--      <span v-if="nowPlayingStore.isFromWeb" class="ml-2 text-yellow-500">(web)</span>-->
    <!--    </div>-->
    <!--    <div v-if="nowPlayingStore.videoFile?.name" class="break-words uppercase">-->
    <!--      {{ nowPlayingStore.videoFile?.name }}-->
    <!--    </div>-->

  </div>

</template>

<script setup>
import { useNowPlayingStore } from '@/Stores/NowPlayingStore'
import { useChannelStore } from '@/Stores/ChannelStore'
import { computed } from 'vue'

const nowPlayingStore = useNowPlayingStore()
const channelStore = useChannelStore()

const applyPaddingTop = computed(() => {
  return nowPlayingStore.isLive || nowPlayingStore.viewerCountIsVisible
})

</script>