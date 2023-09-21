<template>
    <div>
        <button v-if="teamStore.can.editShow && props.episodeStatusId !== 9 && props.episodeStatusId !== 10" :class="episodeStatusClass" @click="openEpisodeStatuses()">{{ episodeStatus }}</button>
        <div v-if="!teamStore.can.editShow || props.episodeStatusId === 9 || props.episodeStatusId === 10" class="cursor-not-allowed" :class="episodeStatusClass">{{ episodeStatus }}</div>

        <dialog :id="dialogId" class="modal">
            <div class="modal-box h-fit overflow-scroll">
                <h2 class="text-center mb-2">Change the Episode Status:</h2>
<!--                <ul tabindex="0" class="dropdown-content z-[1] p-2 shadow bg-base-100 rounded-box w-52">-->
                    <div v-for="(status, key)  in episodeStatuses" :key="key" class="text-center">
                        <button class="btn btn-wide my-1" @click="changeEpisodeStatus(episodeId, status.id)">{{ status.name }}</button>
                    </div>

<!--                </ul>-->
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

let teamStore = useTeamStore()

let props = defineProps({
    episodeId: '',
    episodeStatus: '',
    episodeStatusId: '',
    episodeStatuses: Object,
})

const errorMessage = ref('');
const dialogId = props.episodeId+'episodeStatuses'

function openEpisodeStatuses() {
    document.getElementById(dialogId).showModal()
}

async function changeEpisodeStatus(episodeId, newStatusId) {
    try {
        const response = await axios.post('/shows/episode/changeEpisodeStatus', {
            episode_id: episodeId,
            new_status_id: newStatusId,
        });
        // Handle success response as needed
        // ...
    } catch (error) {
        if (error.response) {
            // If there is an error response, set the error message
            errorMessage.value = error.response.data.message;

            // Console error the error message
            console.error(errorMessage.value);
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
