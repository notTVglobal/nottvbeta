<template>
    <tr>
        <td class="text-gray-500 px-6 py-4 text-sm">
            <!--            <img :src="`https://i.pravatar.cc/50?u=${props.member.email}`" alt="" class="rounded-xl w-10">-->
            <img :src="`${props.member.profile_photo_url}`" alt="" class="rounded-full min-w-[4rem]">
        </td>

        <td class="text-xl font-medium px-6 py-4">
            <!--                                                    <Link :href="`/admin/users/${episode.id}`" class="text-indigo-600 hover:text-indigo-900">{{ episode.name }}</Link>-->
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
                    :disabled="form.processing">Remove</button>
            </form>
        </td>
    </tr>
</template>

<script setup>
import {useForm} from "@inertiajs/inertia-vue3";

let props = defineProps({
    member: Object,
    team: Object,
});

let form = useForm({
    user_id: props.member.team_members.user_id,
    team_id: props.member.team_members.team_id,
    team_slug: props.team.slug,
})

let submit = () => {
    form.post('/teams/removeTeamMember');
};
</script>
