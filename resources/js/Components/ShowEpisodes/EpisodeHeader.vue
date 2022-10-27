
<template>

    <h3 class="inline-flex items-center text-3xl font-semibold relative">
        <img :src="'/storage/images/' + episode.poster" alt="" class="rounded-full h-20 w-20 object-cover">
        <Link :href="`/shows/${show.slug}/episode/${episode.slug}`" class="pl-5">{{ episode.name }}</Link>
    </h3>

    <div class="my-6 ml-10 md:w-3/4">
        <div class="text-sm font-semibold uppercase mb-2">Episode Description</div>
        {{ episode.description }}
    </div>





</template>

<script setup>
import {useShowStore} from "@/Stores/ShowStore.js";
import {useTeamStore} from "@/Stores/TeamStore.js";

let showStore = useShowStore();
let teamStore = useTeamStore();

defineProps({
    episode: Object,
    show: Object,
    team: Object,
})

</script>
