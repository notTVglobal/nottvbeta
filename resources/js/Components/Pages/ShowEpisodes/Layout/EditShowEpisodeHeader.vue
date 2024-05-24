<template>
<div>
  <div class="flex justify-between my-6 px-6">
    <div>
      <div class="font-bold mb-4 text-red-700">EDIT EPISODE</div>
      <h1 class="text-3xl">
        <Link :href="`/shows/${showEpisodeStore.show.slug}/episode/${showEpisodeStore.episode.slug}`" class="text-red-700 font-bold uppercase">
          {{ showEpisodeStore.episode.name }}
        </Link>
      </h1>
    </div>
    <div>
      <CancelButton/>
    </div>
  </div>

  <div class="flex flex-wrap w-full justify-between px-6">

  </div>

</div>
</template>

<script setup>
import { useShowEpisodeStore } from '@/Stores/ShowEpisodeStore'
import CancelButton from "@/Components/Global/Buttons/CancelButton"

const showEpisodeStore = useShowEpisodeStore()


</script>
