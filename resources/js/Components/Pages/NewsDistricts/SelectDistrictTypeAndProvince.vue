<template>
  <div>
    <!-- Toggle Button for District Type -->
    <div class="toggle-buttons mb-2">
      <button @click="setDistrictType('federal')" :class="{active: districtType === 'federal'}">Federal</button>
      <button @click="setDistrictType('subnational')" :class="{active: districtType === 'subnational'}">
        Subnational
      </button>
    </div>
    <div class="districts-navigation mb-2">
      <!-- Sidebar/Dropdown for Filter Selection (Responsive) -->
      <div class="districts-filters">
        <select v-model="selectedProvince">
          <option disabled value="">Select a Province</option>
          <option v-for="province in newsDistrictStore.provinces" :key="province.id" :value="province.id">{{ province.name }}</option>
        </select>
        <!-- Additional filters can be added here -->
        <button @click="clearSelectedProvinceId" class="ml-2 text-blue-500 hover:text-blue-700">clear selection</button>
      </div>

    </div>

  </div>
</template>

<script setup>
import { computed, onBeforeMount, onMounted, ref, watch } from 'vue'
import { useNewsDistrictStore } from '@/Stores/NewsDistrictStore'

const newsDistrictStore = useNewsDistrictStore()

defineProps({
  can: Object,
})

// Accessing state as a computed property
const districtType = computed(() => newsDistrictStore.districtType)
const selectedProvince = ref('');

// Using an action to update state
function setDistrictType(type) {
  newsDistrictStore.setDistrictType(type)
}

// Using an action to update state
function clearSelectedProvinceId() {
  selectedProvince.value =   '';
}

// Update the selectedProvinceId in the store whenever the selected province changes
watch(selectedProvince, (newValue) => {
  newsDistrictStore.selectedProvinceId = newValue;
});

watch(() => newsDistrictStore.selectedProvinceId, (newValue) => {
  // React to changes in the global state, possibly to update local UI elements
  selectedProvince.value = newValue
});

onBeforeMount(() => {
  newsDistrictStore.clearSelectedProvinceId()
})

onMounted(() => {
  selectedProvince.value = newsDistrictStore.selectedProvinceId || '';
})

</script>
<style scoped>
.toggle-buttons button {
  margin-right: 10px;
  padding: 10px;
  border: none;
  background-color: #efefef;
  cursor: pointer;
}

.toggle-buttons button.active {
  background-color: #c8e6c9;
}
</style>