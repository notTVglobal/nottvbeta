<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: false,
        default: '#', // Default value to avoid missing prop
    },
    active: Boolean,
});

const classes = computed(() => {
    return props.active
        ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-200 focus:outline-none focus:border-indigo-700 transition'
        : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-400 hover:text-white hover:border-indigo-400 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition';
        // : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-400 hover:text-blue-700 hover:border-blue-600 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition';
});
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
