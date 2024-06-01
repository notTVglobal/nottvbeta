<template>
  <tr class="hover:bg-gray-100 transition duration-150 ease-in-out cursor-pointer">
    <td v-if="story.category.id" class="px-6 py-4 whitespace-normal align-top">
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
    </td>
    <td class="px-6 py-4 whitespace-normal align-top" v-if="story.title">
      <div class="flex items-center space-x-4">
        <button @click="btnRedirect(`/news/story/${story.slug}`)" class="text-blue-500 hover:text-blue-700">
          <SingleImage :image="story.image" alt="News Story Image" class="hidden md:block h-20 w-20 rounded-full"/>
        </button>
        <div class="flex flex-col">
          <button @click="btnRedirect(`/news/story/${story.slug}`)" class="text-left text-xl font-semibold text-blue-500 hover:text-blue-700 uppercase">
            {{ story.title }}
          </button>
          <div class="text-gray-600 text-sm">
            <span class="uppercase font-semibold">By</span> {{ story.newsPerson.name ? story.newsPerson.name : 'Unknown' }}
          </div>
        </div>
      </div>
    </td>
    <td class="h-full pb-4 align-bottom pr-6 text-right">
      <div class="flex justify-end mt-4">
        <div v-if="story.status === 'Creators Only'" class="text-gray-700 italic">
          {{ story.status }}
        </div>
        <div v-if="story.published_at" class="flex flex-col justify-end">
          <div class="text-xs uppercase font-semibold text-gray-500">Published</div>
          <div class="text-gray-600">
            <ConvertDateTimeToTimeAgo :dateTime="story.published_at" :timezone="timezone"/>
          </div>
          <div class="text-gray-500">{{ formatDateTimeWithYearFromUtcToUserTimezone(story.published_at) }}</div>
        </div>
      </div>
    </td>
  </tr>
</template>

<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'
import ConvertDateTimeToTimeAgo from '@/Components/Global/DateTime/ConvertDateTimeToTimeAgo.vue'
import { useUserStore } from '@/Stores/UserStore'

const props = defineProps({
  story: Object
})

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
const timezone = userStore.timezone

const btnRedirect = (url) => {
  appSettingStore.btnRedirect(url)
}

const formatDateTimeWithYearFromUtcToUserTimezone = (dateTime) => {
  return userStore.formatDateTimeWithYearFromUtcToUserTimezone(dateTime)
}
</script>

<style scoped>
.hover\:bg-gray-100:hover {
  background-color: #f7fafc; /* Light gray background on hover */
}
</style>
