<template>

  <div class="flex flex-row flex-wrap">
    <div
        v-for="creator in creators.data"
        :key="creator.id"
        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
    >
      <div
          class="min-w-[8rem] px-6 py-4 font-medium text-gray-900 dark:text-white"
      >
        <!--                                                    <img :src="`/storage/images/${show.poster}`" class="rounded-full h-20 w-20 object-cover">-->
        <Link :href="`/creators/${creator.slug}`" class="text-blue-800 hover:text-blue-600">
          <img :src="'/storage/images/' + creator.avatar" class="rounded-full h-32 w-32 object-cover"></Link>
      </div>
      <div
          class="px-6 py-4 font-medium text-gray-900 dark:text-white break-words grow-0 w-32"
      >
        <Link :href="`/creators/${creator.slug}`" class="text-blue-800 hover:text-blue-600">{{ creator.name }}</Link>
      </div>
    </div>
  </div>

</template>

<script setup>

defineProps({
  creators: Object,
})

</script>
