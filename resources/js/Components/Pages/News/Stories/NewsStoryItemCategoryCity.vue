<template>
  <div v-if="story.category.id">
      <span class="block font-semibold uppercase text-gray-700" v-if="story.city.id">
        {{ story.city.name }}<br><span class="uppercase font-thin text-gray-500">{{ story.province.name }}</span>
      </span>
    <div v-if="story.province.id && !story.city.id && !story.federalElectoralDistrict.id && !story.subnationalElectoralDistrict.id">
      <span class="block uppercase font-semibold text-gray-700">{{ story.province.name }}</span>
      <span class="block text-sm uppercase font-thin text-gray-500">Province</span>
    </div>
    <div v-if="story.federalElectoralDistrict.id">
      <span class="block uppercase font-semibold text-gray-700">{{ story.federalElectoralDistrict.name }}</span>
      <span class="block text-sm uppercase font-thin text-gray-500">Federal Electoral District</span>
    </div>
    <div v-if="story.subnationalElectoralDistrict.id">
      <span class="block uppercase font-semibold text-gray-700">{{ story.subnationalElectoralDistrict.name }}</span>
      <span class="block text-sm uppercase font-thin text-gray-500">Subnational Electoral District</span>
    </div>
    <span class="block text-lg font-semibold text-orange-800 mt-2">{{ story.category.name }}</span>
    <span class="block text-wrap text-gray-600" v-if="story.subCategory.id">{{ story.subCategory.name }}</span>
  </div>
</template>
<script setup>

const props = defineProps({
  story: Object
})
</script>