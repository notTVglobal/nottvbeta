<template>
  <div class="flex flex-col lg:flex-row justify-center text-center lg:justify-between mt-6">
    <!-- Episode ID -->
    <div class="mb-4 lg:mb-0 w-full lg:w-auto flex justify-center lg:justify-start gap-x-1">
      <span class="text-xs uppercase font-semibold">Episode ID: </span>
      <span class="text-xs">{{ showEpisodeStore.episode.ulid }}</span>
    </div>

    <!-- Creative Commons and Show Information -->
    <div class="flex flex-col lg:flex-row items-center justify-center lg:justify-start w-full lg:w-auto">
      <Link :href="`/shows/${showEpisodeStore.show.slug}`" class="text-blue-500 hover:text-blue-700 uppercase">
        {{ showEpisodeStore.show.name }}
      </Link>

      <div class="">
      <span v-if="showEpisodeStore.episode?.creative_commons?.name" class="text-xs font-semibold text-gray-500">
        &nbsp;{{ showEpisodeStore.episode.creative_commons.name }}&nbsp;
        <span v-if="showEpisodeStore.episode.creative_commons.id !== 7">&bull;&nbsp;</span>
        <span v-else>&nbsp;&copy;&nbsp;</span>
      </span>

        <span class="text-xs font-semibold text-gray-500" v-if="showEpisodeStore.show?.last_release_year">
        {{ showEpisodeStore.show.first_release_year }}-{{ showEpisodeStore.show.last_release_year }}
      </span>
        <span class="text-xs font-semibold text-gray-500" v-else-if="showEpisodeStore.show?.first_release_year">
        {{ showEpisodeStore.show.first_release_year }}
      </span>
        <span class="text-xs font-semibold text-gray-500" v-else-if="showEpisodeStore.episode?.copyrightYear">
        {{ showEpisodeStore.episode.copyrightYear }}
      </span>
      </div>
    </div>
  </div>
</template>
<script setup>
import { useShowEpisodeStore } from '@/Stores/ShowEpisodeStore'

const showEpisodeStore = useShowEpisodeStore()

defineProps({
  team: Object,
  episode: Object,
  show: Object,
  can: Object,
})

let currentYear = new Date().getFullYear()

</script>
