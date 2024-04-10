<template>
  <div id="topDiv">
    <div>
      {{ subscriptions }}
    </div>
  </div>

</template>

<script setup>
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { usePageSetup } from '@/Utilities/PageSetup'

usePageSetup('Admin/Subscriptions')

const appSettingStore = useAppSettingStore()

defineProps({
  subscriptions: Object,
})

</script>
