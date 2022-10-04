
<template>

    <header class="flex justify-between mb-3">
            <h3 class="inline-flex items-center text-3xl font-semibold relative">
                <img :src="`/storage/images/oeV64kpa339M8tmLEZrERCb7bLfuAy8BIqJ17x8t.png`" alt="" class="w-20 mr-2">
                {{ name }}
            </h3>

            <div class="flex flex-wrap-reverse justify-end space-x-4 gap-2">
                <div class="">
                    <Link
                    :href="`/shows/${id}/edit`"><button
                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                    >Edit Show</button>
                    </Link>
                </div>
                <div>
                    <Link :href="`/dashboard`"><button
                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                    >Dashboard</button>
                    </Link>
                </div>
            </div>
    </header>

    <div class="my-6 ml-10 md:w-3/4">
        {{ description }}
    </div>

    <div class="flex justify-end mt-6">
        <span class="text-xs capitalize font-semibold">TEAM: </span><Link href="/teams/1" class="text-blue-500 ml-2"> {{ show }} </Link>
    </div>


</template>

<script setup>
import {useShowStore} from "@/Stores/ShowStore.js";

let showStore = useShowStore();

defineProps({
    id: String,
    name: String,
    description: String,
    show: String,
})

</script>
