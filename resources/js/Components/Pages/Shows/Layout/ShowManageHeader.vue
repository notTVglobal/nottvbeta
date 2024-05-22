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
            <div v-if="showStore.isScheduled && showStore.scheduleDetails">
              <div v-if="nextBroadcastDate">
                The next broadcast date is <strong>{{ userStore.formatDateTimeFromUtcToUserTimezone(nextBroadcastDate) }}&nbsp;{{ userStore.timezoneAbbreviation }}</strong>. <ConvertDateTimeToTimeAgo :dateTime="nextBroadcastDate" :class="`text-yellow-500`" />
              </div>
              <div v-else-if="isFutureBroadcast">
                The next broadcast date is <strong>{{ userStore.formatDateTimeFromUtcToUserTimezone(showStore.scheduleDetails.startTime) }}&nbsp;{{ userStore.timezoneAbbreviation }}</strong>. <ConvertDateTimeToTimeAgo :dateTime="showStore.scheduleDetails.startTime" :class="`text-yellow-500`" />
              </div>
              <div>
                Your show is currently scheduled as <strong>{{ showStore.scheduleDetails.type }}</strong>.
                <div v-if="showStore.scheduleDetails.type === 'one-time'">
                  It will start on <strong>{{ userStore.formatDateTimeFromUtcToUserTimezone(showStore.scheduleDetails.startTime) }}&nbsp;{{ userStore.timezoneAbbreviation }}</strong> and last for <strong>{{ showStore.scheduleDetails.durationMinutes }} minutes</strong>.
                </div>
                <div v-else>
                  <template v-if="Array.isArray(showStore.scheduleDetails.daysOfWeek)">
                    It recurs on <strong>{{ showStore.scheduleDetails.daysOfWeek.join(', ') }}</strong>
                  </template>
                  <template v-else>
                    It recurs on <strong>{{ showStore.scheduleDetails.daysOfWeek }}</strong>
                  </template>
                  starting at <strong>{{ userStore.formatTimeFromDateInUserTimezone(showStore.scheduleDetails.startTime) }}&nbsp;{{ userStore.timezoneAbbreviation }}</strong> with each occurrence lasting <strong>{{ showStore.scheduleDetails.durationMinutes }} minutes</strong>.
                </div>
              </div>
            </div>

            <div v-if="can.editShow">
            <button v-if="!showStore.isScheduled" @click="openAddShowToScheduleModal"
                    :disabled="showStore.loadingUpdatingStatus || showStore.isUpdatingSchedule || showStore.isSaving"
                    class="btn btn-lg bg-green-500 hover:bg-green-700 border-green-500 text-white drop-shadow-lg py-2 flex flex-col disabled:text-white">
              <span v-if="showStore.loadingUpdatingStatus" class="loading loading-dots text-white"></span>
              <span v-else-if="!showStore.isUpdatingSchedule && !showStore.isSaving" class="">Add Show To Schedule</span>
              <span v-else>The schedule is being updated...</span>
            </button>
            <button v-if="showStore.isScheduled" @click="openChangeScheduleModal"
                    :disabled="showStore.loadingUpdatingStatus || showStore.isUpdatingSchedule || showStore.isSaving"
                    class="btn btn-lg bg-indigo-500 hover:bg-indigo-700 border-indigo-500 text-white drop-shadow-lg py-2 flex flex-col">
              <span v-if="showStore.loadingUpdatingStatus" class="loading loading-dots text-white"></span>
              <span v-else-if="!showStore.isUpdatingSchedule && !showStore.isSaving">Change Schedule</span>
              <span v-else>The schedule is being updated...</span>
            </button>
              <div v-if="showStore.isUpdatingSchedule" class="mt-4 text-red-600">
                <span>{{ showStore.updatedBy }} is currently updating the schedule...</span>
              </div>
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
import { computed, onBeforeUnmount, onMounted, ref } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { useShowStore } from "@/Stores/ShowStore"
import { useUserStore } from "@/Stores/UserStore"
import { useNotificationStore } from "@/Stores/NotificationStore"
import SingleImage from "@/Components/Global/Multimedia/SingleImage"
import Button from '@/Jetstream/Button.vue'
import AddShowToSchedule from '@/Components/Global/Schedule/AddShowToSchedule.vue'
import ChangeSchedule from '@/Components/Global/Schedule/ChangeShowSchedule.vue'
import dayjs from 'dayjs'
import utc from 'dayjs/plugin/utc';
import timezone from 'dayjs/plugin/timezone';
import ConvertDateTimeToTimeAgo from '@/Components/Global/DateTime/ConvertDateTimeToTimeAgo.vue'

dayjs.extend(utc);
dayjs.extend(timezone);

const showStore = useShowStore()
const userStore = useUserStore()
const notificationStore = useNotificationStore()

const page = usePage().props

const props = defineProps({
  show: Object,
  team: Object,
  can: Object,
})

const errors = ref();

const nextBroadcastDate = computed(() => {
  console.log('showStore.scheduleDetails:', showStore.scheduleDetails);

  const now = dayjs();
  if (showStore.scheduleDetails && showStore.scheduleDetails.broadcastDates) {
    const broadcastDatesObj = JSON.parse(showStore.scheduleDetails.broadcastDates);
    console.log('Parsed broadcastDates:', broadcastDatesObj);

    if (Array.isArray(broadcastDatesObj.broadcastDates)) {
      const upcomingDates = broadcastDatesObj.broadcastDates
          .map(dateStr => {
            const date = dayjs.tz(dateStr, broadcastDatesObj.timezone);
            console.log('date:', date.toString());
            return date;
          })
          .filter(date => date.isAfter(now))
          .sort((a, b) => a - b);

      console.log('upcomingDates:', upcomingDates);
      return upcomingDates.length > 0 ? upcomingDates[0].toISOString() : null;
    }
  }
  return null;
});

const isFutureBroadcast = computed(() => {
  return dayjs(showStore.scheduleDetails.startTime).isAfter(dayjs());
});

// Function to set up MutationObserver for a modal
const watchModal = (modalId, onClose) => {
  const modalElement = document.getElementById(modalId);
  if (!modalElement) return;

  const observer = new MutationObserver((mutationsList) => {
    for (let mutation of mutationsList) {
      if (mutation.type === 'attributes' && mutation.attributeName === 'open') {
        if (!modalElement.hasAttribute('open')) {
          onClose();
        }
      }
    }
  });

  observer.observe(modalElement, { attributes: true });

  return observer;
};

const openAddShowToScheduleModal = async () => {
  try {
    await showStore.setUpdatingStatus(true, page.user.name, props.show.slug);
    document.getElementById('addShowToScheduleModal').showModal();
    notificationStore.setToastNotification('Show meta loaded.', 'info')
  } catch (error) {
    errors.value = 'Failed to set saving state. Please try again.';
    notificationStore.setToastNotification(errors.value, 'error', 10000)
  }
};

const openChangeScheduleModal = async () => {
  try {
    await showStore.setUpdatingStatus(true, page.user.name, props.show.slug);
    document.getElementById('changeScheduleModal').showModal();
    notificationStore.setToastNotification('Show meta loaded.', 'info')
  } catch (error) {
    errors.value = 'Failed to set saving state. Please try again.';
    notificationStore.setToastNotification(errors.value, 'error', 10000)
  }
};

// Declare observers so they can be cleaned up on unmount
let addShowToScheduleObserver;
let changeScheduleObserver;

onMounted(async () => {
  addShowToScheduleObserver = watchModal('addShowToScheduleModal', async () => {
    await showStore.setUpdatingStatus(false, page.user.name, props.show.slug);
  });

  changeScheduleObserver = watchModal('changeScheduleModal', async () => {
    await showStore.setUpdatingStatus(false, page.user.name, props.show.slug);
  });

  // Check the flag in meta on page load
  console.log('show.meta:', props.show.meta);

  const meta = props.show.meta ? JSON.parse(props.show.meta) : {};
  console.log('Parsed meta:', meta);
  console.log('isUpdatingSchedule:', meta.isUpdatingSchedule);
  console.log('updatedBy:', meta.updatedBy);
  console.log('current user:', page.user.name);

  if (meta.isUpdatingSchedule && meta.updatedBy === page.user.name) {
    console.log('Resetting isUpdatingSchedule and updatedBy');
    await showStore.setUpdatingStatus(false, page.user.name, props.show.slug);
  }
});

// Store observers to disconnect them later
onBeforeUnmount(() => {
  if (addShowToScheduleObserver) addShowToScheduleObserver.disconnect();
  if (changeScheduleObserver) changeScheduleObserver.disconnect();
});
</script>
