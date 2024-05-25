<template preserve-scroll>

  <div class="container mx-auto px-4 mb-12">

    <!--                                <h2 class="text-yellow-500 uppercase tracking-wide font-semibold">EPISODES</h2>-->
    <div class="w-full bg-gray-800 text-2xl p-4 mb-4">EPISODES</div>

    <div class="show-episodes w-full text-sm grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-8">
      <div v-for="episode in episodes"
           :key="episode.id"
           class="episode mt-8 w-full mx-auto">

        <div class="relative inline-block">
          <button @click="appSettingStore.btnRedirect(`/shows/${show.slug}/episode/${episode.slug}`)">
            <SingleImage :image="episode.image" :alt="'episode cover'"
                         class="max-h-64 w-full sm:max-w-64 min-w-[12rem] object-cover hover:opacity-75 transition ease-in-out duration-150 bg-black"/>
          </button>
        </div>

        <Link :href="`/shows/${show.slug}/episode/${episode.slug}`"
              class="block text-base font-semibold leading-tight hover:text-gray-400 mt-4">{{ episode.name }}</Link>

        <div class="text-gray-400 mt-1">
          <ConvertDateTimeToTimeAgo v-if="episode.releaseDateTime" :dateTime="episode.releaseDateTime" class="text-yellow-400 font-light"/>
          <ConvertDateTimeToTimeAgo v-if="episode.scheduledDateTime" :dateTime="episode.scheduledDateTime" class="text-green-400 font-light"/>
        </div>

        <div class="text-gray-500 mt-1" v-if="episode.episode_number">
          Episode {{ episode.episode_number }}
        </div>
        <div class="text-gray-500 mt-1" v-if="!episode.episode_number">
          Episode {{ episode.id }}
        </div>
      </div>
    </div>

<!--     Paginator -->
    <Pagination :data="episodes" class="mt-12 mb-6 pb-6 border-b border-gray-800"/>

    </div>



</template>

<script setup>
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import Pagination from "@/Components/Global/Paginators/PaginationDark"
import SingleImage from "@/Components/Global/Multimedia/SingleImage"
import ConvertDateTimeToTimeAgo from '@/Components/Global/DateTime/ConvertDateTimeToTimeAgo.vue'

const appSettingStore = useAppSettingStore()

let props = defineProps({
  show: Object,
  episodes: Object,
  filters: Object,
});
</script>
