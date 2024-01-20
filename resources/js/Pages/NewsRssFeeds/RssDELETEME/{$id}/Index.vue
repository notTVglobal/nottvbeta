<template>
  <Head :title="`News RSS Feeds: ${props.feed.name}`"/>


  <div id="topDiv" class="place-self-center flex flex-col gap-y-3">
    <div class="bg-white dark:bg-gray-800 text-black dark:text-gray-50 p-5 mb-10">
      <header class="flex justify-between mb-3 border-b border-gray-500">
        <NewsHeader>News</NewsHeader>
      </header>

      <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-900 border-b border-gray-200">
          <div class="flex justify-between">
            <div class="text-2xl">{{ props.feed.name }}</div>
            <div>
              <button
                  @click="back"
                  class="px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"
              >Back
              </button>
            </div>
          </div>
          <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

            <div class="py-4 px-3 space-y-3">
              <div v-for="item in props.feed.items.item">
                <div class="bg-gray-600 p-5 rounded-xl">
                  <div class="text-xl font-semibold"><a :href="item.link" target="_blank">{{ item.title }}</a></div>
                  <div class="text-xs">{{ newFormatDate(item.pubDate) }}</div>
                  <div v-html="item.description"></div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { Inertia } from "@inertiajs/inertia"
import { usePage } from "@inertiajs/inertia-vue3"
import { formatDate } from "@vueuse/shared"
import dayjs from "dayjs"
// import {parseMasterXml} from "@videojs/http-streaming/src/dash-playlist-loader"
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import NewsHeader from "@/Components/Pages/News/NewsHeader"
import Message from "@/Components/Global/Modals/Messages"

usePageSetup('newsRssIndex')

const appSettingStore = useAppSettingStore()

let props = defineProps({
  feed: Object,
  can: Object,
})

function newFormatDate(dateString) {
  const date = dayjs(dateString)
  return date.format('dddd MMMM D, YYYY')
}

function back() {
  let urlPrev = usePage().props.value.urlPrev
  if (urlPrev !== 'empty') {
    Inertia.visit(urlPrev)
  }
}

</script>
