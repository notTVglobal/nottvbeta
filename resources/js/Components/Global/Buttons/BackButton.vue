<template>
  <div>
    <button
        @click.prevent="back"
        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
    >Back
    </button>
  </div>
</template>

<script setup>
import { Inertia } from "@inertiajs/inertia"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useUserStore } from '@/Stores/UserStore'

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()

let props = defineProps({
  url: String
})

function back() {
  if (appSettingStore.prevUrl) {
    if (props.url) {
      Inertia.visit(props.url)
    } else {
      Inertia.visit(appSettingStore.prevUrl)
    }
  } else {
    if (props.url) {
      Inertia.visit(props.url)
    } else {
      // Fallback if prevUrl is not available
      let prevUrl = userStore.isCreator ? '/dashboard' : '/';
      Inertia.visit(prevUrl);
    }
  }
}
</script>
