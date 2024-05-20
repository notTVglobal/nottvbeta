<template>
  <div class="w-full flex flex-wrap">
    <div class="hidden sm:table-cell min-w-[8rem] max-w-fit px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap align-middle border-b-2">
      <button @click="appSettingStore.btnRedirect(`/news/story/${newsStory.slug}`)">
        <SingleImage :image="newsStory.image" alt="news cover" class="rounded-full h-20 w-20 object-cover" />
      </button>
    </div>
    <div class="table-cell px-6 py-4 font-medium text-gray-900 dark:text-gray-50 whitespace-nowrap align-middle flex-1">
      <button @click="appSettingStore.btnRedirect(`/news/story/${newsStory.slug}`)" class="text-lg font-semibold text-blue-800 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-200">
        {{ newsStory.title }}
      </button>
      <div>By {{ newsStory.newsPerson && newsStory.newsPerson.name ? newsStory.newsPerson.name : '' }}</div>

      <div class="flex flex-col pt-2 text-sm">
        <div v-if="newsStory.category?.id" class="font-medium text-orange-800">
          {{ newsStory.category.name }}
          <span v-if="newsStory.subCategory?.id"><span class="text-black"> | </span>{{ newsStory.subCategory.name }}</span>
        </div>

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

        <NewsStoryItemDates :newsStory="newsStory" class="block 2xl:hidden mt-2" />
        <NewsStoryActionButtons :newsStory="newsStory" :can="can" class="block xl:hidden mt-4" />
      </div>

      <div>
        <button
            v-if="newsStory.can.publishNewsStory && !newsStory.published_at && newsStory.status.id === 3"
            @click="openConfirmPublishDialog(newsStory)"
            class="bg-green-600 hover:bg-green-500 text-white mt-1 mx-2 px-4 py-2 h-fit rounded disabled:bg-gray-400"
        >
          Publish
        </button>
      </div>
    </div>
    <div class="hidden 2xl:flex items-center px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap align-middle">
      <NewsStoryItemDates :newsStory="newsStory" />
    </div>
    <div class="hidden xl:flex items-center px-6 py-4 align-right justify-end space-x-2">
      <NewsStoryActionButtons :newsStory="newsStory" :can="can" />
    </div>
  </div>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'
import { computed, ref } from 'vue'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import NewsStoryItemDates from '@/Components/Pages/Newsroom/Elements/NewsStoryItemDates.vue'
import NewsStoryActionButtons from '@/Components/Pages/Newsroom/Elements/NewsStoryActionButtons.vue'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'


const appSettingStore = useAppSettingStore()

const props = defineProps({
  newsStory: Object,
  can: Object,
})

const selectedNewsStory = ref(null)

const openConfirmPublishDialog = (newsStory) => {
  selectedNewsStory.value = newsStory
  appSettingStore.showConfirmationDialog = true
}

function publish() {
  Inertia.patch(route('newsroom.publish', {newsStory: selectedNewsStory.value.slug}))
  const topDiv = document.getElementById("topDiv")
  topDiv.scrollIntoView()
}

// Computed property for location display
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