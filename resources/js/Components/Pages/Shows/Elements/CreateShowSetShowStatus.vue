<template>
  <div class="mb-6">
    <label class="block mb-2 uppercase font-bold text-xs text-red-700"
           for="status"
    >
      Status
    </label>

    <select required
            class="border border-gray-400 text-gray-800 p-2 w-fit rounded-lg block mb-2 uppercase font-bold text-xs "
            v-model="showStore.show_status_id"
    >
      <option v-for="status in statuses"
              :key="status.id" :value="status.id">{{ status.name }}
      </option>


    </select>
    <div v-if="errors.show_status_id" v-text="errors.show_status_id"
         class="text-xs text-red-600 mt-1"></div>
  </div>
</template>
<script setup>
import { useShowStore } from '@/Stores/ShowStore'

const showStore = useShowStore()

const props = defineProps({
  statuses: Array,
  errors: Object,
})
</script>