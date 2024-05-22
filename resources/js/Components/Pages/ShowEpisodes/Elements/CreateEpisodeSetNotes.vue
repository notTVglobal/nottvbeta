<template>
  <div class="mb-6">
    <label class="block mb-2 uppercase text-sm font-bold dark:text-gray-200"
           for="notes"
    >
      Episode Notes <span class="text-indigo-500">(only the team members see the notes)</span>
    </label>

    <TabbableTextarea v-model="showEpisodeStore.episode.notes"
                      class="border border-gray-400 p-2 w-full rounded-lg text-black bg-white dark:bg-gray-800 dark:text-white"
    />

    <div v-if="errors.notes" v-text="errors.notes"
         class="text-xs text-red-600 mt-1"></div>
  </div>
</template>
<script setup>
import { useShowEpisodeStore } from "@/Stores/ShowEpisodeStore"
import TabbableTextarea from '@/Components/Global/TextEditor/TabbableTextarea'

const showEpisodeStore = useShowEpisodeStore()

const props = defineProps({
  errors: Object,
})
</script>