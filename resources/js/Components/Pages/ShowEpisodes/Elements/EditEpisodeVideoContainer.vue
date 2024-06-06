<template>
  <div class="flex flex-col rounded justify-end w-full bg-white dark:bg-black my-6">
    <div class="block uppercase font-bold text-xs text-black dark:text-white"
         for="name"
    >
      Episode Video
    </div>

    <EpisodeVideo :episode="showEpisodeStore.episode" />

  </div>
</template>
<script setup>
import { useShowEpisodeStore } from '@/Stores/ShowEpisodeStore'
import EpisodeVideo from '@/Components/Pages/ShowEpisodes/Elements/EpisodeVideo.vue'

const showEpisodeStore = useShowEpisodeStore()
</script>