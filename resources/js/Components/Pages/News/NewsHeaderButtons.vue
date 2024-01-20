<template>

  <div class="w-full flex flex-end flex-wrap-reverse justify-end gap-2 mr-4 mb-4">
    <button
        v-if="can.viewNewsroom"
        @click="appSettingStore.btnRedirect(`/newsroom`)"
        class="bg-yellow-600 hover:bg-yellow-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
    >Newsroom
    </button>
    <button
        @click="appSettingStore.btnRedirect(``)"
        class="bg-green-600 hover:bg-green-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
        disabled
    >Upload Press Release
    </button>
  </div>
</template>

<script setup>
import { useAppSettingStore } from "@/Stores/AppSettingStore"

const appSettingStore = useAppSettingStore()

defineProps({
  can: Object,
})
</script>
