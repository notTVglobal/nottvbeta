<template>
    <tr>
        <td class="text-gray-500 px-6 py-4 text-sm">
            <img :src="`${props.member.profile_photo_url}`" alt="" class="rounded-full min-w-[4rem]">
        </td>

        <td class="text-xl font-medium px-6 py-4">
            {{  props.member.name }}
        </td>

        <td class="text-gray-500 px-6 py-4 text-sm">
            {{  props.member.position }}
        </td>

        <td class="text-gray-500 px-6 py-4 text-sm">
            {{  props.member.phone }}
        </td>

        <td class="text-gray-500 px-6 py-4 text-sm">
            {{  props.member.email }}
        </td>

        <td class="px-6 py-4">
            <button v-if="props.member.team_members.active === 1" class="text-green-400 text-xl font-semibold">Active</button>
            <button v-if="props.member.team_members.active === 0" class="text-gray-400 text-xl font-semibold" disabled>Inactive</button>
        </td>

        <td class="px-6 py-4">
            <form @submit.prevent="submit" class="mt-6">
            <button class="bg-red-600 text-white hover:bg-red-500 text-xl font-semibold ml-2 my-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
                    type="submit"
                    :disabled="form.processing"
                    @click.prevent="deleteTeamMember(props.member.name)"
                    >
                Remove</button>
            </form>
        </td>
    </tr>

    <ConfirmDialog :member="props.member" @confirmDelete="submit"/>

</template>

<script setup>
import {useForm} from "@inertiajs/inertia-vue3";
import {useTeamStore} from "@/Stores/TeamStore";
import {Inertia} from "@inertiajs/inertia";
import ConfirmDialog from "@/Components/Modals/ConfirmDialog";

let teamStore = useTeamStore();

let props = defineProps({
    member: Object,
});


let form = useForm({
    user_id: props.member.team_members.user_id,
    team_id: props.member.team_members.team_id,
    team_slug: teamStore.slug,
})

teamStore.confirmDialog = false;

async function deleteTeamMember(memberName) {
    teamStore.deleteMemberName = memberName;
    teamStore.confirmDialog = true;
}

function submit() {
    // alert('are you sure?');
    form.post(route('teams.removeTeamMember'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            Inertia.visit(route('teams.manage', [teamStore.slug]), {
                method: 'get',
                preserveScroll: true,
            })
        }
    })
};

defineEmits ([
    'removeMember'
])
</script>
