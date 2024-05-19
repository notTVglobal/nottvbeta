<template>
  <div class="flex flex-row justify-between mt-6">
    <h2 class="text-xl font-semibold leading-tight">
      Edit News Post
    </h2>
    <div class="flex justify-end space-x-2">
      <div>
        <button
            v-if="can.viewNewsroom"
            @click="appSettingStore.btnRedirect(`/newsroom`)"
            class="px-4 py-2 text-white bg-yellow-600 hover:bg-yellow-500 rounded-lg disabled:bg-gray-400"

        >Newsroom
        </button>
      </div>
      <div class="">
        <button
            @click.prevent="newsStore.submit"
            class="text-white bg-blue-700 hover:bg-blue-500 focus:outline-none  font-medium rounded-lg text-sm px-5 py-2.5 "
            :disabled="newsStore.processing"
            :class="{ 'opacity-25': newsStore.processing }"
        >
          Save
        </button>
      </div>
      <div>
        <CancelButton/>
      </div>

    </div>
  </div>
</template>
<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useNewsStore } from '@/Stores/NewsStore'
import CancelButton from '@/Components/Global/Buttons/CancelButton.vue'

const appSettingStore = useAppSettingStore()
const newsStore = useNewsStore()

const props = defineProps({
  can: Object,
})
</script>