<template>
<div class="bg-orange-300 p-2 font-bold">Team Members</div>
    <div class="border-1 border-t mb-3 bg-orange-300 py-1 px-2 text-xs font-semibold text-red-800">
        In development. Not currently working.
    </div>
<!--    change :disabled for Add Member button to: :disabled="! spotsRemaining"-->
    <button
        class="bg-green-500 hover:bg-green-600 text-white ml-2 my-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
        @click="$emit('add')"
        :disabled="props.memberSpots === props.totalSpots"
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

                        <div class="text-sm font-medium text-gray-900">
                            <!--                                                    <Link :href="`/admin/users/${episode.id}`" class="text-indigo-600 hover:text-indigo-900">{{ episode.name }}</Link>-->
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

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <!-- Remove -->
            </td>
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <TeamMember v-for="member in props.members" :member="member" :team="props.team"/>
        </tbody>
    </table>
    <div class="text-right px-3 mt-2 text-gray-600 italic w-full"
         v-show="props.memberSpots === props.totalSpots">
        There are no remaining team spots. Edit the team to add more.
    </div>

</template>

<script setup>
import TeamMember from "@/Components/Teams/TeamMember.vue";
import {useTeamStore} from "@/Stores/TeamStore";
import {computed, reactive, ref} from "vue";

// let team = useTeamStore();

let props = defineProps({
    team: Object,
    members: Object,
    memberSpots: Number,
    totalSpots: Number,
})

// let showModal = ref(false);
let a = props.memberSpots
let b = props.totalSpots;
let spotsRemaining = computed(() => b - a)


</script>
