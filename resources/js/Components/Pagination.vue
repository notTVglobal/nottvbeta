<template>
    <div>
        <Component
            :is="link.url ? 'Link' : 'span'"
            v-for="link in links"
            :href="link.url"
            v-html="link.label"
            class="px-1 text-gray-800 dark:text-gray-50 dark:hover:text-blue-400 hover:text-blue-400"
            :class="{ 'text-gray-300 dark:text-gray-700 dark:hover:text-gray-700 hover:text-gray-300': ! link.url, 'font-bold' : link.active }"
            preserve-scroll/>
    </div>
</template>


<script setup>
defineProps({
    links: Array,
})
</script>
