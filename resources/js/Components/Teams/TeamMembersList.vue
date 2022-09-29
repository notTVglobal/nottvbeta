<template>
<div class="bg-orange-300 p-2 font-bold">Team Members</div>
    <button
        class="bg-green-500 hover:bg-green-600 text-white my-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
        :disabled="! spotsRemaining"
        @click="$emit('add')"
    >Add Member ({{ spotsRemaining }} spots left)</button>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-white divide-y divide-gray-200">
        <!--                                <tr v-for="episode in episodes.data" :key="episode.id">-->
        <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <!-- Avatar -->
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div>
                        <div class="text-sm font-medium text-gray-900">
                            <!--                                                    <Link :href="`/admin/users/${episode.id}`" class="text-indigo-600 hover:text-indigo-900">{{ episode.name }}</Link>-->
                            Name
                        </div>
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
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
        <!--                                <tr v-for="episode in episodes.data" :key="episode.id">-->
        <TeamMember v-for="member in team.members" :name="member.name" :email="member.email" :status="member.status" />
        </tbody>

    </table>

</template>

<script setup>
import TeamMember from "@/Components/Teams/TeamMember.vue";
import {useTeamStore} from "@/Stores/TeamStore";

let team = useTeamStore();

</script>
