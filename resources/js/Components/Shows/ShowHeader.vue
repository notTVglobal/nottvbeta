
<template>
        <div class="flex justify-between mb-6">
            <Link href="/shows" class="text-blue-500 text-sm ml-2">Return to shows list</Link>
            <Link :href="`/shows/edit/${show.id}`" class="text-blue-500 text-sm ml-2">Edit {{ show.name }}</Link>
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

                <div class="mr-8">
                    <button
                        class="bg-green-500 hover:bg-green-600 text-white mt-1 ml-6 px-4 py-2 rounded disabled:bg-gray-400"
                        @click="$emit('add')"
                    >Add an episode</button>
                </div>
            </div>
        </header>

</template>

<script setup>
import {useShowStore} from "@/Stores/ShowStore.js";

let show = useShowStore();

</script>
