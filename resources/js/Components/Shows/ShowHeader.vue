
<template>
    <div class="flex justify-end mb-3">
        <Link href="/shows" class="text-blue-500 text-sm ml-2">All Shows</Link>
    </div>

    <header class="flex justify-between">
        <div>
            <h3 class="inline-flex items-center text-3xl font-semibold relative">
                <img :src="`/storage/images/oeV64kpa339M8tmLEZrERCb7bLfuAy8BIqJ17x8t.png`" alt="" class="w-20 mr-2">
                {{ show.name }}
            </h3>
            <div class="mt-2 p-5">
                <p class="mb-6">
                    {{ show.description }}
                </p>
            </div>
        </div>
        <div>

            <div class="mr-8 mb-2">
                <button
                    class="bg-green-500 hover:bg-green-600 text-white ml-6 px-4 py-2 rounded disabled:bg-gray-400"
                    @click="$emit('add')"
                >Add Episode</button>
            </div>
            <div class="mr-8 mb-3">
                <button
                    class="bg-red-500 hover:bg-red-600 text-white ml-6 px-4 py-2 rounded disabled:bg-gray-400"
                    @click="$emit('add')"
                >Stream Live</button>
            </div>
            <div class="mr-8 mb-2">
                <Link
                    class="bg-blue-500 hover:bg-blue-600 text-white ml-6 px-4 py-2 rounded disabled:bg-gray-400"
                    :href="`/shows/${show.id}/edit`"
                >Edit Show</Link>
            </div>
        </div>
    </header>

</template>

<script setup>
import {useShowStore} from "@/Stores/ShowStore.js";

let show = useShowStore();

</script>
