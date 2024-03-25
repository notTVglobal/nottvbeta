<template>
  <div>
      <div class="flex flex-row items-start">
        <!--                <img :src="'/storage/images/' + show.poster" alt="" class="w-20 mr-2 justify-left">-->
        <Link :href="`/shows/${show.slug}`" class="uppercase">
          <div class="h-80">
            <SingleImage :image="show.image" alt="'show poster'" class="w-60 object-contain mr-2 justify-left"/>
          </div>
        </Link>
        <div class="flex flex-col pl-4">
          <Link :href="`/shows/${show.slug}`" class="uppercase"><span class="hover:text-blue-600 mb-3 inline-flex items-center text-3xl font-semibold relative">{{ show.name }}</span></Link>
            <div><span class="text-xs  font-semibold uppercase">Team: </span>
              <Link :href="`/teams/${team.slug}/manage`" class="text-blue-500 hover:text-blue-600 ml-2 uppercase font-bold"> {{
                  team.name
                }}
              </Link>
            </div>
            <div><span class="text-xs font-semibold mr-2 uppercase">Show Runner: </span><span
                class="font-medium font-bold"> {{ show.showRunner.name }} </span></div>
            <div class="pt-4"><span class="text-xs font-semibold mr-2 uppercase">Category: </span><span
                class="font-medium text-orange-800"> {{ show.category.name }} </span></div>
            <div><span class="text-xs font-semibold mr-2 uppercase">Sub-category: </span><span
                class="font-medium text-orange-800"> {{ show.subCategory.name }} </span></div>

          <div class="mt-12 w-full h-full flex flex-col">
            <div class="flex-grow"></div>
            <div v-if="show.isScheduled && show.scheduleDetails.length > 0">
              <div class="mb-2" v-for="(detail, index) in show.scheduleDetails" :key="index">
                Your show is currently scheduled as <strong>{{ detail.type }}</strong>.
                <div v-if="detail.type === 'one-time'">
                  It will start on <strong>{{ userStore.formatDateTimeFromUtcToUserTimezone(detail.startDateTime) }}&nbsp;{{userStore.timezoneAbbreviation}}</strong> and last for <strong>{{ detail.durationMinutes }} minutes</strong>.
                </div>
                <div v-else>
                  <template v-if="Array.isArray(detail.daysOfWeek)">
                    It recurs on <strong>{{ detail.daysOfWeek.join(', ') }}</strong>
                  </template>
                  <template v-else>
                    It recurs on <strong>{{ detail.daysOfWeek }}</strong>
                  </template>
                  starting at <strong>{{ userStore.formatTimeInUserTimezone(detail.startTime) }}&nbsp;{{userStore.timezoneAbbreviation}}</strong> with each occurrence lasting <strong>{{ detail.durationMinutes }} minutes</strong>.
                </div>

              </div>
            </div>
            <div v-if="can.editShow">
            <button v-if="!show.isScheduled" onclick="addShowToScheduleModal.showModal()"
                    class="btn btn-lg bg-green-500 hover:bg-green-700 border-green-500 text-white drop-shadow-lg py-2 flex flex-col">
              <span>Add Show To Schedule</span>
            </button>
            <button v-if="show.isScheduled" onclick="changeShowScheduleModal.showModal()"
                    class="btn btn-lg bg-indigo-500 hover:bg-indigo-700 border-indigo-500 text-white drop-shadow-lg py-2 flex flex-col">
              <span>Change Schedule</span>
            </button>
            </div>
          </div>
        </div>

      </div>

    <AddShowToSchedule :show="show">
      <template #form-title>
        Add your show to the schedule
      </template>
      <template #form-description>
        Add your show to the schedule
      </template>
      <template #button-label>
        Add
      </template>
    </AddShowToSchedule>

    <ChangeShowSchedule :show="show"/>


  </div>
</template>

<script setup>
import { useShowStore } from "@/Stores/ShowStore"
import { useTeamStore } from "@/Stores/TeamStore"
import { useUserStore } from "@/Stores/UserStore"
import SingleImage from "@/Components/Global/Multimedia/SingleImage"
import Button from '@/Jetstream/Button.vue'
import AddShowToSchedule from '@/Components/Global/Schedule/AddShowToSchedule.vue'
import ChangeShowSchedule from '@/Components/Global/Schedule/ChangeShowSchedule.vue'

const showStore = useShowStore()
const teamStore = useTeamStore()
const userStore = useUserStore()

defineProps({
  show: Object,
  team: Object,
  can: Object,
})

</script>
