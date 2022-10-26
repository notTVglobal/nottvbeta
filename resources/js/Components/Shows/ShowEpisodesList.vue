<template>

<button class="bg-orange-300 p-2 font-bold w-full text-left" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Episodes</button>
    <div class="collapse" id="collapseExample">
    <Link
        :href="`/shows/${props.show.id}/episode/create`"><button
        class="bg-green-500 hover:bg-green-600 text-white ml-2 my-2 px-4 py-2 rounded disabled:bg-gray-400 h-max w-max"
    >Create Episode</button></Link>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-white divide-y divide-gray-200">
        <!--                                <tr v-for="episode in episodes.data" :key="episode.id">-->
        <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                Ep.#
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                        <div class="text-sm font-medium text-gray-900 pl-14">
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
        <tbody class="bg-white divide-y divide-gray-200">
        <!--                                <tr v-for="episode in episodes.data" :key="episode.id">-->
        <ShowEpisode v-for="episode in props.episodes" :name="episode.name" :notes="episode.notes" :status="episode.episodeStatus_id" />
        </tbody>
    </table>

    <!-- Paginator -->
<!--    <Pagination :links="episodes.links" class="mb-6"/>-->
    </div>
</template>

<script setup>
import ShowEpisode from "@/Components/Shows/ShowEpisode.vue";
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
