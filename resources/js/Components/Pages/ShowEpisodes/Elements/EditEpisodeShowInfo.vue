<template>
<div class="mb-8">
  <div class="space-y-1 text-black dark:text-white">
    <div class="mb-6"><span class="text-xs uppercase font-semibold">Episode #: </span>
      <span v-if="showEpisodeStore.episode.episodeNumber" class="font-bold uppercase">{{ showEpisodeStore.episode.episodeNumber }}</span>
      <span v-if="!showEpisodeStore.episode.episodeNumber" class="font-bold uppercase">{{ showEpisodeStore.episode.id }}</span>
    </div>
    <div class=""><span class="text-xs uppercase font-semibold">Show: </span>
      <Link :href="`/shows/${showEpisodeStore.show.slug}/manage`">
          <span
              class="font-bold uppercase text-blue-600 hover:text-blue-800 dark:text-blue-300 dark:hover:text-blue-500">{{
              showEpisodeStore.show.name
            }}</span>
      </Link>
    </div>
    <div class=""><span class="text-xs uppercase font-semibold">Category: </span>
      <span class="font-bold uppercase">{{ showEpisodeStore.show?.category }}</span>
      <span class="text-xs">
                    (change the category on the
                    <Link :href="`/shows/${showEpisodeStore.show.slug}/edit`"
                          class="text-blue-600 hover:text-blue-800 dark:text-blue-300 dark:hover:text-blue-500">
                        show edit page</Link>)
                </span>
    </div>
    <div class=""><span class="text-xs uppercase font-semibold">Sub-category: </span>
      <span class="font-bold uppercase">{{ showEpisodeStore.show?.subCategory }}</span>
    </div>
    <div class=""><span class="text-xs uppercase font-semibold">Team: </span>
      <Link :href="`/teams/${showEpisodeStore.team.slug}/manage`">
          <span
              class="dark:font-bold uppercase text-blue-600 hover:text-blue-800 dark:text-blue-300 dark:hover:text-blue-500">{{
              showEpisodeStore.team.name
            }}</span>
      </Link>
    </div>

  </div>
</div>
</template>
<script setup>
import { useShowEpisodeStore } from '@/Stores/ShowEpisodeStore'

const showEpisodeStore = useShowEpisodeStore()
</script>