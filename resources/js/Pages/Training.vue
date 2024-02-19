<template>
  <Head title="Training"/>
  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white text-black p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <div class="flex justify-between">
        <div class="grid grid-cols-1 grid-rows-2">
          <h1 class="text-3xl font-semibold">Training</h1>
        </div>
        <div><span class="text-xs font-semibold text-purple-700">Creator Mode</span></div>
        <div class="grid grid-cols-1 grid-rows-2">
          <div class="justify-self-end">
            <DashboardButton />
          </div>
        </div>
      </div>
      <p>
        Travis will create some training videos to help people create content in a higher broadcast quality.
      </p>
      <ul class="list-disc list-inside pt-4">
        <li>
          <Link class="text-blue-500 hover:text-blue-700 font-semibold" :href="`/training/go-live-using-zoom`">How to Stream to notTV Using Zoom</Link>
        </li>

        <li>
          Field production
        </li>
        <li>
          Studio production
        </li>

      </ul>

      <button @click="notificationStore.openDialogNotification()" class="mt-8">OPEN TEST NOTIFICATION</button>

    </div>
  </div>
  <!--                </div>-->
  <!--            </div>-->
  <!--        </div>-->
</template>

<script setup>
import { onBeforeMount, onMounted, ref } from 'vue'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useNotificationStore } from '@/Stores/NotificationStore'
import Message from '@/Components/Global/Modals/Messages'
import DashboardButton from '@/Components/Global/Buttons/DashboardButton.vue'

usePageSetup('training')

const appSettingStore = useAppSettingStore()
const notificationStore = useNotificationStore()

onMounted(() => {
  notificationStore.title = 'Test Notification'
  notificationStore.body = 'This is just a temporary notification!'
})

</script>

