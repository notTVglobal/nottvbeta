<template>
    <div>
        <div v-if="!userStore.isAdmin">
            <button v-if="teamStore.can.editShow && props.episodeStatusId !== 9 && props.episodeStatusId !== 10" :class="episodeStatusClass" @click="openEpisodeStatuses()">{{ episodeStatus }}</button>
            <div v-if="!teamStore.can.editShow || props.episodeStatusId === 9 || props.episodeStatusId === 10" class="cursor-not-allowed" :class="episodeStatusClass">{{ episodeStatus }}</div>
        </div>
        <div v-else>
            <button class="h-fit py-1" :class="episodeStatusClass" @click="openEpisodeStatuses()">{{ episodeStatus }}</button>
        </div>

        <dialog :id="dialogId" class="modal">
            <div class="modal-box h-fit bg-white text-black">
                <div v-if="props.episodeStatusId === 7">
                    <h3 class="text-center mb-2">Episode is Published</h3>
                    <p class="text-center mb-2">
                        Please contact the notTV Team if you need to change the episode status. This is because the episode is already promoted and distributed.
                    </p>
                    <p class="text-center">
                        Email <a class="text-blue-600 hover:text-blue-500" :href="`mailto:hello@not.tv?subject=Need%20Help%20With%20Published%20Episode%20ID:%20${props.episodeUlid}&body=Episode%20ID:%20${props.episodeUlid}%0AEpisode%20Name:%20${props.episodeName}%0AShow%20Name:%20${props.showName}`">hello@not.tv</a> for assistance
                    </p>
                    <p v-if="userStore.isAdmin" class="text-center mt-2">
                        <button @click="openAdminChangeStatusModal()" class="btn btn-wide my-2 text-white bg-orange-600 hover:bg-orange-500">Change Status (admin only)</button>
                    </p>
                </div>
                <div v-else>
                    <div v-if="!setDateTime">
                        <h2 class="text-center mb-2">Change the Episode Status:</h2>
                        <div v-for="(status, key)  in episodeStatuses" :key="key" class="text-center">
                            <div class="btn btn-wide my-1" @click="checkEpisodeStatus(episodeId, status.id)">{{ status.name }}</div>
                        </div>
                    </div>
                    <div v-if="setDateTime">
                        <h3 class="text-center mb-2">Set the Scheduled Release Date and Time:</h3>
                        <div class="text-center">
                            <DateTimePicker :date="convertedDate" @date-time-selected="handleScheduledDateTime" />
                            <button class="btn my-2" @click="changeEpisodeStatus(episodeId, 6)">Schedule it!</button>
                            <button class="btn ml-2 my-2" @click="cancelScheduleEpisode">Cancel</button>
                        </div>
                    </div>
                </div>

            </div>

            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>

        <dialog :id="`confirmPublishModal.${episodeId}`" class="modal">
            <div class="modal-box">
                <h3 class="text-center font-bold text-lg">Are you sure you want to publish?</h3>
                <p class="text-center py-4">This action cannot be undone. When you publish an episode it registers it on the blockchain and promotes it on the network.</p>
                <div class="modal-action">
                    <form method="dialog">
                            <button @click="changeEpisodeStatus(episodeId, 7)" class="btn text-white bg-green-600 hover:bg-green-500 mr-2">Yes! Publish Now!</button>
                            <button class="btn text-white bg-orange-600 hover:bg-orange-500">No, cancel</button>
                    </form>
                </div>
            </div>
        </dialog>

        <dialog :id="`adminChangeStatusModal.${episodeId}`" class="modal">
            <div class="modal-box bg-gray-100 text-black">
                <h3 class="text-center font-bold text-lg">Change Status (Admin Only)</h3>
                <p class="text-center py-4">This is the Admin Override to change the episode status. If the episode is already published and you un-publish it the episode will be reverted to "Review" and become inaccessible to the public.</p>
                <div class="w-full flex justify-center mt-2 pb-4">
                    <form method="dialog">
                        <button @click="changeEpisodeStatus(episodeId, 5)" class="btn text-white bg-blue-600 hover:bg-blue-500 mr-2">Un-publish</button>
                        <button @click="changeEpisodeStatus(episodeId, 9)" class="btn text-white bg-orange-600 hover:bg-orange-500 mr-2">Freeze</button>
                        <button @click="changeEpisodeStatus(episodeId, 10)" class="btn text-white bg-red-600 hover:bg-red-500">Restrict</button>
                    </form>
                </div>
                <p class="text-center py-4"><span class="font-semibold">Please Note:</span> Un-publishing will have an adverse affect on the promotional links already used.</p>
                <p class="text-center py-4"><span class="italic">Press ESC to close or <button @click="closeModals" class="text-blue-600 hover:text-blue-500">click here</button> to close.</span></p>
            </div>
        </dialog>

    </div>

</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { computed, ref, watch, watchEffect } from 'vue'
import { format } from "date-fns"
import { useTeamStore } from "@/Stores/TeamStore"
import { useShowStore } from "@/Stores/ShowStore"
import { useUserStore } from "@/Stores/UserStore"
import { useNotificationStore } from "@/Stores/NotificationStore"
import DateTimePicker from "@/Components/Global/Calendar/DateTimePicker"
import DateTimePickerSelect from "@/Components/Global/Calendar/DateTimePickerSelect"
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import timezone from 'dayjs/plugin/timezone';
import utc from 'dayjs/plugin/utc'; // Required for timezone support

const teamStore = useTeamStore()
const showStore = useShowStore()
const userStore = useUserStore()
const notificationStore = useNotificationStore()

// Extend Day.js with the plugins
dayjs.extend(relativeTime);
dayjs.extend(timezone);
dayjs.extend(utc);

let props = defineProps({
    episodeId: '',
    episodeUlid: '',
    episodeName: '',
    episodeSlug: '',
    episodeStatus: '',
    episodeStatusId: '',
    episodeStatuses: Object,
    showName: '',
    showSlug: '',
    scheduledDateTime: String,
})

// Ref for reactive timezone
const userTimezone = ref(userStore.timezone);

const convertedDate = ref('');
watchEffect(() => {
  if (userStore.timezone) {
    convertedDate.value = dayjs.utc(props.scheduledDateTime).tz(userStore.timezone).format();
  }
})

const convertTimeToUserTimezone = (date, timezone) => {
  // Convert the date to the provided timezone using Day.js
  return dayjs.tz(date, timezone);
};

const errorMessage = ref('');
const dialogId = props.episodeId+'episodeStatuses'

let scheduledDateTime = ref(null)
let setDateTime = ref(false)

function openEpisodeStatuses() {
    document.getElementById(dialogId).showModal()
}

// function checkEpisodeStatus(episodeId, newStatusId) {
//     setDateTime = true;
//     if (newStatusId === 6 && scheduledDateTime === null) {
//         // open a model to set scheduled dateTime
//
//         router.reload()
//     }
// }

const checkEpisodeStatus = (episodeId, statusId) => {
    if (statusId === 6) {
        // update the modal to set scheduled dateTime
        setDateTime.value = !setDateTime.value;
    } else if (statusId === 7) {
        // open modal to confirm they want to publish.
        document.getElementById('confirmPublishModal.' + props.episodeId).showModal()
    }
    else if (statusId !== 6) {
        changeEpisodeStatus(episodeId, statusId)
    }
};

const cancelScheduleEpisode = () => {
    setDateTime.value = !setDateTime.value;
};

const openAdminChangeStatusModal = () => {
    document.getElementById('adminChangeStatusModal.'+props.episodeId).showModal()
}

const handleScheduledDateTime = (newDate) => {
    selectedScheduledDateTime.value = newDate;
    scheduledDateTime = newDate.date;
    // console.log(scheduledDateTime)
    updateScheduledDateTime(scheduledDateTime)
    // console.log(formattedScheduledDateTime)
}

console.log('we already have the user timezone: ' + userStore.timezone)

// const getUserTimezone = () => {
//     // Use the Intl object to get the user's timezone
//     userTimezone.value = Intl.DateTimeFormat().resolvedOptions().timeZone;
// };

let selectedScheduledDateTime = ref('');
let formattedScheduledDateTime = ref(''); // This will display the formatted date and time

const convertToTimeZone = (dateTime, userTimezone) => {
    return format(dateTime, 'yyyy-MM-dd HH:mm:ssXXX', { userTimezone });
};

const updateScheduledDateTime = (scheduledDateTime) => {
    if (selectedScheduledDateTime.value) {
        // Convert the selected date and time to the desired time zone
        // const timeZone = 'UTC'; // Change this to your desired time zone
        formattedScheduledDateTime.value = convertToTimeZone(
            new dayjs(scheduledDateTime).tz(userStore.timezone).format()
        );
        // console.log('updated ScheduleDateTime to timezone: ' + formattedScheduledDateTime.value)
    } else {
        formattedScheduledDateTime.value = '';
    }
};

async function changeEpisodeStatus(episodeId, statusId) {
    try {
        const response = await axios.post('/shows/episode/changeEpisodeStatus', {
            episode_id: episodeId,
            new_status_id: statusId,
            scheduled_release_dateTime: formattedScheduledDateTime.value
        });
        // Handle success response as needed
        notificationStore.setToastNotification(response.data.message, 'success')
        // Check if the response contains an "alert" message
        if (response.data.alert) {
            // Display the alert message
            alert(response.data.alert);
        } else
        {
          // Update the showStore.episodes with the new data
          const updatedEpisode = response.data;
          const episodeIndex = showStore.episodes.data.findIndex(ep => ep.id === updatedEpisode.episode_id);
          if (episodeIndex !== -1) {
            showStore.episodes.data[episodeIndex].episodeStatusId = updatedEpisode.episode_status_id;
            showStore.episodes.data[episodeIndex].scheduledReleaseDateTime = updatedEpisode.scheduled_release_dateTime;
            // Update episodeStatus using episodeStatuses array from the store
            const status = showStore.episodeStatuses.find(status => status.id === updatedEpisode.episode_status_id);
            if (status) {
              showStore.episodes.data[episodeIndex].episodeStatus = status.name;
            }
          }
        }
    } catch (error) {
        if (error.response) {
          notificationStore.setToastNotification(error.response.data, 'error')
          showStore.errorMessage = error.response.data.error
        } else {
            // console.error(error);
        }
    }
    // return response
    document.getElementById(dialogId).close()
    router.reload()
}

const closeModals = () => {
    document.getElementById(dialogId).close()
    document.getElementById('confirmPublishModal.'+props.episodeId).close()
    document.getElementById('adminChangeStatusModal.'+props.episodeId).close()
    router.reload()
}

const episodeStatusClass = computed(() => ({
    'btn m-1 w-fit font-semibold text-xl text-orange-400': props.episodeStatusId===1,
    'btn m-1 w-fit text-xl text-green-400': props.episodeStatusId===2,
    'btn m-1 w-fit font-semibold text-xl text-green-600': props.episodeStatusId===3,
    'btn m-1 w-fit font-bold text-xl text-green-600': props.episodeStatusId===4,
    'btn m-1 w-fit font-semibold text-xl text-purple-700': props.episodeStatusId===5,
    'btn m-1 w-fit font-italic text-xl text-pink-500': props.episodeStatusId===6,
    'btn m-1 w-fit font-bold text-xl light:text-black dark:text-white': props.episodeStatusId===7,
    'btn m-1 w-fit font-medium text-xl text-gray-500': props.episodeStatusId===8,
    'btn m-1 w-fit font-semibold font-italic text-xl text-red-700': props.episodeStatusId===9,
    'btn m-1 w-fit font-bold text-xl text-red-800': props.episodeStatusId===10,
}))

</script>
