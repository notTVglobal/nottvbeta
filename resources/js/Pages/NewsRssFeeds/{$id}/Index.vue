<template>
  <Head :title="`News RSS Feeds: ${feed.name}`"/>


  <div id="topDiv" class="place-self-center flex flex-col gap-y-3">
    <div class="bg-white dark:bg-gray-800 text-black dark:text-gray-50 p-5 mb-10">

      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

      <NewsHeader :can="can">News</NewsHeader>

      <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-900 border-b border-gray-200">
          <div class="flex justify-between">
            <div class="text-2xl">{{ feed.name }}<span class="ml-4 text-sm font-semibold text-indigo-700">Last updated ... {{formatDateTime(feed.lastSuccessfulUpdate)}} GMT 0</span></div>
            <div>
              <div class="flex items-center mt-6 xl:mt-0">
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
            </div>
            <div class="flex justify-end space-x-2">
              <button
                  v-if="userStore.isNewsPerson"
                  @click="appSettingStore.btnRedirect(`/newsRssFeeds/${feed.slug}/edit`)"
                  class="px-3 py-1 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
              >Edit
              </button>
              <div v-if="appSettingStore.prevUrl = '/newsRssFeeds' || 'newsRssFeeds.index'">
                <BackButton :url="'/newsRssFeeds'"/>
              </div>

            </div>
          </div>

          <Pagination :data="feed.items" />

          <div class="relative overflow-x-auto shadow-md sm:rounded-lg mx-auto justify-center flex w-full">

            <div class="py-4 px-3 space-y-3">
              <div v-for="item in feed.items.data">
                <div class="bg-gray-600 text-white p-5 rounded-xl w-1/2">
                  <div class="flex flex-row mb-2">
                    <div class="w-full flex justify-start flex-wrap text-xl font-semibold pr-10"><a :href="item.url" target="_blank">{{ item.title }}</a></div>
                    <div class="w-fit flex flex-wrap justify-end">
                      <div v-if="item.is_saved" class="text-success italic font-semibold uppercase text-sm mb-2">Archived</div>
                      <div v-else class=""><button @click="addToArchive(item.id)" class="btn btn-md" :class="{'btn-success':!item.is_saved}">Add To Archive</button></div>
                    </div>
                  </div>
                  <div class="text-xs mb-2">{{ newFormatDate(item.pubDate) }}</div>
                  <div v-html="item.description" class="mb-2"></div>
                  <div class="w-full flex flex-row justify-center mx-auto"><a :href="item.url" target="_blank"><img :src="item.image_url" class="w-fit"></a></div>
                </div>

              </div>
              <div class="py-8">
                <Pagination :data="feed.items" />
              </div>
            </div>
          </div>


        </div>
      </div>



    </div>
  </div>
</template>

<script setup>
import dayjs from "dayjs"
// import {parseMasterXml} from "@videojs/http-streaming/src/dash-playlist-loader"
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useUserStore } from "@/Stores/UserStore"
import NewsHeader from "@/Components/Pages/News/NewsHeader"
import Message from "@/Components/Global/Modals/Messages"
import BackButton from "@/Components/Global/Buttons/BackButton"
import { formatDate } from '@vueuse/shared'
import { useTimeAgo } from '@vueuse/core'
import { ref, watch } from 'vue'
import throttle from 'lodash/throttle'
import { Inertia } from '@inertiajs/inertia'
import Pagination from '@/Components/Global/Paginators/Pagination.vue'
import { useForm } from '@inertiajs/inertia-vue3'
// import { format } from 'date-fns'
// import { utcToZonedTime } from 'date-fns-tz'

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
  news_rss_feed_id: props.feed.id
})

let search = ref(props.filters.search)

watch(search, throttle(function (value) {
  Inertia.get(`/newsRssFeeds/${props.feed.slug}`, {search: value}, {
    preserveState: true,
    replace: true,
  })
}, 300))

const addToArchive = async (itemId) => {

  console.log(itemId)
  form.is_saved = true;
  form.id = itemId;

  await form.patch(`/newsRssFeedItemsTemp/${itemId}`)
};


// let lastUpdatedTime = props.lastUpdateTime

function newFormatDate(dateString) {
  const date = dayjs(dateString)
  return date.format('dddd MMMM D, YYYY')
}


// tec21: I was trying to do a TimeAgo format or this "Last Updated..."
// but we need a reusable component that converts the serverTime to the user time.
// then take your pick as to how you want to display this on this page...
// Last updated TimeAgo or the function below...
function formatLastUpdatedDate(dateString) {
  const date = new Date(dateString);
  const options = {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: 'numeric',
    minute: '2-digit',
    hour12: true
  };
  // return `Last updated on ${new Intl.DateTimeFormat('en-US', options).format(date)}`;
  return `Last updated on ${useTimeAgo(props.feed.lastSuccessfulUpdate)}`;
}

</script>
