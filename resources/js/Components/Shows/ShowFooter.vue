<template>

    <div class="flex justify-end mt-6">
        <!-- Paginator -->
        <!--                            <Pagination :links="`#`" class="mt-6"/>-->
        <Link :href="`/teams/${teamId}`" class="text-blue-500 ml-2"> {{ teamName }} © 2022 </Link>
    </div>

</template>

<script setup>
import {useShowStore} from "@/Stores/ShowStore";

let showStore = useShowStore();

defineProps({
    teamName: String,
    teamId: Number,
})

</script>
