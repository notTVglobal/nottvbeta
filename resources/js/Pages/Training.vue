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
            <Link :href="`/dashboard`">
              <button
                  class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
              >Dashboard
              </button>
            </Link>
          </div>
        </div>
      </div>
      <p>
        Travis will create some training videos to help people create content in a higher broadcast quality.
      </p>
      <ul class="list-disc list-inside pt-4">
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

usePageSetup('training')

const appSettingStore = useAppSettingStore()
const notificationStore = useNotificationStore()

onMounted(() => {
  notificationStore.title = 'Test Notification'
  notificationStore.body = 'This is just a temporary notification!'
})

</script>

