<template>
    <div>
<!--       <Head :title="title"/>-->
    </div>

    <div class="overflow-scroll hide-scrollbar scrollbar-hid">
        <slot />
    </div>

  <ImageLightboxModal v-if="appSettingStore.showImageLightboxModal"/>
  <GeneralServiceNotification />
</template>

<script setup>
import { onBeforeMount } from 'vue'
import { useAppSettingStore } from '@/Stores/AppSettingStore'

import GeneralServiceNotification from '@/Components/Global/Modals/GeneralServiceNotification.vue'
import ImageLightboxModal from '@/Components/Global/Modals/ImageLightboxModal.vue'

const appSettingStore = useAppSettingStore()

onBeforeMount(() => {
  appSettingStore.checkScreenSize()
})

</script>
