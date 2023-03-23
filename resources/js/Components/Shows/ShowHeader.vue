
<template>
    <div>
        <Link :href="`/shows/${show.slug}`" class="uppercase">
            <div class="flex flex-row">
<!--                <img :src="'/storage/images/' + show.poster" alt="" class="w-20 mr-2 justify-left">-->
                <SingleImage :image="show.image" alt="'show poster'" class="w-20 mr-2 justify-left" />
                <span class="inline-flex items-center text-3xl font-semibold relative">{{ show.name }}</span>
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
    show: Object,
    team: Object,
})

</script>
