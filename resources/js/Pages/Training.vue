<template>
  <Head title="Training"/>
  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white text-black p-10 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <div class="flex justify-between">
        <div class="grid grid-cols-1 grid-rows-2">
          <h1 class="text-3xl font-semibold">Training</h1>
        </div>
        <div><span class="text-xs font-semibold text-purple-700">Creator Mode</span></div>
        <div class="grid grid-cols-1 grid-rows-2">
          <div class="justify-self-end">
            <DashboardButton/>
          </div>
        </div>
      </div>
      <div>
      <h2>Live Streaming</h2>
      <ul class="list-disc list-inside pt-4">
        <li>
          <Link class="text-blue-500 hover:text-blue-700 font-semibold" :href="`/training/go-live-using-zoom`">How to
            Stream to notTV Using Zoom
          </Link>
        </li>

        <li>
          <Link class="text-blue-500 hover:text-blue-700 font-semibold" :href="`/training/how-to-push-to-facebook`">How
            to Push To Facebook
          </Link>
        </li>
        <li>
          <Link class="text-blue-500 hover:text-blue-700 font-semibold" :href="`/training/how-to-push-to-rumble`">How to
            Push To Rumble
          </Link>
        </li>
        <li>
          <Link class="text-blue-500 hover:text-blue-700 font-semibold" :href="`/training/live-streaming-guide`"> Live Streaming Cheat Sheet
          </Link>
        </li>

      </ul>
      </div>
      <div class="mt-4">
        <h2>
          Field production
        </h2>
        <ul class="list-disc list-inside pt-4">
          <li>
            Travis will create some training videos to help people create content in a higher broadcast quality.
          </li>
        </ul>
      </div>
      <div class="mt-4">
        <h2>
          Studio production
        </h2>
        <ul class="list-disc list-inside pt-4">
          <li>
            Travis will create some training videos to help people create content in a higher broadcast quality.
          </li>
        </ul>
      </div>
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

