<template>
  <div>
    <div id="topDiv">
      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

    </div>
  </div>

</template>

<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import Message from "@/Components/Global/Modals/Messages"

usePageSetup('2')

const appSettingStore = useAppSettingStore()

</script>
