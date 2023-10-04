<template>
    <div v-if="data.last_page >  1" class="flex flex-wrap justify-center my-4 space-x-2 md:space-x-4 space-y-2">
        <div></div>
        <Component
            :is="link.url ? 'Link' : 'span'"
            :id="id"
            v-for="(link, key) in filteredLinks"
            :key="key"
            :href="link.url"
            v-html="link.label"
            class="px-2 md:px-4 py-3 text-sm leading-4 h-fit"
            :class="{ 'text-white bg-orange-400 hover:bg-orange-400 dark:bg-orange-400 dark:hover:bg-orange-400': link.active,
                      'rounded mt-2 mr-0.5 bg-gray-100 light:bg-gray-100 light:hover:bg-gray-100 light:text-gray-300 dark:bg-gray-900 dark:hover:bg-gray-900 dark:text-gray-700': ! link.url,
                      'rounded bg-gray-200 light:bg-gray-200 light:hover:bg-gray-300 dark:bg-gray-800 dark:hover:bg-gray-600 light:text-gray-800 dark:text-gray-50 focus:text-indigo-500 hover:shadow': link.url }"
            @scroll.prevent/>
    </div>
</template>


<script setup>
import { ref, computed } from 'vue';

const { data, id } = defineProps(['data', 'id']);
const currentPage = ref(1); // Current page number (adjust as needed)
const itemsPerPage = 5; // Number of items per page (adjust as needed)

const filteredLinks = computed(() => {
    const startIndex = Math.max(currentPage.value - 1, 0) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;

    // Select the first, second, active (adjusted), second-to-last, and last links from the prop data
    const firstLink = data.links[startIndex];
    const secondLink = data.links[startIndex + 1];
    const activeLink = getAdjustedActiveLink(data.links, startIndex, endIndex);
    const secondToLastLink = data.links[data.links.length - 2];
    const lastLink = data.links[data.links.length - 1];

    // Return the selected links in an array
    return [firstLink, secondLink, activeLink, secondToLastLink, lastLink].filter(Boolean);
});

function getAdjustedActiveLink(links, startIndex, endIndex) {
    const activeIndex = links.findIndex(link => link.active);

    if (activeIndex === startIndex + 1 && links[startIndex + 1].active) {
        // If the second link is active, make it the third link
        return links[startIndex + 2];
    } else if (activeIndex === links.length - 2 && links[links.length - 2].active) {
        // If the second-to-last link is active, make it the third-to-last link
        return links[links.length - 3];
    }

    return links[activeIndex];
}
</script>

<!--class="px-1 text-gray-800 dark:text-gray-50 dark:hover:text-blue-400 hover:text-blue-400"-->
<!--:class="{ 'text-gray-300 dark:text-gray-700 dark:hover:text-gray-700 hover:text-gray-300': ! link.url, 'font-bold' : link.active }"-->
