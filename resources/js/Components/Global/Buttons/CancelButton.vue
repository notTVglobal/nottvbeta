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

let props = defineProps({
  url: String
})

function cancel() {
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
