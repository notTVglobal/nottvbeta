<template>
  <div class="mb-6">
    <label class="block mb-2 uppercase font-bold dark:text-gray-200"
           for="description"
    >
      Description <span :class="errors.description ? 'text-red-500' : 'text-indigo-500'">* REQUIRED</span>
    </label>

    <tip-tap-description-editor :description="description"
                                @updateContent="handleContentUpdate"
                                :isFocused="false"/>

    <div v-if="errors.description" v-text="errors.description" class="text-xs text-red-600 mt-1"></div>
  </div>
</template>
<script setup>
import { useShowEpisodeStore } from '@/Stores/ShowEpisodeStore'

import TipTapDescriptionEditor from '@/Components/Global/TextEditor/TipTapDescriptionEditor.vue'

const showEpisodeStore = useShowEpisodeStore()

const props = defineProps({
  description: String,
  errors: Object,
})

const handleContentUpdate = (html) => {
  showEpisodeStore.episode.description = html
}
</script>