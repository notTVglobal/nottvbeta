<template>
  <Head :title="`News RSS Feeds: ${feed.name}`"/>


  <div id="topDiv" class="place-self-center flex flex-col gap-y-3">
    <div class="bg-white dark:bg-gray-800 text-black dark:text-gray-50 p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <NewsHeader :can="can">News</NewsHeader>

      <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-900 border-b border-gray-200">


          <div class="flex flex-wrap justify-between items-center mb-1">
            <div class="text-2xl">{{ feed.name }}</div>
            <div class="flex flex-row flex-wrap gap-2">
              <div>
                <button
                    v-if="userStore.isNewsPerson"
                    @click="appSettingStore.btnRedirect(`/newsRssFeeds/${feed.slug}/edit`)"
                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg">
                  Edit
                </button>
              </div>
              <div
                  v-if="appSettingStore.prevUrl === '/newsRssFeeds' || appSettingStore.prevUrl === 'newsRssFeeds.index'">
                <BackButton :url="'/newsRssFeeds'"/>
              </div>
            </div>
          </div>
          <span class="text-sm font-semibold text-indigo-700 mb-4">Last updated ... {{
              userStore.formatDateTimeFromUtcToUserTimezone(feed.lastSuccessfulUpdate)
            }} </span>

          <div class="flex justify-center my-4">
            <div class="relative w-full max-w-md">
              <input
                  v-model="search"
                  type="search"
                  class="bg-gray-50 text-black text-sm rounded-full focus:outline-none focus:shadow w-full pl-10 py-1"
                  placeholder="Search...">
              <div class="absolute top-0 left-0 flex items-center h-full ml-3">
                <svg class="fill-current text-gray-400 w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 512 512">
                  <path
                      d="M456.69 421.39 362.6 327.3a173.81 173.81 0 0 0 34.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 0 0 327.3 362.6l94.09 94.09a25 25 0 0 0 35.3-35.3ZM97.92 222.72a124.8 124.8 0 1 1 124.8 124.8 124.95 124.95 0 0 1-124.8-124.8Z"/>
                </svg>
              </div>
            </div>
          </div>


          <!--          <div class="flex justify-between">-->
          <!--            <div class="text-2xl">{{ feed.name }}<span class="ml-4 text-sm font-semibold text-indigo-700">Last updated ... {{formatDateTime(feed.lastSuccessfulUpdate)}} GMT 0</span></div>-->
          <!--            <div>-->
          <!--              <div class="flex items-center mt-6 xl:mt-0">-->
          <!--                <div class="relative">-->
          <!--                  <input v-model="search" type="search" class="bg-gray-50 text-black text-sm rounded-full-->
          <!--                            focus:outline-none focus:shadow w-64 pl-8 px-3 py-1" placeholder="Search...">-->
          <!--                  <div class="absolute top-0 flex items-center h-full ml-2">-->
          <!--                    <svg class="fill-current text-gray-400 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">-->
          <!--                      <path-->
          <!--                          d="M456.69 421.39 362.6 327.3a173.81 173.81 0 0 0 34.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 0 0 327.3 362.6l94.09 94.09a25 25 0 0 0 35.3-35.3ZM97.92 222.72a124.8 124.8 0 1 1 124.8 124.8 124.95 124.95 0 0 1-124.8-124.8Z"/>-->
          <!--                    </svg>-->

          <!--                  </div>-->
          <!--                </div>-->
          <!--              </div>-->
          <!--            </div>-->
          <!--            <div class="flex justify-end space-x-2">-->
          <!--              <button-->
          <!--                  v-if="userStore.isNewsPerson"-->
          <!--                  @click="appSettingStore.btnRedirect(`/newsRssFeeds/${feed.slug}/edit`)"-->
          <!--                  class="px-3 py-1 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"-->
          <!--              >Edit-->
          <!--              </button>-->
          <!--              <div v-if="appSettingStore.prevUrl = '/newsRssFeeds' || 'newsRssFeeds.index'">-->
          <!--                <BackButton :url="'/newsRssFeeds'"/>-->
          <!--              </div>-->

          <!--            </div>-->
          <!--          </div>-->


          <Pagination :data="feed.items"/>

          <div class="relative overflow-x-auto shadow-md sm:rounded-lg mx-auto">
            <div class="py-4 px-3 space-y-3 max-w-4xl mx-auto">
              <div v-for="item in feed.items.data" class="bg-gray-600 text-white p-5 rounded-xl">
                <div class="flex flex-row justify-between items-start mb-2">
                  <div class="text-xl font-semibold pr-10"><a :href="item.url" target="_blank">{{ item.title }}</a>
                  </div>
                  <div>
                    <div v-if="item.is_saved" class="text-green-500 italic font-semibold uppercase text-sm">Archived
                    </div>
                    <div v-else>
                      <button @click="addToArchive(item.id)"
                              class="bg-green-500 text-white rounded-lg px-4 py-2 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                        Add To Archive
                      </button>
                    </div>
                  </div>
                </div>
                <div class="text-xs mb-2">{{ newFormatDate(item.pubDate) }}</div>
                <div v-html="item.description" class="mb-2"></div>
                <div class="flex justify-center"><a :href="item.url" target="_blank"><img :src="item.image_url"
                                                                                          class="max-w-full h-auto"></a>
                </div>
              </div>
              <div class="py-8">
                <Pagination :data="feed.items"/>
              </div>
            </div>
          </div>


        </div>
      </div>


    </div>
  </div>
</template>

<script setup>
import dayjs from 'dayjs'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import NewsHeader from '@/Components/Pages/News/NewsHeader'
import Message from '@/Components/Global/Modals/Messages'
import BackButton from '@/Components/Global/Buttons/BackButton'
import { ref, watch } from 'vue'
import throttle from 'lodash/throttle'
import { Inertia } from '@inertiajs/inertia'
import Pagination from '@/Components/Global/Paginators/Pagination.vue'
import { useForm } from '@inertiajs/inertia-vue3'

usePageSetup('newsFeed')

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()

let props = defineProps({
  feed: Object,
  can: Object,
  filters: Object,
})

const form = useForm({
  id: Number,
  is_saved: Boolean,
  news_rss_feed_id: props.feed.id,
})

let search = ref(props.filters.search)

watch(search, throttle(function (value) {
  Inertia.get(`/newsRssFeeds/${props.feed.slug}`, {search: value}, {
    preserveState: true,
    replace: true,
  })
}, 300))

const addToArchive = async (itemId) => {
  Inertia.patch(`/newsRssFeedItemsTemp/${itemId}/save`, {
  }, {
    preserveState: true, // Prevents the page from fully reloading
    preserveScroll: true, // Keeps the scroll position
    onSuccess: (page) => {
      console.log('Item archived successfully')
      // Here, you can optionally refresh data or handle UI updates
    },
    onError: (errors) => {
      console.error('Error archiving item:', errors)
    },
  })
};

function newFormatDate(dateString) {
  const date = dayjs(dateString)
  return date.format('dddd MMMM D, YYYY')
}

</script>
