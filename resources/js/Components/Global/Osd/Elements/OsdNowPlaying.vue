<template>
  <div>
    <div>
      <div
           class="w-fit flex flex-col justify-start text-xs text-gray-500 break-words uppercase">
        Now Playing
      </div>
      <div>
        <div class="flex flex-col">
          <h3 v-if="nowPlayingStore?.activeMedia?.details?.url">
            <!-- Render as a link if the URL exists -->
            <Link :href="`/${nowPlayingStore?.activeMedia?.details?.url}`">
              <!-- Title (with link) -->
              {{ nowPlayingStore?.activeMedia?.details?.name }}
            </Link>
          </h3>
          <h3 v-else>
            <!-- Just display the name without a link if the URL does not exist -->
            <!-- Title (no link) -->
            {{ nowPlayingStore.activeMedia.details.name }}
          </h3>
          <!--                    <div class="showOrMovieTitle">{{ nowPlayingStore.show?.name }}</div>-->
          <!--                    <div class="showEpisodeTitle">{{ nowPlayingStore.show?.episode?.name }}</div>-->
          <!-- Release Date -->
          <div class="releaseYear">{{ nowPlayingStore?.activeMedia?.details?.videoDetails?.release_year }}</div>
          <!--                    <div class="releaseDateTime">{{ useTimeAgo(nowPlayingStore.show?.episode.releaseDateTime) }}</div>-->
        </div>
      </div>

      <div v-if="nowPlayingStore?.type && nowPlayingStore?.type === 'channel'" class="-ml-3 break-words">
        <span class="text-xs uppercase pr-2">Channel: </span>
        <span class="text-xs font-semibold">{{ nowPlayingStore?.channel?.name }}</span>
      </div>

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
import { Inertia } from '@inertiajs/inertia'
import { useNowPlayingStore } from '@/Stores/NowPlayingStore'

const nowPlayingStore = useNowPlayingStore()

</script>