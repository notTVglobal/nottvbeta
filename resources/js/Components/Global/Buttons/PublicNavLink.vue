<template>
  <Button :href="href" :class="classes" class="leading">
    <slot />
  </Button>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3'
import Button from '@/Jetstream/Button.vue'

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
      ? 'flex justify-center items-center p-2 bg-indigo-400 uppercase text-sm font-medium hover:bg-indigo-400 rounded leading-5 transition'
      : 'flex justify-center items-center p-2 hover:bg-indigo-400 leading-5 transition';
  // : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-400 hover:text-blue-700 hover:border-blue-600 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition';
});

</script>
