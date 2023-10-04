<template>
    <div v-if="data.last_page > 1" class="flex flex-wrap justify-center my-4 space-x-4 space-y-2">
        <div></div>
        <Component
            :is="link.url ? 'Link' : 'span'"
            :id="id"
            v-for="(link, key) in filteredLinks"
            :key="key"
            class="px-4 py-3 text-sm leading-4"
            :class="{
        'bg-orange-400 text-white hover:bg-orange-400': link.active,
        'rounded mt-2 mr-0.5 dark:bg-gray-900 dark:hover:bg-gray-900 dark:text-gray-700': !link.url,
        'rounded bg-gray-800 hover:bg-gray-600 focus:text-indigo-500 hover:shadow': link.url
      }"
            :href="link.url"
            v-html="link.label"
            @scroll.prevent
        />
    </div>
</template>


<script setup>
import {computed, ref} from 'vue';
const { data, id } = defineProps(['data', 'id']);
const currentPage = ref(1); // Current page number (adjust as needed)
const itemsPerPage = 5; // Number of items per page (adjust as needed)
const filteredLinks = computed(() => {
    const startIndex = Math.max(currentPage.value - 1, 0) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;

    // Select the first, second, active, second-to-last, and last links from the prop data
    const firstLink = data.links[startIndex];
    const secondLink = data.links[startIndex + 1];
    const activeLink = data.links.find(link => link.active);
    const secondToLastLink = data.links[data.links.length - 2];
    const lastLink = data.links[data.links.length - 1];

    // Return the selected links in an array
    return [firstLink, secondLink, activeLink, secondToLastLink, lastLink].filter(Boolean);
});
// defineProps({
//     data: Object,
//     id: String,
// })
</script>
<!--class="px-1 text-gray-300 hover:text-blue-300"-->
<!--:class="{ 'text-gray-300 hover:text-gray-300': ! link.url, 'font-bold' : link.active }"-->
<!--text-gray-300 hover:text-blue-300-->
<!--text-gray-300 hover:text-gray-300-->
