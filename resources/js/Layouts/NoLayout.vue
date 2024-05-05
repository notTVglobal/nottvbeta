<template>
    <div>
<!--       <Head :title="title"/>-->
    </div>

    <div class="overflow-scroll hide-scrollbar scrollbar-hid">
        <slot />
    </div>
</template>

<script setup>
import { onBeforeMount } from 'vue'
import { useAppSettingStore } from '@/Stores/AppSettingStore'

const appSettingStore = useAppSettingStore()

onBeforeMount(() => {
  appSettingStore.checkScreenSize()
})

</script>
