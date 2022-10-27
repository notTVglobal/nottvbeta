<template>

    <div class="flex flex-row flex-wrap">
        <div
            v-for="episode in props.episodes.data"
            :key="episode.id"
            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
        >
            <div
                class="min-w-[8rem] px-6 py-4 font-medium text-gray-900 dark:text-white"
            >
                <Link :href="`/shows/${props.show.slug}/episode/${episode.slug}`" class="text-blue-800 hover:text-blue-600">
                    <img :src="'/storage/images/' + episode.poster" class="rounded-full h-32 w-32 object-cover">
                </Link>
            </div>
            <div
                class="px-6 py-4 font-medium text-gray-900 dark:text-white break-words grow-0 w-32"
            >
                <Link :href="`/shows/${props.show.slug}/episode/${episode.slug}`" class="text-blue-800 hover:text-blue-600">{{ episode.name }}</Link>
            </div>
        </div>
    </div>


    <!-- Paginator -->
    <Pagination :links="props.episodes.links" class="mb-6"/>

</template>

<script setup>
// import ShowEpisode from "@/Components/Shows/Manage/ShowEpisode.vue";
// import {useShowStore} from "@/Stores/ShowStore";
import Pagination from "@/Components/Pagination";

// let showStore = useShowStore();

let props = defineProps({
    show: Object,
    episodes: Object,
    filters: Object,
    // can: Object,
});

</script>
