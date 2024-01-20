<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { computed } from 'vue'

let props = defineProps({
    active: Boolean,
    href: String,
    as: String,
});

const baseClasses = 'block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition';
const activeClasses = computed(() => props.active ? 'bg-gray-100' : '');
</script>

<template>
  <div>
    <button
        v-if="as === 'button'"
        type="submit"
        :class="[baseClasses, activeClasses]"
    >
      <slot />
    </button>

    <a
        v-else-if="as === 'a'"
        :href="href"
        :class="[baseClasses, activeClasses]"
    >
      <slot />
    </a>

    <Link
        v-else
        :href="href"
        :class="[baseClasses, activeClasses]"
    >
      <slot />
    </Link>
  </div>
</template>
