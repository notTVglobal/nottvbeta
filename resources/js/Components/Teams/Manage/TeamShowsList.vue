<template>
    <div>
        <div class="bg-orange-300 text-black p-2 font-bold">Shows</div>


        <table class="table-auto min-w-full divide-y divide-gray-200">
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
                v-for="show in shows"
                :show="show"
                :can="can"
            />

            </tbody>
        </table>
    </div>

</template>

<script setup>
import {useShowStore} from "@/Stores/ShowStore";
import {useTeamStore} from "@/Stores/TeamStore";
import TeamShow from "@/Components/Teams/Manage/TeamShow";

let showStore = useShowStore();
let teamStore = useTeamStore();

defineProps({
    shows: Object,
    can: Object,
})

</script>
