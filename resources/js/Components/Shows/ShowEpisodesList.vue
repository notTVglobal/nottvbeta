<template>
<div class="bg-orange-300 p-2 font-bold">Episodes</div>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-white divide-y divide-gray-200">
        <!--                                <tr v-for="episode in episodes.data" :key="episode.id">-->
        <tr>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div>
                        <div class="text-sm font-medium text-gray-900 pl-14">
                            <!--                                                    <Link :href="`/admin/users/${episode.id}`" class="text-indigo-600 hover:text-indigo-900">{{ episode.name }}</Link>-->
                            Name
                        </div>
                    </div>
                </div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                Description
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                Cast
            </td>
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
        <!--                                <tr v-for="episode in episodes.data" :key="episode.id">-->
        <ShowEpisode v-for="episode in show.episodes" :name="episode.name" :description="episode.description" :poster="episode.poster" />
        </tbody>

    </table>

</template>

<script setup>
import ShowEpisode from "@/Components/Shows/ShowEpisode.vue";
import {useShowStore} from "@/Stores/ShowStore";

let show = useShowStore();

</script>
