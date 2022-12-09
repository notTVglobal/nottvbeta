<template>
    <div>
        <!-- Paginator -->
        <Pagination :links="shows.links" :id="showLinks" class="mb-6"/>

        <div class="flex flex-row flex-wrap pb-12">
            <div
                v-for="show in shows.data"
                :key="show.id"
                class="bg-white dark:bg-gray-800 dark:border-gray-700"
            >
                <div
                    class="min-w-[8rem] px-6 py-4 font-medium text-gray-900 dark:text-white"
                >
                    <!--                                                    <img :src="`/storage/images/${show.poster}`" class="rounded-full h-20 w-20 object-cover">-->
                    <Link :href="`/shows/${show.slug}`" class="text-blue-800 hover:text-blue-600">
                        <img :src="'/storage/images/' + show.poster" class="h-64 min-w-[8rem] w-48 object-cover hover:opacity-75 transition ease-in-out duration-150"></Link>
                </div>
                <div
                    class="px-6 py-4 font-medium text-gray-900 dark:text-white break-words grow-0 w-full text-center"
                >
                    <Link :href="`/shows/${show.slug}`" class="text-blue-800 hover:text-blue-600">{{ show.name }}</Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Pagination from "@/Components/Pagination"

defineProps({
    shows: Object,
})
</script>
