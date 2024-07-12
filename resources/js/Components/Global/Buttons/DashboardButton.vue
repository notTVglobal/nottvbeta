<template>
  <div>
    <button
        @click="appSettingStore.btnRedirect('/dashboard')"
        class="bg-black hover:bg-gray-800 text-white font-semibold mt-2 px-4 py-2 rounded-lg disabled:bg-gray-400 h-max w-max"
    >
      <font-awesome-icon :icon="['fas', 'tachometer-alt']" class="hover:text-blue-800 mr-1" /> Dashboard
    </button>
  </div>
</template>

<script setup>
import { useAppSettingStore } from "@/Stores/AppSettingStore"

const appSettingStore = useAppSettingStore()

</script>
