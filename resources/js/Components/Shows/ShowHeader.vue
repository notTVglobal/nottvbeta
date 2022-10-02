
<template>
    <div class="flex justify-between mb-3">
        <div>
            <h3 class="inline-flex items-center text-3xl font-semibold relative">
                <img :src="`/storage/images/oeV64kpa339M8tmLEZrERCb7bLfuAy8BIqJ17x8t.png`" alt="" class="w-20 mr-2">
                {{ name }}
            </h3>
        </div>
        <span class="text-xs font-semibold text-purple-700">Creator Mode</span>
        <Link :href="`/dashboard`"><button
            class="hidden sm:block px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
        >Dashboard</button>
        </Link>
    </div>

    <div class="my-6 ml-10 md:w-3/4">
        {{ description }}
    </div>

    <header class="mt-10 flex justify-end mb-4">
        <div class="space-x-2">
                <Link
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded disabled:bg-gray-400"
                    :href="`/golive`"
                >Go Live</Link>
                <Link
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-3 rounded disabled:bg-gray-400"
                    :href="`/shows/${id}/edit`"
                >Edit Show</Link>
        </div>
    </header>

</template>

<script setup>
import {useShowStore} from "@/Stores/ShowStore.js";

let showStore = useShowStore();

defineProps({
    id: String,
    name: String,
    description: String,
})

</script>
