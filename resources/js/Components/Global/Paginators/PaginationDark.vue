<template>
  <div v-if="data.last_page > 1" class="flex flex-wrap justify-center my-4 space-x-4 space-y-2">
    <div></div>
    <Component
        :is="link.url ? 'Link' : 'span'"

        v-for="(link, key) in data.links"
        :key="key"
        class="px-4 py-3 text-sm leading-4"
        :class="{
        'bg-orange-400 text-white hover:bg-orange-400': link.active,
        'rounded mt-2 mr-0.5 dark:bg-gray-900 dark:hover:bg-gray-900 dark:text-gray-700 text-gray-700': !link.url,
        'rounded bg-gray-800 hover:bg-gray-600 focus:text-indigo-500 hover:shadow': link.url
      }"
        :href="link.url"
        v-html="link.label"
    />
  </div>
</template>


<script setup>

defineProps({
  data: Object,
})
</script>
<!--class="px-1 text-gray-300 hover:text-blue-300"-->
<!--:class="{ 'text-gray-300 hover:text-gray-300': ! link.url, 'font-bold' : link.active }"-->
<!--text-gray-300 hover:text-blue-300-->
<!--text-gray-300 hover:text-gray-300-->
