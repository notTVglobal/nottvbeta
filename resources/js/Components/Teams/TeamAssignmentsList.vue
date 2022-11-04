<template>
    <div class="bg-orange-300 p-2 font-bold">Team Assignments</div>
    <div class="border-1 border-t mb-3 bg-orange-300 py-1 px-2 text-xs font-semibold text-red-800">
        In development. Not currently working.
    </div>
    <Link
        :href="`#`"><button
        class="bg-green-500 hover:bg-green-600 text-white ml-2 my-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
        disabled
    >Create Assignment</button></Link>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-white divide-y divide-gray-200">
        <!--                                <tr v-for="episode in episodes.data" :key="episode.id">-->
        <tr>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div>
                        <div class="text-sm font-medium text-gray-900 pl-14">
                            <!--                                                    <Link :href="`/admin/users/${episode.id}`" class="text-indigo-600 hover:text-indigo-900">{{ episode.name }}</Link>-->
                            Assignment
                        </div>
                    </div>
                </div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                Description
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                Assigned To
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                Notes
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                Status
            </td>
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
        <!--                                <tr v-for="episode in episodes.data" :key="episode.id">-->
<!--        <TeamAssignment v-for="episode in show.episodes" :name="episode.name" :description="episode.description" :poster="episode.poster" />-->
        </tbody>

    </table>

</template>

<script setup>
import TeamAssignment from "@/Components/Teams/TeamAssignment.vue";
import {useShowStore} from "@/Stores/ShowStore";

let show = useShowStore();

</script>
