<template>
    <div>
        <button v-if="teamStore.can.editShow && props.episodeStatusId !== 9 && props.episodeStatusId !== 10" :class="episodeStatusClass" @click="openEpisodeStatuses()">{{ episodeStatus }}</button>
        <div v-if="!teamStore.can.editShow || props.episodeStatusId === 9 || props.episodeStatusId === 10" class="cursor-not-allowed" :class="episodeStatusClass">{{ episodeStatus }}</div>

        <dialog :id="dialogId" class="modal">
            <div class="modal-box h-fit overflow-scroll">
                <div v-if="!setDateTime">
                    <h2 class="text-center mb-2">Change the Episode Status:</h2>
                    <div v-for="(status, key)  in episodeStatuses" :key="key" class="text-center">
                        <div class="btn btn-wide my-1" @click="checkEpisodeStatus(episodeId, status.id)">{{ status.name }}</div>
                    </div>
                </div>
                <div v-if="setDateTime">
                    <h3 class="text-center mb-2">Set the Scheduled Release Date and Time:</h3>
                    <div class="text-center">
                        <DateTimePicker :date="props.scheduled_release_dateTime" @date-time-selected="handleScheduledDateTime" />
                        <button class="btn my-2" @click="changeEpisodeStatus(episodeId, 6)">Schedule it!</button>
                        <button class="btn ml-2 my-2" @click="cancelScheduleEpisode">Cancel</button>
                    </div>
                </div>

            </div>

            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>

    </div>

</template>

<script setup>

import {computed, ref} from "vue";
import {Inertia} from "@inertiajs/inertia";
import {useTeamStore} from "@/Stores/TeamStore";
import {useShowStore} from "@/Stores/ShowStore";
import DateTimePicker from "@/Components/Calendar/DateTimePicker.vue";
import DateTimePickerSelect from "@/Components/Calendar/DateTimePickerSelect.vue";
import {format} from "date-fns-tz";

let teamStore = useTeamStore()
let showStore = useShowStore()

let props = defineProps({
    episodeId: '',
    episodeStatus: '',
    episodeStatusId: '',
    episodeStatuses: Object,
    scheduledDateTime: '',
})

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
//         Inertia.reload()
//     }
// }

const checkEpisodeStatus = (episodeId, statusId) => {
    if (statusId === 6) {
        // update the modal to set scheduled dateTime
        setDateTime.value = !setDateTime.value;
    } else if (statusId !== 6) {
        changeEpisodeStatus(episodeId, statusId)
    }
};

const cancelScheduleEpisode = () => {
    setDateTime.value = !setDateTime.value;
};

const handleScheduledDateTime = (newDate) => {
    selectedScheduledDateTime.value = newDate;
    scheduledDateTime = newDate.date;
    console.log(scheduledDateTime)
    updateScheduledDateTime()
    console.log(formattedScheduledDateTime)
}

const userTimezone = ref('');

const getUserTimezone = () => {
    // Use the Intl object to get the user's timezone
    userTimezone.value = Intl.DateTimeFormat().resolvedOptions().timeZone;
};

let selectedScheduledDateTime = ref('');
let formattedScheduledDateTime = ref(''); // This will display the formatted date and time

const convertToTimeZone = (dateTime, userTimezone) => {
    return format(dateTime, 'yyyy-MM-dd HH:mm:ssXXX', { userTimezone });
};

const updateScheduledDateTime = () => {
    if (selectedScheduledDateTime.value) {
        // Convert the selected date and time to the desired time zone
        // const timeZone = 'UTC'; // Change this to your desired time zone
        formattedScheduledDateTime.value = convertToTimeZone(
            new Date(scheduledDateTime),
            userTimezone
        );
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
        // ...
    } catch (error) {
        if (error.response) {
            showStore.errorMessage = error.response.data.error
        } else {
            console.error(error);
        }
    }
    // return response
    document.getElementById(dialogId).close()
    Inertia.reload()
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
