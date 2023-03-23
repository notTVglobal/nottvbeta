<template>
    <div v-if="data.links.length > 2" class="flex flex-wrap justify-center my-4 space-x-4 space-y-2">
        <Component
            :is="link.url ? 'Link' : 'span'"
            v-for="(link, key) in data.links"
            :key="key"
            :href="link.url"
            v-html="link.label"
            class="px-4 py-3 text-sm leading-4 h-fit"
            :class="{ 'text-white bg-orange-400 hover:bg-orange-400 dark:bg-orange-400 dark:hover:bg-orange-400': link.active,
                      'rounded mt-2 mr-0.5 bg-gray-100 hover:bg-gray-100 text-gray-300 dark:bg-gray-900 dark:hover:bg-gray-900 dark:text-gray-700': ! link.url,
                      'rounded bg-gray-200 hover:bg-gray-300 dark:bg-gray-800 dark:hover:bg-gray-600 focus:text-indigo-500 hover:shadow': link.url }"
            @scroll.prevent/>
    </div>
</template>


<script setup>
defineProps({
    data: Object,
})
</script>

<!--class="px-1 text-gray-800 dark:text-gray-50 dark:hover:text-blue-400 hover:text-blue-400"-->
<!--:class="{ 'text-gray-300 dark:text-gray-700 dark:hover:text-gray-700 hover:text-gray-300': ! link.url, 'font-bold' : link.active }"-->
