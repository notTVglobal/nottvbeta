<template>
  <div v-if="can.publish" class="flex flex-col py-4 px-6 mb-6 bg-white shadow rounded-lg">
    <div v-if="!newsStore.showNewsPersonSelector">
      <div class="font-semibold text-xs uppercase mb-2 text-gray-700">News Writer</div>
      <p class="text-gray-900 font-semibold">{{ newsStore?.newsPerson?.name }}</p>
      <button @click.prevent="newsStore.toggleNewsPersonSelector" class="btn btn-primary mt-2">Change News Writer</button>
    </div>
    <div v-else>
      <div class="font-semibold text-xs uppercase mb-2 text-gray-700">News Writer</div>
      <ChangeNewsPersonAsWriter @updateNewsPerson="newsStore.toggleNewsPersonSelector"/>
      <button @click.prevent="newsStore.toggleNewsPersonSelector" class="btn btn-secondary mt-2">Cancel</button>
    </div>
  </div>
</template>
<script setup>
import { useNewsStore } from '@/Stores/NewsStore'
import ChangeNewsPersonAsWriter from '@/Components/Pages/News/ChangeNewsPersonAsWriter.vue'

const newsStore = useNewsStore()

const props = defineProps ({
  can: Object
})
</script>