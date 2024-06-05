<template>
<div>
  <div v-if="locationDisplay?.city && locationDisplay?.province">
    <div class="font-semibold">{{ locationDisplay.city }}, <span class="font-medium text-gray-800">{{ locationDisplay.province }}</span></div>
  </div>

  <div v-else-if="locationDisplay?.province && locationDisplay.type === 'Province'">
    <div class="font-semibold">{{ locationDisplay.province }} &nbsp;&nbsp;<span class="text-xs font-medium text-gray-500 uppercase">Province</span></div>
  </div>

  <div v-else-if="locationDisplay?.type === 'Federal Electoral District'">
    <div class="font-semibold">{{ locationDisplay.name }} &nbsp;&nbsp;<span class="text-xs font-medium text-gray-500 uppercase">Federal Electoral District</span></div>
  </div>

  <div v-else-if="locationDisplay?.type === 'Subnational Electoral District'">
    <div class="font-semibold">{{ locationDisplay.name }} &nbsp;&nbsp;<span class="text-xs font-medium text-gray-500 uppercase">Subnational Electoral District</span></div>
  </div>
</div>
</template>
<script setup>
// Computed property for location display
import { computed } from 'vue'

const props = defineProps({
  newsStory: Object,
})

const locationDisplay = computed(() => {
  const { city, province, federalElectoralDistrict, subnationalElectoralDistrict } = props.newsStory

  if (city?.id && province?.id) {
    return { city: city.name, province: province.name }
  }
  if (province?.id && !city?.id && !federalElectoralDistrict?.id && !subnationalElectoralDistrict?.id) {
    return { province: province.name, type: 'Province' }
  }
  if (federalElectoralDistrict?.id) {
    return { name: federalElectoralDistrict.name, type: 'Federal Electoral District' }
  }
  if (subnationalElectoralDistrict?.id) {
    return { name: subnationalElectoralDistrict.name, type: 'Subnational Electoral District' }
  }
  return null
})
</script>