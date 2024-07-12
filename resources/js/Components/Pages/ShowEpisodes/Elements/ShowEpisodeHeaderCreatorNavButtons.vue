<template>
  <div v-if="can.editShow || can.manageShow"
       class="flex justify-end">

    <div class="flex flex-col">
      <div class="flex flex-end flex-wrap-reverse justify-end gap-2 mr-4 my-4">
        <button
            v-if="can.manageShow"
            @click="appSettingStore.btnRedirect(`/shows/${show.slug}/episode/${episode.slug}/manage`)"
            class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
        >
          <font-awesome-icon :icon="['fas', 'cogs']" class="hover:text-blue-800 mr-1" /> Manage Episode
        </button>
      </div>
    </div>
  </div>
</template>
<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'

const appSettingStore = useAppSettingStore()

const props = defineProps({
  can: Object,
  team: Object,
  show: Object,
  episode: Object
})
</script>