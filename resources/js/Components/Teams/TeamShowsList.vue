<template>
    <div>
        <!-- Paginator -->
        <Pagination :data="shows" class="mb-6"/>

        <div class="flex flex-row flex-wrap pb-12">
            <div
                v-for="(show, key) in shows.data"
                :key="show.id"
                class="bg-gray-800 border-gray-700"
            >
                <Link :href="`/shows/${show.slug}`" class="text-gray-50 hover:text-blue-50">
                <div class=" min-w-[8rem] max-w-[14rem] px-6 py-4 font-medium break-words grow-0 w-full text-center text-gray-50 hover:text-blue-500">

                    <SingleImage :image="show.image" :poster="show.poster" :alt="'show cover'" class="h-64 min-w-[8rem] mb-4 object-cover hover:opacity-75 transition ease-in-out duration-150"/>
                    {{ show.name }}

                    <div class="text-xs uppercase text-gray-500">{{ show.categoryName[0] }}</div>
                    <div class="text-xs uppercase text-gray-500">{{ show.categorySubName[0] }}</div>
                </div>
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import Pagination from "@/Components/Pagination"
import SingleImage from "@/Components/Multimedia/SingleImage";

defineProps({
    shows: Object,
})
</script>
