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

  <div class="place-self-center flex flex-col gap-y-3 w-full overscroll-x-none pb-64">
    <div id="topDiv" class="flex justify-end px-5">
      <div class="text-3xl font-semibold pt-4">Schedule</div>
    </div>
    <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>
    <div class="mx-6">
      <div class="alert alert-info">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>The schedule is updated every 15 minutes. Please revisit this page to see the updated changes.</span>
      </div>
    </div>
    <ul class="w-64 ml-12 my-8">
      <li class="p-2 bg-green-800">Scheduled Shows</li>
      <li class="p-2 bg-purple-800">New Releases</li>
      <li class="p-2 bg-blue-800">Live Events</li>
      <li class="p-2 bg-yellow-800">News</li>
      <!--            <li class="p-2"><font-awesome-icon icon="fa-leaf" class="text-red-600 text-3xl pr-2"/> Canadian Content</li>-->
      <!--            <li class="p-2"><font-awesome-icon icon="fa-flag-usa" class="text-red-600 text-3xl pr-2"/> American Content</li>-->

    </ul>

    <div class="ml-5 px-2">
      <span class="text-sm uppercase text-purple-500">All times are listed in your timezone.</span>
    </div>

    <table class="table-fixed mx-5">
      <thead class="bg-gray-900">
      <tr class="border-b border-0.5 border-white">
        <th class="column-width p-2 border-r border-0.5 border-white">10:00am</th>
        <th class="column-width p-2 border-x border-0.5 border-white">10:30am</th>
        <th class="column-width p-2 border-x border-0.5 border-white">11:00am</th>
        <th class="column-width p-2 hidden lg:table-cell border-x border-0.5 border-white">11:30am</th>
        <th class="column-width p-2 hidden xl:table-cell border-x border-0.5 border-white">12:00pm</th>
        <th class="column-width p-2 hidden xl:table-cell">12:30pm</th>
        <th class="column-width p-2 hidden 2xl:table-cell border-x border-0.5 border-white">1:00pm</th>
        <th class="column-width p-2 hidden 2xl:table-cell">1:30pm</th>

      </tr>
      </thead>
      <tbody>
      <tr class="border-b border-0.5 border-white">
        <td colspan="2" onclick="goToNowPlayingModal.showModal()"
            class="column-width p-2 bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
          <div class="flex flex-col">
            <span class="text-center pb-2">Scheduled Show</span>
            <div class="w-full h-64 bg-gray-400"></div>
          </div>
        </td>
        <!--                    <td class="p-2 bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">-->
        <!--                        <div class="flex flex-col">-->
        <!--                            <span class="text-center pb-2">Scheduled Show</span>-->
        <!--                            <div class="w-full h-64 bg-gray-400"></div>-->
        <!--                        </div>-->
        <!--                    </td>-->
        <td onclick="getReminderModal.showModal()"
            class="column-width p-2 bg-purple-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-purple-600 hover:border-blue-500 cursor-pointer">
          <div class="flex flex-col">
            <span class="text-center pb-2">New Release</span>
            <div class="w-full h-64 bg-gray-400"></div>
          </div>
        </td>

        <td colspan="3" onclick="getReminderModal.showModal()"
            class="column-width p-2 hidden xl:table-cell bg-blue-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-blue-600 hover:border-blue-500 cursor-pointer">
          <div class="flex flex-col">
            <span class="text-center pb-2">Live Event</span>
            <div class="w-full h-64 bg-gray-400"></div>
          </div>
        </td>

        <td onclick="getReminderModal.showModal()"
            class="column-width p-2 hidden 2xl:table-cell bg-yellow-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-yellow-600 hover:border-blue-500 cursor-pointer">
          <div class="flex flex-col">
            <span class="text-center pb-2">News Program</span>
            <div class="w-full h-64 bg-gray-400"></div>
          </div>
        </td>
        <td onclick="getReminderModal.showModal()"
            class="column-width p-2 hidden 2xl:table-cell bg-green-800 text-sm 2xl:text-md border border-0.5 border-green-300 hover:bg-green-600 hover:border-blue-500 cursor-pointer">
          <div class="flex flex-col">
            <span class="text-center pb-2">Scheduled Show</span>
            <div class="w-full h-64 bg-gray-400"></div>
          </div>
        </td>

      </tr>


      </tbody>

    </table>

    <PopUpModal :id="`goToNowPlayingModal`">
      <template v-slot:header>Now Playing</template>
      <template v-slot:main><span class="text-orange-500">This modal is temporary. This will take you to the now playing show or episode page.</span>
      </template>
    </PopUpModal>
    <PopUpModal :id="`getReminderModal`">
      <template v-slot:header>Set Reminder</template>
      <template v-slot:main><span class="text-orange-500">This modal is temporary. Set a reminder when this show starts and/or subscribe to the show to get all notifications when new episodes are released or the show goes live.</span>
      </template>
    </PopUpModal>

    <div class="bg-gray-600 rounded-lg shadow m-10 p-4">


      <TodayView />
    </div>


  </div>
</template>

<script setup>
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useScheduleStore } from "@/Stores/ScheduleStore"
import Message from "@/Components/Global/Modals/Messages"
import PopUpModal from "@/Components/Global/Modals/PopUpModal"
import TodayView from '@/Components/Global/Calendar/TodayView.vue'

usePageSetup('schedule')

const appSettingStore = useAppSettingStore()
const scheduleStore = useScheduleStore()

let props = defineProps({
  can: Object,
})

</script>

<style scoped>

.column-width {
  @apply w-16
}

</style>
