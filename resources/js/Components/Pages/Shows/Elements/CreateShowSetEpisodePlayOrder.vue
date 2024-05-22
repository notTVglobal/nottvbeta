<template>
  <div class="mb-6">
    <label class="block mb-2 uppercase font-bold text-xs text-red-700"
           for="episode_play_order"
    >
      Episode Play Order
    </label>

    <select required
            class="border border-gray-400 text-gray-800 p-2 w-fit rounded-lg block mb-2 uppercase font-bold text-xs "
            v-model="showStore.episode_play_order"
            id="episode_play_order"
    >
      <option disabled value="">Please select one</option> <!-- Disabled placeholder option -->
      <option value="newest">Start with the Newest Episode</option>
      <option value="oldest">Start with the First Episode</option>

    </select>
    <div v-if="errors.episode_play_order" v-text="errors.episode_play_order"
         class="text-xs text-red-600 mt-1"></div>
  </div>
</template>
<script setup>
import { useShowStore } from '@/Stores/ShowStore'

const showStore = useShowStore()

const props = defineProps({
  errors: Object,
})
</script>