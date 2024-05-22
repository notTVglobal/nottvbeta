<template>
  <Head title="Schedule"/>

  <div id="topDiv" class="place-self-center flex flex-col overscroll-x-none pb-64 h-screen">
    <div class="min-h-screen w-full justify-end px-5 bg-gray-900 text-gray-50 mt-16 overflow-y-scroll">

      <PublicNavigationMenu/>
      <PublicResponsiveNavigationMenu/>
      <div class="container mx-auto px-4 gap-y-3 rounded sm:rounded-lg shadow">

        <header class="flex justify-end">
          <div class="relative w-32 h-32 mr-8">
            <div class="absolute top-3 left-0 w-full h-full flex justify-center items-center z-20"><h1
                class="text-4xl font-bold text-white bg-black bg-opacity-80 px-4 py-1 text-center">Broadcast<br/>Schedule
            </h1></div>
            <div class="absolute top-3 left-0 w-full h-full flex justify-center items-center z-10"><img
                src="/storage/images/Ping.png" alt="notTV Ping"/></div>

          </div>
        </header>

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

        <div class="bg-gray-600 rounded-lg shadow m-10 p-4">


<!--          <TodayView/>-->


        </div>
      </div>
      <Footer/>
    </div>
  </div>
</template>

<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useUserStore } from '@/Stores/UserStore'
import { useScheduleStore } from '@/Stores/ScheduleStore'
import Message from '@/Components/Global/Modals/Messages'
import PopUpModal from '@/Components/Global/Modals/PopUpModal'
import TodayView from '@/Components/Global/Calendar/TodayView.vue'
import { computed, onBeforeMount, onMounted, watch } from 'vue'

import dayjs from 'dayjs'
import { router } from '@inertiajs/vue3'

import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import PublicResponsiveNavigationMenu from '@/Components/Global/Navigation/PublicResponsiveNavigationMenu.vue'
import Footer from '@/Components/Global/Layout/Footer.vue'
import ScheduleGridContainer from '@/Components/Global/Schedule/ScheduleGridContainer.vue'

const appSettingStore = useAppSettingStore()
const scheduleStore = useScheduleStore()
const userStore = useUserStore()
const videoPlayerStore = useVideoPlayerStore()

let props = defineProps({})

appSettingStore.currentPage = `schedule`
appSettingStore.setPrevUrl()


// Function to handle scrolling
const scrollToTop = () => {
  requestAnimationFrame(() => {
    const topDiv = document.getElementById('topDiv')
    if (topDiv) {
      // Smooth scroll to the 'topDiv' element
      topDiv.scrollIntoView({behavior: 'smooth'})
    } else {
      // Fallback: smooth scroll to the top of the page
      window.scrollTo({top: 0, behavior: 'smooth'})
    }
  })
}
scrollToTop() // Optionally scroll to top when the component mounts

onBeforeMount(() => {
  appSettingStore.checkScreenSize()
})

onMounted(() => {
  if (videoPlayerStore.player) {
    console.log('player is initialized...')
    console.log('disposing player...')
    setTimeout(() => {
      videoPlayerStore.disposePlayer()
    }, 1000) // Delay the disposal by 1000 milliseconds (1 second)
  }
})

</script>
<script>
import NoLayout from '@/Layouts/NoLayout'

export default {
  layout: NoLayout,
}
</script>

