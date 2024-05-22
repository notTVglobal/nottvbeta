<template>
  <div v-if="errors.name" v-text="errors.name"
       class="bg-red-600 p-2 w-full text-white font-semibold mt-1 mb-6"></div>
  <div v-if="errors.description" v-text="errors.description"
       class="bg-red-600 p-2 w-full text-white font-semibold mt-1 mb-6"></div>
</template>
<script setup>

const props = defineProps({
  errors: Object,
})
</script>