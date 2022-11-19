<template>
    <div>
        <Component
            :is="link.url ? 'Link' : 'span'"
            v-for="link in links"
            :href="link.url"
            v-html="link.label"
            class="px-1 text-gray-300 hover:text-blue-300"
            :class="{ 'text-yellow-800 hover:text-yellow-800': ! link.url, 'font-bold' : link.active }"
            preserve-scroll/>
    </div>
</template>


<script setup>
defineProps({
    links: Array,
})
</script>
