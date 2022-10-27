
<template>


            <h3 class="inline-flex items-center text-3xl font-semibold relative">
                <img :src="'/storage/images/' + show.poster" alt="" class="w-20 mr-2">
                <Link :href="`/shows/${show.slug}`" class="">{{ show.name }}</Link>
            </h3>







</template>

<script setup>
import {useShowStore} from "@/Stores/ShowStore.js";
import {useTeamStore} from "@/Stores/TeamStore.js";

let showStore = useShowStore();
let teamStore = useTeamStore();

defineProps({
    show: Object,
    team: Object,
})

</script>
