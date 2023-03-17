<template>

    <div class="container mx-auto px-4 mb-12">

        <!--                                <h2 class="text-yellow-500 uppercase tracking-wide font-semibold">EPISODES</h2>-->
        <div class="w-full bg-gray-800 text-2xl p-4 mb-4">EPISODES</div>

        <div class="popular-movies text-sm grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-8">

                <div v-for="episode in episodes.data"
                     :key="episode.id"
                     class="movie mt-8 max-w-[12rem] mx-auto">

                    <div class="relative inline-block">
                        <Link :href="`/shows/${props.show.slug}/episode/${episode.slug}`">
                            <img :src="'/storage/images/' + episode.poster" alt="episode cover" class="h-28 min-w-[12rem] w-28 object-cover hover:opacity-75 transition ease-in-out duration-150">
                        </Link>
                    </div>
                    <Link :href="`/shows/${props.show.slug}/episode/${episode.slug}`" class="block text-base font-semibold leading-tight hover:text-gray-400 mt-4">{{ episode.name }}</Link>
                    <div class="text-gray-400 mt-1">{{ formatDate(episode.created_at) }}</div>
                </div>

        </div>

    <!-- Paginator -->
    <Pagination :links="props.episodes.links" class="mt-12 mb-6 pb-6 border-b border-gray-800"/>
    </div>



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
