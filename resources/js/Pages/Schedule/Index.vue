<script>

// DELETE THIS SCRIPT TAG
// This is only for notes about building this page.

// The schedule page will allow free users to look back 72 hours and look ahead 72 hours.
// On a mobile device it's easy enough to create a forever scroll for looking forward.
// The easter egg will be scrolling UP to look back at the past 72 hours.
// Free users can watch any of the content in the past 72 hours for free.
// Users are given credits each month to watch premium notTV content.
// They may purchase more credits, or subscribe for unlimited access.
// They may also use credits to purchase licenses to content from creators.
// Credits may also be spent in the shop.

</script>

<template>
  <Head title="Schedule"/>

  <div ref="scrollContainer" class="place-self-center flex flex-col w-full overscroll-x-none pb-64 px-4">
    <div id="topDiv" class="flex justify-end px-5">
      <div class="relative w-32 h-32 mt-8 -mb-8 mr-8">
        <div class="absolute top-3 left-0 w-full h-full flex justify-center items-center z-20"><h1
            class="text-4xl font-bold text-white bg-black bg-opacity-80 px-4 py-1 text-center">Broadcast<br/>Schedule
        </h1></div>
        <div class="absolute top-3 left-0 w-full h-full flex justify-center items-center z-10"><img
            src="/storage/images/Ping.png" alt="notTV Ping"/></div>
      </div>

    </div>
    <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

    <ScheduleGridContainer/>

    <PopUpModal :id="`goToNowPlayingModal`">
      <template v-slot:header>Now Playing</template>
      <template v-slot:main><span class="text-orange-500">This modal is temporary. This will take you to the now playing show or episode page.</span>
      </template>
    </PopUpModal>
    <PopUpModal :id="`getReminderModal`">
      <template v-slot:header>Set Reminder</template>
      <template v-slot:main><span class="text-orange-500">This modal is temporary. Set a reminder when this show starts and/or subscribe to the show to get all notifications when new episodes are released or the show goes live. <br/><br/><span
          class="font-semibold text-yellow-600">NOTE: Monthly and Yearly contributors get first access to new features.</span></span>
      </template>
    </PopUpModal>

  </div>
</template>

<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import { useScheduleStore } from '@/Stores/ScheduleStore'
import Message from '@/Components/Global/Modals/Messages'
import PopUpModal from '@/Components/Global/Modals/PopUpModal'
import ScheduleGridContainer from '@/Components/Global/Schedule/ScheduleGridContainer.vue'
import { onMounted, ref, watch } from 'vue'
import dayjs from 'dayjs'

usePageSetup('schedule')

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
const scheduleStore = useScheduleStore()

let props = defineProps({
  can: Object,
})

// onMounted(() => {
//   scheduleStore.initializeTimeSlots();
// });

// Define a reactive watcher on the timezone
// This watcher will call fetchSchedules whenever the timezone changes and is not null
// watch(
//     () => userStore.timezone,
//     async (newTimezone, oldTimezone) => {
//       // Ensure the timezone is set before calling preloadWeeklyContent
//       if (newTimezone) {
//         const startDate = dayjs();
//         const endDate = startDate.add(4, 'hour');
//         await scheduleStore.fetchSchedules(startDate.format(), endDate.format());
//         console.log('Preload schedules...')
//       }
//     },
//     {immediate: true}, // This option ensures the watcher is triggered immediately on mount
// )

onMounted(() => {
  const topDiv = document.getElementById('topDiv')
  if (topDiv) {
    topDiv.scrollIntoView({behavior: 'smooth'})
  }
})

function openModal(modalName) {
  document.getElementById(modalName).showModal()
}

</script>
