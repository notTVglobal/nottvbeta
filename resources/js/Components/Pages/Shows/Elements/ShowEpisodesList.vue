<template preserve-scroll>

  <div class="container mx-auto px-4 mb-12">

    <!--                                <h2 class="text-yellow-500 uppercase tracking-wide font-semibold">EPISODES</h2>-->
    <div class="w-full bg-gray-800 text-2xl p-4 mb-4">EPISODES</div>

    <div class="show-episodes w-full text-sm grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-8">

      <div v-for="episode in episodes.data"
           :key="episode.id"
           class="episode mt-8 w-full mx-auto">

        <div class="relative inline-block">
          <Link :href="`/shows/${show.slug}/episode/${episode.slug}`">
            <SingleImage :image="episode.image" :alt="'episode cover'"
                         :class="'h-28 w-48 min-w-[12rem] object-cover hover:opacity-75 transition ease-in-out duration-150 bg-black'"/>
            <!--                            <SingleImage :image="episode.image" :alt="'episode cover'" :class="'h-96 min-w-[16rem] w-64 object-cover mb-6 lg:mb-0 m-auto lg:m-0'"/>-->


          </Link>
        </div>
        <Link :href="`/shows/${show.slug}/episode/${episode.slug}`"
              class="block text-base font-semibold leading-tight hover:text-gray-400 mt-4">{{ episode.name }}
        </Link>
        <div class="text-gray-400 mt-1" v-if="!episode.episode_number">Episode {{ episode.id }}</div>
        <div class="text-gray-400 mt-1" v-if="episode.episode_number">Episode {{ episode.episode_number }}</div>
        <div class="text-gray-400 mt-1">{{ formatDate(episode.created_at) }}</div>
      </div>

    </div>

    <!-- Paginator -->
    <Pagination :data="episodes" class="mt-12 mb-6 pb-6 border-b border-gray-800"/>
  </div>

</template>

<script setup>
// import {useShowStore} from "@/Stores/ShowStore"
// import ShowEpisode from "@/Components/Pages/Shows/Manage/ShowEpisode.vue"
import SingleImage from "@/Components/Global/Multimedia/SingleImage"
import Pagination from "@/Components/Global/Paginators/PaginationDark"

// const showStore = useShowStore();

defineProps({
  show: Object,
  episodes: Object,
  filters: Object,
  // can: Object,
});

</script>
