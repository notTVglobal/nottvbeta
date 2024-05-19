<template>
  <div class="flex flex-row justify-between mt-6">
    <h2 class="text-2xl font-semibold leading-tight">
      Create News Story
    </h2>
    <div class="flex justify-end space-x-2">
      <button
          @click="newsStore.submit"
          class="text-white bg-blue-700 hover:bg-blue-500 focus:outline-none font-medium rounded-lg text-sm px-6 py-2.5 ml-2"
          :disabled="newsStore.processing"
          :class="{ 'opacity-25': newsStore.processing }"
      >
        Save
      </button>
    </div>
  </div>
</template>
<script setup>
import { useNewsStore } from '@/Stores/NewsStore'

const newsStore = useNewsStore()
</script>