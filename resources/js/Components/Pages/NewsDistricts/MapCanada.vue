<template>
  <div>
    <MapCanadaSvg @provinceSelected="handleProvinceSelection" />
  </div>
</template>

<script setup>
import { nextTick, onMounted, ref } from 'vue'
import { useNewsDistrictStore } from '@/Stores/NewsDistrictStore'
import MapCanadaSvg from '@/Components/Global/Maps/Canada/MapCanadaSvg.vue'

const newsDistrictStore = useNewsDistrictStore()

document.querySelectorAll('#canada-map path').forEach(province => {
  province.addEventListener('click', (e) => {
    const provinceId = e.target.id;
    // Logic to replace the map with the detailed province map
    console.log(`${provinceId} was clicked`);
    // This could involve dynamically loading another SVG,
    // or updating the current SVG's visibility and details.
  });
});


const map = ref(null);

onMounted(() => {
  nextTick(() => {
    // Assuming `map` is your LMap component reference

  });
});

function handleProvinceSelection(abbreviation) {
  console.log('Selected Province ID:', abbreviation);
  // Additional logic to handle the province selection
  newsDistrictStore.setSelectedProvinceId(abbreviation);
}
</script>

<style>

</style>
