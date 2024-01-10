<template>
    <tr>
        <td class="text-gray-500 px-6 py-4 min-w-[3rem] max-w-[3rem] text-sm">
            <img v-if="props.member.profile_photo_path"
                :src="`/storage/${props.member.profile_photo_path}`"
                alt=""
                class="min-h-[3rem] min-w-[3rem] max-w-[3rem] rounded-full object-cover">
            <img v-if="!props.member.profile_photo_path"
                 :src="`${props.member.profile_photo_url}`"
                 alt=""
                 class="min-h-[3rem] min-w-[3rem] max-w-[3rem] rounded-full object-cover">
        </td>

        <td class="text-xl font-medium px-6 py-4">
            <div class="pl-3">
                {{  props.member.name }}
            </div>
        </td>

        <td class="light:text-gray-600 px-6 py-4 text-sm">
            <span v-if="props.member.id === teamStore.teamCreator.id">Team Creator</span>
            <span v-else-if="props.member.id === teamStore.teamLeader.id">Team Leader</span>
            <span v-else-if="teamStore.managers.some(manager => manager.id === props.member.id)">Team Manager</span>
        </td>

        <td class="light:text-gray-600 px-6 py-4 text-sm">
            {{  props.member.phone }}
        </td>

        <td class="light:text-gray-600 px-6 py-4 text-sm">
            {{  props.member.email }}
        </td>

        <td class="px-6 py-4">
            <button v-if="props.member.team_members.active === 1" class="text-green-400 text-xl font-semibold">Active</button>
            <button v-if="props.member.team_members.active === 0" class="text-gray-400 text-xl font-semibold" disabled>Inactive</button>
        </td>

        <td v-if="can.editTeam" class="px-6 py-4">
            <div>
                <button v-if="props.member.id !== teamStore.teamLeader.id" class="bg-blue-600 text-white hover:bg-blue-500 ml-2 my-2 px-2 py-1 rounded disabled:bg-gray-400 h-max w-max"
                        @click.prevent="confirmTeamManager(props.member)"
                >
                    <span v-if="addManager">Make Manager</span><span v-if="removeManager">Remove Manager</span></button>
                <button v-if="props.member.id !== teamStore.teamLeader.id" class="bg-red-600 text-white hover:bg-red-500 ml-2 my-2 px-2 py-1 rounded disabled:bg-gray-400 h-max w-max"
                        @click.prevent="deleteTeamMember(props.member)"
                        >
                    Remove</button>
            </div>
        </td>

    </tr>

    <ConfirmRemoveTeamMemberDialog :member="props.member" @confirmDelete="teamStore.deleteTeamMember"/>
    <ConfirmTeamManagerDialog :member="props.member" @confirmRemoveManager="teamStore.removeTeamManager" @confirmAddManager="teamStore.addTeamManager">
        <span v-if="teamStore.addManager">add</span>
        <span v-if="!teamStore.addManager">remove</span>
    </ConfirmTeamManagerDialog>

</template>

<script setup>
import {useTeamStore} from "@/Stores/TeamStore";
import ConfirmRemoveTeamMemberDialog from "@/Components/Modals/ConfirmRemoveTeamMemberDialog";
import ConfirmTeamManagerDialog from "@/Components/Modals/ConfirmTeamManagerDialog.vue";
import {onBeforeMount, ref} from "vue";

let teamStore = useTeamStore();

let removeManager = ref(false)
let addManager = ref(false)

let props = defineProps({
    member: Object,
    can: Object,
});

teamStore.confirmDialog = false;

function deleteTeamMember(member) {
    teamStore.deleteMemberName = member.name;
    teamStore.deleteMemberId = member.id;
    teamStore.confirmDialog = true;
}

function confirmTeamManager(member) {
    teamStore.selectedManagerName = member.name;
    teamStore.selectedManagerId = member.id;
    teamStore.confirmManagerDialog = true;

    teamStore.addManager = !teamStore.managers.some(manager => manager.id === member.id);


}

const memberID = props.member.id;

const isManager = teamStore.managers.some(manager => manager.id === memberID);

if (isManager) {
    removeManager = true;
    addManager = false;
} else if (!isManager) {
    addManager = true;
    removeManager = false;
}

onBeforeMount(() => {

})

defineEmits ([
    'removeMember'
])
</script>
