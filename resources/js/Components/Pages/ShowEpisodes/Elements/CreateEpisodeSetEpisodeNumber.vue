<template>
  <div class="mb-6">
    <label class="block mb-2 uppercase text-sm font-bold dark:text-gray-200"
           for="episode_number"
    >
      Episode Number
    </label>

    <input v-model="showEpisodeStore.episode.episode_number"
           class="border border-gray-400 text-gray-800 p-2 w-1/2 rounded-lg"
           type="text"
           :placeholder="showEpisodeStore.episode.id"
           name="episode_number"
           id="episode_number"
    >
    <div v-if="errors.episode_number" v-text="errors.episode_number"
         class="text-xs text-red-600 mt-1"></div>
  </div>
</template>
<script setup>
import { useShowEpisodeStore } from '@/Stores/ShowEpisodeStore'

const showEpisodeStore = useShowEpisodeStore()

const props = defineProps({
  errors: Object,
})
</script>