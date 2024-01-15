<template>
<!--<div class="bg-orange-300 text-black p-2 font-bold">Team Members</div>-->
<!--    moved this button to the header of the Manage Team page -->
<!--    <button-->
<!--        class="bg-green-500 hover:bg-green-600 text-white ml-2 my-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"-->
<!--        @click="openModal"-->
<!--        :disabled="!teamStore.spotsRemaining"-->
<!--        v-if="teamStore.can.editTeam"-->
<!--    >Add Member ({{ teamStore.spotsRemaining }} spots left)</button>-->


    <div v-if="can.editTeam">
        <button
            class="bg-green-500 hover:bg-green-600 text-white font-semibold ml-2 my-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
            @click="openModal"
            :disabled="!teamStore.spotsRemaining"
            v-if="can.isTeamOwner || can.isTeamLeader || can.isTeamManager"
        >Add Member ({{ teamStore.spotsRemaining }} spots left)
        </button>
    </div>



    <div class="overflow-x-auto">
    <table class="table min-w-full divide-y divide-gray-200">
        <thead class="divide-y divide-gray-200">
        <tr>
            <td class="px-6 min-w-[3rem] max-w-[3rem] whitespace-nowrap text-sm font-medium">
                <!-- Avatar -->
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">

                        <div class="pl-3 text-sm font-medium">
                            Name
                        </div>

                </div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                Position
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                Phone
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                Email
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                Status
            </td>

            <td v-if="can.manageTeam" class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <!-- Remove -->
            </td>
        </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <TeamMember v-for="member in teamStore.members" :member="member" :team="team" :key="member.id" :can="can"/>
        </tbody>
    </table>
    </div>
    <div class="text-right px-3 mt-2 text-gray-600 italic w-full"
         v-show="!teamStore.spotsRemaining">
        There are no remaining team spots. Edit the team to add more.
    </div>

    <Teleport to="body">
        <TeamAddMember :can="can" :creatorFilters="props.creatorFilters" :creators="props.creators"/>
    </Teleport>


</template>

<script setup>
import { onBeforeMount } from "vue"
import TeamMember from "@/Components/Teams/Manage/Elements/TeamMember.vue";
import TeamAddMember from "@/Components/Teams/Manage/Elements/TeamAddMember.vue";
import { useTeamStore } from "@/Stores/TeamStore";

let teamStore = useTeamStore();

let props = defineProps({
    team: Object,
    creators: Object,
    creatorFilters: Object,
    can: Object,
})

onBeforeMount(async () => {
    teamStore.showModal = false;
})

function openModal() {
    teamStore.showModal = true;
}

</script>
