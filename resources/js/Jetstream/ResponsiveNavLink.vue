<script setup>
import {computed} from 'vue';
import {Link} from '@inertiajs/inertia-vue3';

const props = defineProps({
    active: Boolean,
    href: String,
    as: String,
});

const classes = computed(() => {
    return props.active
        ? 'block pl-3 pr-4 py-10 border-l-4 border-indigo-400 text-base font-medium text-gray-50 bg-indigo-500 focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-50 transition'
        : 'block pl-3 pr-4 py-6 border-l-4 border-gray-300 text-base font-medium text-gray-200 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition';
});
</script>

<template>
    <div>
        <button v-if="as == 'button'" :class="classes" class="w-full text-left">
            <slot/>
        </button>

        <Link v-else :href="href" :class="classes">
            <slot/>
        </Link>
    </div>
</template>
