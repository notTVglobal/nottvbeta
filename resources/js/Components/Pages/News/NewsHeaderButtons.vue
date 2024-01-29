<template>

  <div class="w-full flex flex-end flex-wrap-reverse justify-end gap-2 mr-4 mb-4">
    <button
        @click="appSettingStore.btnRedirect(``)"
        class="bg-green-600 hover:bg-green-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
        disabled
    >Upload Press Release<br><span class="text-xs">Move this to the Press Releases page</span>
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
