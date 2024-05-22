<template>
  <div class="mb-4">
    <label class="block mb-2 uppercase font-bold dark:text-gray-200"
           for="episodeVideo"
    >
      Attach Recording
    </label>

    <select class="rounded-lg">
      <option>No recordings available</option>
      <option>Select recording...</option>
    </select>


  </div>
</template>
<script setup>
import { useShowEpisodeStore } from '@/Stores/ShowEpisodeStore'

const showEpisodeStore = useShowEpisodeStore()

const props = defineProps({
  errors: Object,
})
</script>