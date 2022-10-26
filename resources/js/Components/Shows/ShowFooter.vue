<template>

    <div class="flex justify-end mt-6">
        <!-- Paginator -->
        <!--                            <Pagination :links="`#`" class="mt-6"/>-->
        <Link :href="`/teams/${teamStore.id}`" class="text-blue-500 ml-2"> {{ teamStore.name }} © 2022 </Link>
    </div>

</template>

<script setup>
import {useShowStore} from "@/Stores/ShowStore";
import {useTeamStore} from "@/Stores/TeamStore";

let showStore = useShowStore();
let teamStore = useTeamStore();

</script>
