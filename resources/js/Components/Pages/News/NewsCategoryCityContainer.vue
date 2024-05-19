<template>
<div>
  <div class="py-4 px-6 mb-6 bg-white shadow rounded-lg">
    <div class="mb-2">
      <div class="font-semibold text-xs uppercase text-gray-700">Category</div>
      <p class="text-gray-900 font-semibold">{{ newsStore.category?.name }}</p>
    </div>
    <div class="mb-2">
      <div class="font-semibold text-xs uppercase text-gray-700">Subcategory</div>
      <p class="text-gray-900 font-semibold">{{ newsStore.subCategory?.name }}</p>
    </div>
    <div v-if="location" class="mb-2">
      <div class="font-semibold text-xs uppercase text-gray-700">Location</div>
      <p class="text-gray-900 font-semibold">{{ location }}</p>
    </div>
    <button
        @click="newsStore.toggleCategoryCitySelector"
        :class="['btn mt-2', newsStore.showCategoryCitySelector ? 'btn-secondary' : 'btn-primary']">
      {{ newsStore.showCategoryCitySelector ? 'Done' : 'Change Category / City' }}
    </button>

    <CategoryCitySelector
        v-if="newsStore.showCategoryCitySelector"
        :searchPath="`/newsStory/${newsStore.slug}/edit`"
    />
  </div>



<!--  <CategoryCitySelector-->
<!--      v-if="newsStore.showCategoryCitySelector"-->
<!--      :newsStory="newsStory"-->
<!--      :citySearch="citySearch"-->
<!--      :filters="filters"-->
<!--      :searchPath="`/newsStory/${newsStory.slug}/edit`"-->
<!--  />-->
</div>
</template>
<script setup>
import { useNewsStore } from '@/Stores/NewsStore'
import { computed, defineAsyncComponent, watch } from 'vue'
const CategoryCitySelector = defineAsyncComponent({
  loader: () => import('@/Components/Pages/News/CategoryCitySelector.vue'),
  loadingComponent: { template: '<p>Loading...</p>' },
  errorComponent: { template: '<p>Error loading component</p>' },
})

const newsStore = useNewsStore()

watch(() => [newsStore.news_category_id, newsStore.news_category_sub_id], () => {
  newsStore.setSelectedCategory()
})

// Computed property for location display
const location = computed(() => {
  if (newsStore.city?.name) {
    return newsStore.province?.name
        ? `${newsStore.city.name}, ${newsStore.province.name}`
        : newsStore.city.name
  }
  if (newsStore.province?.name) {
    return newsStore.province.name
  }
  if (newsStore.federalElectoralDistrict?.name) {
    return newsStore.federalElectoralDistrict.name
  }
  if (newsStore.subnationalElectoralDistrict?.name) {
    return newsStore.subnationalElectoralDistrict.name
  }
  return null
})

</script>