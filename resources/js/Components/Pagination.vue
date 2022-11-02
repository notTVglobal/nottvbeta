<template>
    <div>
        <Component
            :is="link.url ? 'Link' : 'span'"
            v-for="link in links"
            :href="link.url"
            v-html="link.label"
            class="px-1 text-gray-800 dark:text-gray-50"
            :class="{ 'text-gray-300 dark:text-gray-700': ! link.url, 'font-bold' : link.active }"
            preserve-scroll/>
    </div>
</template>


<script setup>
defineProps({
    links: Array,
})
</script>
