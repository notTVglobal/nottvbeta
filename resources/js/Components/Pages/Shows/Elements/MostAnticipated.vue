<template>
  <div class="most-anticipated xl:w-1/4 mt-12 xl:mt-0">
    <h2 class="text-purple-500 uppercase tracking-wide font-semibold text-2xl">Most Anticipated</h2>
    <div class="most-anticipated-container space-y-10 mt-8">
      <div v-for="show in mostAnticipated.data"
           :key="show.id"
           class="show flex">
        <Link :href="`/shows/${show.slug}`">
          <SingleImage :image="show.image" :alt="'show cover'"
                       class="h-24 min-w-[4rem] w-16 object-cover hover:opacity-75 transition ease-in-out duration-150"/>
        </Link>
        <div class="ml-4">
          <Link :href="`/shows/${show.slug}`" class="hover:text-gray-300">{{ show.name }}</Link>
          <div class="text-gray-400 text-sm mt-1">{{ show.categoryName }}</div>
          <div class="text-gray-400 text-sm font-thin mt-1">{{ show.categorySubName }}</div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import SingleImage from "@/Components/Global/Multimedia/SingleImage"

defineProps({
  mostAnticipated: Object,
})
</script>
