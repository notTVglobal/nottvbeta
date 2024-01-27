<template>
  <div class="w-full mx-auto p-4">

    <div class="flex w-full h-fit flex-wrap justify-between align-baseline space-x-10">
        <h2 class="text-center text-xl md:text-3xl font-semibold my-auto align-middle pl-6">News Stories</h2>
      <div class="my-auto">
        <div class="relative">
          <input v-model="search" type="search" class="bg-gray-50 text-black text-sm rounded-full
                            focus:outline-none focus:shadow w-64 pl-8 px-3 py-1" placeholder="Search...">
          <div class="absolute top-0 flex items-center h-full ml-2">
            <svg class="fill-current text-gray-400 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
              <path
                  d="M456.69 421.39 362.6 327.3a173.81 173.81 0 0 0 34.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 0 0 327.3 362.6l94.09 94.09a25 25 0 0 0 35.3-35.3ZM97.92 222.72a124.8 124.8 0 1 1 124.8 124.8 124.95 124.95 0 0 1-124.8-124.8Z"/>
            </svg>

          </div>
        </div>
      </div>
      <div class="my-auto pr-6">
        <button
            @click="appSettingStore.btnRedirect(``)"
            class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded disabled:bg-gray-400"
            disabled
        >Upload Press Release
        </button>
      </div>
    </div>

    <table class="w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
      <tr>
        <th v-if="newsStories.data.some(story => story.newsCategory)" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" @click="sort('category')">Location and Category</th>
        <th v-if="newsStories.data.some(story => story.title)" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" @click="sort('title')">Story</th>
        <th v-if="newsStories.data.some(story => story.status)" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" @click="sort('status')">Date Published</th>
      </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
      <tr v-for="story in newsStories.data" :key="story.id">
        <td v-if="story.newsCategory" class="px-6 py-4 whitespace-normal">
          <span class="block uppercase font-thin"
                v-if="story.city">
            {{ story.city }}<br>{{ story.province}}</span>
          <span class="block uppercase font-thin"
                v-if="story.province && !story.city && !story.federalElectoralDistrict && !story.subnationalElectoralDistrict">
            {{ story.province }}</span>
          <span class="block uppercase font-thin"
                v-if="story.federalElectoralDistrict">
            {{ story.federalElectoralDistrict }}</span>
          <span class="block uppercase font-thin"
                v-if="story.subnationalElectoralDistrict">
            {{ story.subnationalElectoralDistrict }}</span>
          <span class="px-4"></span>
          <span class="block text-lg font-semibold">
            {{ story.newsCategory }}</span>
          <span class="block text-wrap"
                v-if="story.newsCategorySub">
            {{ story.newsCategorySub }}</span>
        </td>
        <td class="px-6 py-4 whitespace-normal"
            v-if="story.title" >

            <div class="flex items-center space-x-2 align-middle">
              <Link :href="`/news/story/${story.slug}`" class="text-blue-500 hover:text-blue-700">
                <SingleImage :image="story.image" alt="News Story Image" class="hidden md:block min-h-20 min-w-20 max-h-20 max-w-20 mr-4 rounded-full" />
              </Link>
              <div class="flex flex-col">
                <div>
                  <Link :href="`/news/story/${story.slug}`" class="text-blue-500 hover:text-blue-700">
                    <span class="pt-2 text-xl uppercase font-semibold text-wrap">{{ story.title }}</span>
                  </Link>
                </div>
                <div>
                  <span class="uppercase text-xs font-semibold">By</span> {{ story.user.name }}
                </div>
              </div>
            </div>

          <div class="ml-4 mt-2">

          </div>
        </td>
        <td class="pr-3 text-right">
          <div class="flex justify-end mt-4">
            <div v-if="story.published_at" class="flex flex-col justify-end">
<!--              <span v-if="!story.published_at">{{ story.status }}</span><br>-->
              <div class="text-xs uppercase font-semibold">Published</div>
              <div>{{ formatDate(new Date(story.published_at).toLocaleDateString()) }}</div>
            </div>
          </div>
        </td>
      </tr>
      </tbody>
    </table>

    <div v-if="newsStories.data.length === 0" class="w-full flex flex-row justify-center text-sm italic my-24">
      No Results. Try again!
    </div>

    <div class="w-full flex justify-center">
      <Pagination :data="newsStories" class="" />
    </div>

  </div>
</template>



<script setup>
import { ref, watch } from 'vue'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'
import Pagination from '@/Components/Global/Paginators/Pagination.vue'
import throttle from 'lodash/throttle'
import { Inertia } from '@inertiajs/inertia'

const appSettingStore = useAppSettingStore()

const props = defineProps({
  newsStories: Object,
  filters: Object,
});

const sort = (field) => {
  // Add sorting logic here
};

let search = ref(props.filters.search)

watch(search, throttle(function (value) {
  Inertia.get('/news', {search: value}, {
    preserveState: true,
    replace: true,
  })
}, 300))
</script>

<style>
/* Add custom styles or utility classes from Tailwind CSS here */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
