<template>
  <div class="mb-6">
    <label class="block mb-2 uppercase font-bold dark:text-gray-200"
           for="name"
    >
      Episode Name <span :class="errors.description ? 'text-red-500' : 'text-indigo-500'">* REQUIRED</span>
    </label>

    <input v-model="showEpisodeStore.episode.name"
           class="border border-gray-400 text-gray-800 p-2 w-1/2 rounded-lg"
           type="text"
           name="name"
           id="name"
           required
    >
    <div v-if="errors.name" v-text="errors.name"
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