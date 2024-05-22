<template>
  <div class="mb-6">
    <label class="block mb-2 uppercase font-bold text-xs text-light text-red-700"
           for="name"
    >
      Show Notes (only visible to team members)
    </label>

    <TabbableTextarea v-model="showStore.notes"
                      class="border border-gray-400 p-2 w-full rounded-lg text-black bg-white dark:bg-gray-800 dark:text-white"
    />
    <div v-if="errors.notes" v-text="errors.notes"
         class="text-xs text-red-600 mt-1"></div>
  </div>
</template>
<script setup>
import { useShowStore } from '@/Stores/ShowStore'
import TabbableTextarea from '@/Components/Global/TextEditor/TabbableTextarea'

const showStore = useShowStore()

const props = defineProps({
  errors: Object,
})

</script>