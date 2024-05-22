<template>
  <div>
    <button
        v-if="as === 'button'"
        type="submit"
        :class="[baseClasses, activeClasses, darkClasses]"
    >
      <slot />
    </button>

    <a
        v-else-if="as === 'a'"
        :href="href"
        :class="[baseClasses, activeClasses, darkClasses]"
    >
      <slot />
    </a>

    <Link
        v-else
        :href="href"
        :class="[baseClasses, activeClasses, darkClasses]"
    >
      <slot />
    </Link>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue'

let props = defineProps({
    active: Boolean,
    href: {
        type: String,
        required: false,
        default: '#', // Default value to avoid missing prop
    },
    as: String,
    dark: false,
});

const baseClasses = 'block px-4 py-2 text-sm leading-5 focus:outline-none transition';
const activeClasses = computed(() => props.active ? 'bg-indigo-200 text-gray-700' : '');
const darkClasses = computed(() => props.dark ? 'text-gray-50 hover:bg-gray-200 hover:text-gray-700 focus:bg-gray-100 focus:text-gray-700' : 'text-gray-700 hover:bg-gray-200 hover:text-gray-700 focus:bg-gray-100')
</script>
