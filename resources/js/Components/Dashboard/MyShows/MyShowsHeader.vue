<template>
    <div>
        <div class="mb-6 pb-6 flex justify-between border-b border-gray-800">
            <div class="mb-1 font-semibold text-xl dark:text-gray-50">
                My Shows
            </div>

            <div v-if="can.createShow" class="">

                <div v-if="hasShows">
                    <Link :href="`/shows/create`"><button
                        class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 text-xs rounded disabled:bg-gray-400"
                    >Create Show</button>
                    </Link>
                </div>

                <div v-else>
                    <button class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 text-xs rounded disabled:bg-gray-400"
                            @click="createShowWithNoTeamButton">
                        Create Show
                    </button>
                    <dialog id="dashboardNoTeams" class="modal">
                        <div class="modal-box">
                            <h3 class="font-bold text-lg mb-3">You don't have any teams!</h3>
                            <button class="btn btn-primary" @click="navigateToCreateTeam">Create a Team</button>
                            <p class="py-4">Press ESC key or click outside to close</p>
                        </div>
                        <form method="dialog" class="modal-backdrop">
                            <button>close</button>
                        </form>
                    </dialog>
                </div>

            </div>
        </div>
    </div>
</template>

<script setup>
import {Inertia} from "@inertiajs/inertia";

defineProps({
    can: Object,
    hasShows: Boolean,
})

function createShowWithNoTeamButton() {
    document.getElementById('dashboardNoTeams').showModal()
}

const navigateToCreateTeam = () => {
    Inertia.visit('teams/create');
};

</script>
