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
                class="font-medium text-orange-800"> {{ show?.category?.name }} </span></div>
            <div><span class="text-xs font-semibold mr-2 uppercase">Sub-category: </span><span
                class="font-medium text-orange-800"> {{ show?.subCategory?.name }} </span></div>

          <div class="mt-12 w-full h-full flex flex-col">
            <div class="flex-grow"></div>
            <div v-if="show.isScheduled && show.scheduleDetails">
              <div>
                Your show is currently scheduled as <strong>{{ show.scheduleDetails.type }}</strong>.
                <div v-if="show.scheduleDetails.type === 'one-time'">
                  It will start on <strong>{{ userStore.formatDateTimeFromUtcToUserTimezone(show.scheduleDetails.startTime) }}&nbsp;{{ userStore.timezoneAbbreviation }}</strong> and last for <strong>{{ show.scheduleDetails.durationMinutes }} minutes</strong>.
                </div>
                <div v-else>
                  <template v-if="Array.isArray(show.scheduleDetails.daysOfWeek)">
                    It recurs on <strong>{{ show.scheduleDetails.daysOfWeek.join(', ') }}</strong>
                  </template>
                  <template v-else>
                    It recurs on <strong>{{ show.scheduleDetails.daysOfWeek }}</strong>
                  </template>
                  starting at <strong>{{ userStore.formatTimeFromDateInUserTimezone(show.scheduleDetails.startTime) }}&nbsp;{{ userStore.timezoneAbbreviation }}</strong> with each occurrence lasting <strong>{{ show.scheduleDetails.durationMinutes }} minutes</strong>.
                </div>
              </div>
            </div>

            <div v-if="can.editShow">
            <button v-if="!show.isScheduled" onclick="addShowToScheduleModal.showModal()"
                    :disabled="scheduleStore.savingToSchedule"
                    class="btn btn-lg bg-green-500 hover:bg-green-700 border-green-500 text-white drop-shadow-lg py-2 flex flex-col">
              <span v-if="!scheduleStore.savingToSchedule">Add Show To Schedule</span>
              <span v-else>The schedule is being updated...</span>
            </button>
            <button v-if="show.isScheduled" onclick="changeScheduleModal.showModal()"
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
        NOTE: You may want to schedule episodes individually.
      </template>
      <template #button-label>
        Add
      </template>
    </AddShowToSchedule>

    <ChangeSchedule :show="show"/>


  </div>
</template>

<script setup>
import { useShowStore } from "@/Stores/ShowStore"
import { useTeamStore } from "@/Stores/TeamStore"
import { useUserStore } from "@/Stores/UserStore"
import { useScheduleStore } from "@/Stores/ScheduleStore"
import SingleImage from "@/Components/Global/Multimedia/SingleImage"
import Button from '@/Jetstream/Button.vue'
import AddShowToSchedule from '@/Components/Global/Schedule/AddShowToSchedule.vue'
import ChangeSchedule from '@/Components/Global/Schedule/ChangeShowSchedule.vue'

const showStore = useShowStore()
const teamStore = useTeamStore()
const userStore = useUserStore()
const scheduleStore = useScheduleStore()

defineProps({
  show: Object,
  team: Object,
  can: Object,
})

</script>
