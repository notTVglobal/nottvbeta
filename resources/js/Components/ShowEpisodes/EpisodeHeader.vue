
<template>

    <div class="inline-flex items-center text-3xl font-semibold relative">
        <Link :href="`/shows/${show.slug}/episode/${episode.slug}`" class="pl-5 uppercase">
            <div class="flex flex-row space-x-2">
                <!--                <img :src="'/storage/images/' + show.poster" alt="" class="w-20 mr-2 justify-left">-->
                <SingleImage :image="show.image" :poster="show.poster" alt="'episode poster'" class="rounded-full h-20 w-20 object-cover mr-2" />
                <span class="inline-flex items-center text-3xl font-semibold relative">{{ episode.name }}</span>
            </div>
        </Link>
    </div>






</template>

<script setup>
import {useShowStore} from "@/Stores/ShowStore.js";
import {useTeamStore} from "@/Stores/TeamStore.js";
import SingleImage from "@/Components/Multimedia/SingleImage.vue";

let showStore = useShowStore();
let teamStore = useTeamStore();

defineProps({
    episode: Object,
    show: Object,
    team: Object,
})

</script>
