<template>
    <div>
<!--       <Head :title="title"/>-->
    </div>

    <div class="overflow-scroll hide-scrollbar scrollbar-hid">
        <slot />
    </div>

  <ImageLightboxModal v-if="appSettingStore.showImageLightboxModal"/>
  <GeneralServiceNotification />
  <SocialSharingModal v-if="socialShareStore.socialSharingModal" />
</template>

<script setup>
import { onBeforeMount } from 'vue'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useSocialShareStore } from '@/Stores/SocialShareStore'

import GeneralServiceNotification from '@/Components/Global/Modals/GeneralServiceNotification.vue'
import ImageLightboxModal from '@/Components/Global/Modals/ImageLightboxModal.vue'
import SocialSharingModal from '@/Components/Global/Modals/SocialSharingModal.vue'

const appSettingStore = useAppSettingStore()
const socialShareStore = useSocialShareStore()

onBeforeMount(() => {
  appSettingStore.checkScreenSize()
})

</script>
