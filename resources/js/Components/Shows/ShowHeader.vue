
<template>
    <div class="flex justify-between mb-3">
        <Link href="/shows" class="text-blue-500 text-sm ml-2">All Shows</Link>
        <Link :href="`/dashboard`"><button
            class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
        >Dashboard</button>
        </Link>
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
        <div class="space-y-4 mr-8">
            <div class="">
                <button
                    class="bg-red-500 hover:bg-red-600 text-white ml-6 px-4 py-2 rounded disabled:bg-gray-400"
                    @click="$emit('add')"
                >Stream Live</button>
            </div>
            <div class="">
                <Link
                    class="bg-blue-500 hover:bg-blue-600 text-white ml-6 px-4 py-3 rounded disabled:bg-gray-400"
                    :href="`/shows/${show.id}/edit`"
                >Edit Show</Link>
            </div>
            <div class="">
                <button
                    class="bg-green-500 hover:bg-green-600 text-white ml-6 px-4 py-2 rounded disabled:bg-gray-400"
                    @click="$emit('add')"
                >Add Episode</button>
            </div>
        </div>
    </header>

</template>

<script setup>
import {useShowStore} from "@/Stores/ShowStore.js";

let show = useShowStore();

</script>
