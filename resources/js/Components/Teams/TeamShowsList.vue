<template>
    <div class="bg-orange-300 p-2 font-bold">Shows</div>
    <Link
        :href="`/shows/create`">
        <button
        class="bg-green-500 hover:bg-green-600 text-white ml-2 mt-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
    >Create Show</button></Link>

    <table class="table-auto min-w-full divide-y divide-gray-200">
        <thead class="bg-white divide-y divide-gray-200">
        <!--                                <tr v-for="episode in episodes.data" :key="episode.id">-->
        <tr>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div>
                        <div class="text-sm font-medium text-gray-900 pl-14">
                            <!--                                                    <Link :href="`/admin/users/${episode.id}`" class="text-indigo-600 hover:text-indigo-900">{{ episode.name }}</Link>-->
                            Show Name
                        </div>
                    </div>
                </div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                Notes
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                Show Runner
            </td>

        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
        <!--                                <tr v-for="episode in episodes.data" :key="episode.id">-->
        <TeamShow v-for="show in shows" :id="show.id" :name="show.name" :description="show.description" :poster="show.poster" :showRunner="show.showRunner" />
        <!--        <TeamMember v-for="member in team.members" :name="member.name" :email="member.email" :status="member.status" />-->
        </tbody>
    </table>

</template>

<script setup>
import Pagination from "@/Components/Pagination";
import ShowEpisode from "@/Components/Shows/ShowEpisode.vue";
import {useShowStore} from "@/Stores/ShowStore";
import {useTeamStore} from "@/Stores/TeamStore";
import TeamShow from "@/Components/Teams/TeamShow";
import {useForm} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";

let showStore = useShowStore();
let teamStore = useTeamStore();

defineProps({
    shows: Object,
})


</script>
