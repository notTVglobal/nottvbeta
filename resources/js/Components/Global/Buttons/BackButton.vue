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
import { router } from '@inertiajs/vue3'
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
      router.visit(props.url)
    } else {
      router.visit(appSettingStore.prevUrl)
    }
  } else {
    if (props.url) {
      router.visit(props.url)
    } else {
      // Fallback if prevUrl is not available
      let prevUrl = userStore.isCreator ? '/dashboard' : '/';
      router.visit(prevUrl);
    }
  }
}
</script>
