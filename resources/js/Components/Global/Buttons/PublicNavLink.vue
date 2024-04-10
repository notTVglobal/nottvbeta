<template>
  <Button :href="href" :class="classes" class="leading">
    <slot />
  </Button>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia'
import Button from '@/Jetstream/Button.vue'

const classes = computed(() => {
  return props.active
      ? 'flex justify-center items-center p-2 bg-indigo-400 uppercase text-sm font-medium hover:bg-indigo-400 rounded leading-5 transition'
      : 'flex justify-center items-center p-2 hover:bg-indigo-400 leading-5 transition';
  // : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-400 hover:text-blue-700 hover:border-blue-600 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition';
});

const props = defineProps({
  href: String,
  active: Boolean,
});
</script>