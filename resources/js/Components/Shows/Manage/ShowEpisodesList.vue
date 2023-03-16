<template>


    <table class="min-w-full divide-y divide-gray-200">
        <thead class="divide-y divide-gray-200">
        <!--                                <tr v-for="episode in episodes.data" :key="episode.id">-->
        <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                Ep.#
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                        <div class="text-sm font-medium pl-14">
                            <!--                                                    <Link :href="`/admin/users/${episode.id}`" class="text-indigo-600 hover:text-indigo-900">{{ episode.name }}</Link>-->
                            Name
                        </div>
                </div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                Notes
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                Status
            </td>
        </tr>
        </thead>

        <tbody class="divide-y divide-gray-200">

            <ShowEpisode v-for="episode in props.episodes.data" :episode="episode" :showSlug="props.show.slug"/>

        </tbody>
    </table>

    <!-- Paginator -->
    <Pagination :links="episodes.links" class="mb-6"/>

</template>

<script setup>
import ShowEpisode from "@/Components/Shows/Manage/ShowEpisode.vue";
import {useShowStore} from "@/Stores/ShowStore";
import Pagination from "@/Components/Pagination";

// let showStore = useShowStore();

let props = defineProps({
    show: Object,
    episodes: Object,
    filters: Object,
    can: Object,
});

</script>
