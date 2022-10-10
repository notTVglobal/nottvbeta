
<template>
    <div class="flex flex-wrap-reverse justify-end gap-2">
        <div class="">
            <Link
                :href="`/golive`"><button
                class="px-4 py-2 text-white bg-red-600 hover:bg-red-500 rounded-lg disabled:bg-gray-400"
                disabled
            >Go Live</button>
            </Link>
        </div>
        <div class="">
            <Link
                :href="`/shows/${show.id}/edit`"><button
                class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
            >Edit</button>
            </Link>
        </div>
        <div class="">
            <Link
                :href="`/teams/${team.id}/manage`"><button
                class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
            >Team</button>
            </Link>
        </div>
        <div>
            <Link :href="`/dashboard`"><button
                class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
            >Dashboard</button>
            </Link>
        </div>
    </div>
    <header class="flex justify-between mb-3">
            <h3 class="inline-flex items-center text-3xl font-semibold relative">
                <img :src="'/storage/images/' + posterName" alt="" class="w-20 mr-2">
                <Link :href="`/shows/${show.id}`" class="">{{ show.name }}</Link>
            </h3>
    </header>

    <div class="my-6 ml-10 md:w-3/4">
        {{ show.description }}
    </div>

    <div class="flex justify-end mt-6">
        <div class="flex flex-col">
            <div><span class="text-xs capitalize font-semibold">Team: </span><Link :href="`/teams/${team.id}/manage`" class="text-blue-500 ml-2"> {{ team.name }} </Link></div>
            <div><span class="text-xs capitalize font-semibold mr-2">Show Runner: </span> {{ showRunnerName }} </div>
        </div>
    </div>


</template>

<script setup>
import {useShowStore} from "@/Stores/ShowStore.js";

let showStore = useShowStore();

defineProps({
    show: Object,
    team: Object,
    showRunnerName: String,
    posterName: String,
})

</script>
