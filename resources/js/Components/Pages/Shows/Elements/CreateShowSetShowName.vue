<template>
  <div class="mb-6">
    <label class="block mb-2 uppercase font-bold dark:text-gray-200"
           for="name"
    >
      Show Name <span :class="errors.name ? 'text-red-500' : 'text-indigo-500'">* REQUIRED</span>
    </label>

    <input v-model="showStore.name"
           class="bg-gray-50 border border-gray-400 text-gray-900 p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500"
           type="text"
           name="name"
           id="name"
           required
           placeholder="Show Name"
           autofocus
    >
    <div v-if="errors.name" v-text="errors.name" class="text-xs text-red-600 mt-1"></div>
  </div>
</template>
<script setup>
import { useShowStore } from '@/Stores/ShowStore'

const showStore = useShowStore()

const props = defineProps({
  errors: Object,
})
</script>