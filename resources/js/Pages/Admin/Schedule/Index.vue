<template>
  <Head title="Admin/Schedule"/>

  <div class="place-self-center flex flex-col">
    <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <AdminHeader>Schedule</AdminHeader>

      <div class="flex flex-row justify-between gap-x-4 mb-3">
        <div>
          <Link :href="`#`">
            <button
                class="btn btn-sm bg-green-500 hover:bg-green-600 text-white px-4 py-2 mr-2 rounded disabled:bg-gray-400"
                disabled
            >Blank
            </button>
          </Link>
          <Link :href="`#`">
            <button
                class="btn btn-sm bg-green-500 hover:bg-green-600 text-white px-4 py-2 mr-2 rounded disabled:bg-gray-400"
                disabled
            >Blank
            </button>
          </Link>
          <Link :href="`#`">
            <button
                class="btn btn-sm bg-green-500 hover:bg-green-600 text-white px-4 py-2 mr-2 rounded disabled:bg-gray-400"
                disabled
            >Blank
            </button>
          </Link>
          <button
              @click.prevent="purgeAllCaches"
              class="btn btn-sm bg-green-500 hover:bg-green-600 text-white px-4 py-2 mr-2 rounded-lg disabled:bg-gray-400"
          >
            Purge All Caches
          </button>


        </div>
        <input v-model="adminStore.searchTerm" type="search" placeholder="Search..." class="border px-2 rounded-lg"/>
      </div>

      <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

              <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <div
                    class="table w-full text-sm text-left text-gray-500 dark:text-gray-400"
                >
                  <div
                      class="table-header-group text-xs text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                  >
                    <div class="table-row">
                      <div scope="col" class="table-cell px-6 py-3 uppercase">
                        <font-awesome-icon icon="fa-repeat" class="mr-2 cursor-pointer hover:text-blue-500"
                                           @click.prevent="resetCalendar"/>
                        <span class="uppercase">Calendar </span>
                      </div>


                    </div>
                  </div>
                  <div class="table-row-group">

                    <div class="calendar">

                      <!-- Calendar View -->
                      <div class="calendar-view">
                        <MonthView />

                        <!-- View Selector -->
                        <div class="view-selector px-6 space-x-2 border-t-2 border-gray-50 pt-6">
                          <span class="text-xm uppercase font-semibold tracking-wider">View: </span>
                          <button @click="setView('week')" class="py-1 px-2 text-black rounded-lg" :class="[currentView === 'week' ? 'bg-blue-200' : 'bg-gray-100 hover:bg-gray-200']">Week</button>
                          <button @click="setView('threeDay')" class="py-1 px-2 text-black rounded-lg" :class="[currentView === 'threeDay' ? 'bg-blue-200' : 'bg-gray-100 hover:bg-gray-200']">3-Day</button>
                          <button @click="setView('oneDay')" class="py-1 px-2 text-black rounded-lg" :class="[currentView === 'oneDay' ? 'bg-blue-200' : 'bg-gray-100 hover:bg-gray-200']">1-Day</button>
                          <button  @click="setToToday" class="py-1 px-2 text-black rounded-lg" :class="{'bg-blue-200': scheduleStore.isToday, 'bg-gray-100 hover:bg-gray-200': !scheduleStore.isToday}">Today</button>
                        </div>
                        <WeekView v-if="currentView === 'week'"/>
                        <ThreeDayView v-if="currentView === 'threeDay'"/>
                        <TodayView v-if="currentView === 'oneDay'"/>
                      </div>
                    </div>





                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>





      </div>
    </div>
  </div>

</template>


<script setup>
import { Inertia } from '@inertiajs/inertia'
import { onBeforeUnmount, onMounted, onUnmounted, ref, watch } from 'vue'
import throttle from 'lodash/throttle'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useScheduleStore } from '@/Stores/ScheduleStore'
import { useAdminStore } from '@/Stores/AdminStore'
import AdminHeader from '@/Components/Pages/Admin/AdminHeader'
import Message from '@/Components/Global/Modals/Messages'
import Pagination from '@/Components/Global/Paginators/Pagination'
import SourceSelector from '@/Components/Pages/Admin/Channels/SourceSelector'
import DynamicModal from '@/Components/Pages/Admin/Channels/DynamicModal'
import AddOrUpdateMistStreamModal from '@/Components/Global/MistStreams/AddOrUpdateMistStreamModal'
import MonthView from '@/Components/Global/Calendar/MonthView'
import WeekView from '@/Components/Global/Calendar/WeekView'
import ThreeDayView from '@/Components/Global/Calendar/ThreeDayView'
import TodayView from '@/Components/Global/Calendar/TodayView'

usePageSetup('admin.schedule')

const appSettingStore = useAppSettingStore()
const scheduleStore = useScheduleStore()
const adminStore = useAdminStore()

let props = defineProps({})

const reloadSchedule = () => {
  //
}

const setToToday = () => {
  // Use the existing action to set the selected day to today
  scheduleStore.setSelectedDayToToday(new Date());
  currentView.value = 'oneDay'
};

const currentView = ref('oneDay')

const setView = (view) => {
  currentView.value = view
}

const purgeAllCaches = () => {
  scheduleStore.resetAll()
  Inertia.post('/invalidate-caches/')
  Inertia.reload()
}

const resetCalendar = () => {
  scheduleStore.reset()
  scheduleStore.checkAndFetchForUpcomingContent()
}

onBeforeUnmount(() => {
  scheduleStore.reset()
})

</script>

<style scoped>

#tooltip {
  background: #333;
  color: white;
  font-weight: bold;
  padding: 4px 8px;
  font-size: 13px;
  border-radius: 4px;
}

:deep(.popper) {
  background: #333333;
  padding: 2px;
  border-radius: 20px;
  color: #fff;
  font-weight: bold;
  text-transform: uppercase;
}

:deep(.popper #arrow::before) {
  background: #333333;
}

:deep(.popper:hover),
:deep(.popper:hover > #arrow::before) {
  background: #333333;
}
</style>
