<template>
  <div class="pt-8 max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold mb-6 text-center">Cities</h1>

    <!-- Search Box -->
    <div class="flex mb-6 justify-center">
      <input
          v-model="searchQuery"
          type="text"
          placeholder="Search for a city..."
          class="w-full max-w-lg p-3 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
      />
    </div>

    <!-- Cities List -->
    <div v-if="filteredProvinces.length" class="flex flex-wrap gap-4 justify-center xl:flex-row">
      <div
          v-for="province in filteredProvinces"
          :key="province.id"
          class="mb-4 w-full xl:w-1/4 min-w-[200px] p-2 bg-gray-100 rounded-lg shadow-md"
      >
        <div @click="toggleProvince(province.id)" class="cursor-pointer bg-blue-600 text-white p-3 rounded-md">
          <h2 class="text-xl font-semibold">{{ province.name }}</h2>
        </div>
        <transition name="fade">
          <div v-if="province.isOpen" class="pt-2">
            <ul class="pl-6 overflow-hidden max-h-48 overflow-y-auto">
              <li v-for="city in province.cities.slice(0, 5)" :key="city.id" class="mb-2">
                <Link :href="`/news/city/${city.slug}`" class="text-blue-600 hover:underline">
                  {{ city.name }}
                </Link>
              </li>
            </ul>
            <button v-if="province.cities.length > 5" @click="toggleExpand(province.id)" class="text-sm text-blue-500 hover:underline mt-2 ml-6">
              {{ province.isExpanded ? 'Show Less' : 'Show More' }}
            </button>
            <transition name="fade">
              <ul v-if="province.isExpanded" class="pl-6 overflow-hidden max-h-48 overflow-y-auto">
                <li v-for="city in province.cities.slice(5)" :key="city.id" class="mb-2">
                  <Link :href="`/news/city/${city.slug}`" class="text-blue-600 hover:underline">
                    {{ city.name }}
                  </Link>
                </li>
              </ul>
            </transition>
          </div>
        </transition>
      </div>
    </div>
    <div v-else>
      <p>Loading cities...</p>
    </div>
  </div>
</template>


<script setup>
import { ref, computed, watch } from 'vue'
import { defineProps } from 'vue'

const props = defineProps({
  newsProvinces: {
    type: Array,
    default: () => [],
  },
})

// Initialize provincesWithState only if newsProvinces is defined and not empty
const provincesWithState = ref([])

watch(() => props.newsProvinces, (newVal) => {
  if (newVal && newVal.length > 0) {
    provincesWithState.value = newVal.map(province => ({
      ...province,
      cities: province.cities || [],
      isOpen: false,
      isExpanded: false,
    }))
  }
}, { immediate: true })

const searchQuery = ref('')

// Filter provinces based on search query
const filteredProvinces = computed(() => {
  if (!searchQuery.value) {
    return provincesWithState.value
  }

  return provincesWithState.value.map(province => {
    const filteredCities = province.cities.filter(city =>
        city.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
    return {
      ...province,
      cities: filteredCities,
      isOpen: filteredCities.length > 0 // Open province if it has matching cities
    }
  }).filter(province => province.cities.length > 0)
})

// Toggle province section
const toggleProvince = id => {
  provincesWithState.value.forEach(province => {
    if (province.id === id) {
      province.isOpen = !province.isOpen
    } else {
      province.isOpen = false
    }
  })
}

// Toggle expand/collapse for cities
const toggleExpand = id => {
  const province = provincesWithState.value.find(province => province.id === id)
  if (province) {
    province.isExpanded = !province.isExpanded
  }
}
</script>


<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
  opacity: 0;
}

.min-w-[200px] {
  min-width: 200px;
}
</style>

