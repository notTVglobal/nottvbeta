<template>
    <div>
<!--        <div class="bg-orange-300 text-black p-2 font-bold">Shows</div>-->

        <div>
            <button
                v-if="teamStore.can.manageTeam"
                @click="userStore.btnRedirect('/shows/create')"
                class="bg-green-500 hover:bg-green-600 text-white font-semibold ml-2 mt-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
            >Create Show
            </button>
        </div>

        <div v-if="isSmallScreen" class="stacked-table">
            <div v-for="show in shows.data" :key="show.id" class="card">
                <div><b>Show Name:</b> {{ show.name }}</div>
                <div><b>Show Notes:</b> {{ show.notes }}</div>
                <!-- Add other fields similarly -->
            </div>
        </div>
        <table v-else class="table-auto min-w-full divide-y divide-gray-200">
            <thead class="divide-y divide-gray-200">
            <tr>
                <td class="px-6 py-4">
    <!--                Poster-->
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                    Show Name

                </td>

                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    Show Notes
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    Category
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    Show Status
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                    Show Runner
                </td>

                <td v-if="can.manageTeam || can.editTeam" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">

                </td>

            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">

            <TeamShow
                v-for="show in shows.data"
                :show="show"
                :can="can"
            />

            </tbody>
        </table>
        <!-- Paginator -->
        <Pagination :data="shows" class="mt-6" />
    </div>

</template>

<script setup>
import { useShowStore } from "@/Stores/ShowStore";
import { useTeamStore } from "@/Stores/TeamStore";
import { useUserStore } from "@/Stores/UserStore";

import TeamShow from "@/Components/Teams/Manage/Elements/TeamShow";
import { ref, onMounted, onBeforeUnmount } from 'vue';
import Pagination from "@/Components/Pagination"

const showStore = useShowStore();
const teamStore = useTeamStore();
const userStore = useUserStore()

const isSmallScreen = ref(false);

const checkScreenSize = () => {
    const width = window.innerWidth;
    isSmallScreen.value = width < 800; // Adjust this value for your breakpoints
};

defineProps({
    shows: Object,
    can: Object,
})

onMounted(() => {
    checkScreenSize(); // Check screen size on mount
    window.addEventListener('resize', checkScreenSize); // Add resize listener
});

onBeforeUnmount(() => {
    window.removeEventListener('resize', checkScreenSize); // Clean up listener
});

</script>
