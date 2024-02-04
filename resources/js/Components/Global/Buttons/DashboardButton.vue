<template>
  <div>
    <button
        @click="appSettingStore.btnRedirect('/dashboard')"
        class="bg-black hover:bg-gray-800 text-white font-semibold ml-2 mt-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
    >Dashboard
    </button>
  </div>
</template>

<script setup>
import { useAppSettingStore } from "@/Stores/AppSettingStore"

const appSettingStore = useAppSettingStore()

</script>
