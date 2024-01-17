<template>
  <div>
    <button
        @click.prevent="cancel"
        class="ml-2 px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
    >Cancel
    </button>
  </div>
</template>

<script setup>
import { Inertia } from "@inertiajs/inertia"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useUserStore } from '@/Stores/UserStore'

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()

function cancel() {
  if (appSettingStore.prevUrl) {
    Inertia.visit(appSettingStore.prevUrl)
  } else {
    // Fallback if prevUrl is not available
    let prevUrl = userStore.isCreator ? '/dashboard' : '/';
    Inertia.visit(prevUrl);
  }
}
</script>
