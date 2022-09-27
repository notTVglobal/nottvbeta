<!-- tec21: This component is currently not being used... I moved the code directly into the Teams/{$id}/Index.vue page for testing/dev purposes -->
<template>
        <div class="flex justify-between mb-6">
            <Link href="/teams" class="text-blue-500 text-sm ml-2">Return to teams list</Link>
            <Link :href="`/teams/${props.team.id}/edit`" class="text-blue-500 text-sm ml-2">Edit {{ props.team.name }}</Link>
        </div>

        <header class="flex justify-between">
            <div>
                <h3 class="inline-flex items-center text-3xl font-semibold relative">
                    <img :src="`../../images/Ping.png`" alt="" class="w-20 mr-2">
                    {{ props.team.name }} Team
                    <div
                        class="bg-green-400 w-5 h-5 text-xs text-white rounded-full flex justify-center items-center absolute -right-4 -top-2">
                        {{ props.team.memberSpots }}
                    </div>
                </h3>
                <div class="mt-2 p-5">
                    <p class="mb-6">
                        {{ props.team.description }}
                    </p>
                </div>
            </div>
            <div>
                <div class="mr-8 mt-6">
                    <button
                        class="bg-green-500 hover:bg-green-600 text-white mt-1 ml-6 px-4 py-2 rounded disabled:bg-gray-400"
                        :disabled="! spotsRemaining"
                        @click="$emit('add')"
                        >Add Member ({{ spotsRemaining }} spots left)</button>
                </div>
                <div class="mr-8">
                    <button
                        class="bg-green-500 hover:bg-green-600 text-white mt-1 ml-6 px-4 py-2 rounded disabled:bg-gray-400"
                    >Create Assignment</button>
                </div>
            </div>
        </header>

</template>

<script setup>
// import {useTeamStore} from "@/Stores/TeamStore.js";
import { ref, computed } from 'vue';

// let teamStore = useTeamStore();

let props = defineProps({
    team: Object
});
//
// const a = ref(3)
// const spotsRemaining = computed(() => props.team.memberSpots - a )

</script>
