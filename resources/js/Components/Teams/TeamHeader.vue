
<template>
        <div class="flex justify-between mb-6">
            <Link href="/teams" class="text-blue-500 text-sm ml-2">Return to teams list</Link>
            <Link :href="`/teams/edit/${team.id}`" class="text-blue-500 text-sm ml-2">Edit {{ team.name }}</Link>
        </div>

        <header class="flex justify-between">
            <div>
                <h3 class="inline-flex items-center text-3xl font-semibold relative">
                    <img :src="`../../images/Ping.png`" alt="" class="w-20 mr-2">
                    {{ team.name }} Team
                    <div
                        class="bg-green-400 w-5 h-5 text-xs text-white rounded-full flex justify-center items-center absolute -right-4 -top-2">
                        {{ team.spots }}
                    </div>


                </h3>
            </div>
            <div class="mr-8 mt-6">
                <button
                    class="bg-green-500 hover:bg-green-600 text-white mt-8 ml-6 px-4 py-2 rounded disabled:bg-gray-400"
                    :disabled="! team.spotsRemaining"
                    @click="$emit('add')"
                    >Add Member ({{ team.spotsRemaining }} spots left)</button>
            </div>
        </header>

        <div class="flex justify-between mt-12 mb-6 p-5">

            <p class="mb-6">
                {{ team.description }}
            </p>

        </div>

</template>

<script setup>
import {useTeamStore} from "@/Stores/TeamStore.js";

let team = useTeamStore();

</script>
