<template>
    <tr>
        <td class="text-gray-500 px-6 py-4 text-sm">
            <img v-if="props.member.profile_photo_path"
                :src="`/storage/${props.member.profile_photo_path}`"
                alt=""
                class="h-16 w-16 rounded-full object-cover">
            <img v-if="!props.member.profile_photo_path"
                 :src="`${props.member.profile_photo_url}`"
                 alt=""
                 class="h-16 w-16 rounded-full object-cover">
        </td>

        <td class="text-xl font-medium px-6 py-4">
            {{  props.member.name }}
        </td>

        <td class="light:text-gray-600 dark:text-gray-200 px-6 py-4 text-sm">
            {{  props.member.position }}
        </td>

        <td class="light:text-gray-600 dark:text-gray-200 px-6 py-4 text-sm">
            {{  props.member.phone }}
        </td>

        <td class="light:text-gray-600 dark:text-gray-200 px-6 py-4 text-sm">
            {{  props.member.email }}
        </td>

        <td class="px-6 py-4">
            <button v-if="props.member.team_members.active === 1" class="text-green-400 text-xl font-semibold">Active</button>
            <button v-if="props.member.team_members.active === 0" class="text-gray-400 text-xl font-semibold" disabled>Inactive</button>
        </td>

        <td v-if="teamStore.can.editTeam" class="px-6 py-4">
            <div>

                <button class="bg-red-600 text-white hover:bg-red-500 ml-2 my-2 px-2 py-1 rounded disabled:bg-gray-400 h-max w-max"
                        @click.prevent="deleteTeamMember(props.member)"
                        >
                    Remove</button>
            </div>
        </td>
    </tr>

    <ConfirmDialog :member="props.member" @confirmDelete="teamStore.deleteTeamMember"/>

</template>

<script setup>
import {useTeamStore} from "@/Stores/TeamStore";
import ConfirmDialog from "@/Components/Modals/ConfirmDialog";

let teamStore = useTeamStore();

let props = defineProps({
    member: Object,
});

teamStore.confirmDialog = false;

function deleteTeamMember(member) {
    teamStore.deleteMemberName = member.name;
    teamStore.deleteMemberId = member.id;
    teamStore.confirmDialog = true;
}

defineEmits ([
    'removeMember'
])
</script>
