<template>

    <div class="flex flex-row flex-wrap">
        <div v-for="episode in props.episodes.data"
             :key="episode.id"
             class="pb-8 bg-white border-b dark:bg-gray-800 dark:border-gray-700">

            <Link :href="`/shows/${props.show.slug}/episode/${episode.slug}`" class="hover:opacity-60 text-gray-900 dark:text-white">
                <div class="flex flex-col min-w-[8rem] px-6 py-4 font-medium break-words grow-0">
                    <img :src="'/storage/images/' + episode.poster" class="rounded-full h-32 w-32 object-cover mb-2">
                    <span>{{ episode.name }}</span>
                    <span class="text-xs uppercase font-semibold text-gray-500">
                        {{ formatDate(episode.created_at) }}
                    </span>
                </div>
            </Link>

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
