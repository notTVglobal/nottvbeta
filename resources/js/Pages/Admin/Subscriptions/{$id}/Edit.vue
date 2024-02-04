<template>
  <div id="topDiv">
    <div>
      {{ subscription }}
    </div>
  </div>

</template>

<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from "@/Stores/AppSettingStore"

const appSettingStore = useAppSettingStore()

let props = defineProps({
  subscription: Object,
})

usePageSetup(`Admin/Subscription/${props.subscription.id}/Edit`)

</script>
