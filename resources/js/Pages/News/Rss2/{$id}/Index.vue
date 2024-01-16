<template>
  <Head :title="`News RSS Feeds: ${props.feed.name}`"/>


  <div id="topDiv" class="place-self-center flex flex-col gap-y-3">
    <div class="bg-white dark:bg-gray-800 text-black dark:text-gray-50 p-5 mb-10">

      <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

      <NewsHeader :can="can">News</NewsHeader>

      <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-900 border-b border-gray-200">
          <div class="flex justify-between">
            <div class="text-2xl">{{ props.feed.name }}</div>
            <div>
              <BackButton/>
            </div>
          </div>
          <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

            <div class="py-4 px-3 space-y-3">
              <div v-for="item in props.feed.items.item">
                <div class="bg-gray-600 text-white p-5 rounded-xl">
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
import { onMounted, ref } from "vue"
import { formatDate } from "@vueuse/shared"
import dayjs from "dayjs"
// import {parseMasterXml} from "@videojs/http-streaming/src/dash-playlist-loader"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
const appSettingStore = useAppSettingStore()
import { useUserStore } from "@/Stores/UserStore"
import { useNewsStore } from "@/Stores/NewsStore"
import NewsHeader from "@/Components/Pages/News/NewsHeader"
import Message from "@/Components/Global/Modals/Messages"
import BackButton from "@/Components/Global/Buttons/BackButton"

const videoPlayerStore = useVideoPlayerStore()
const userStore = useUserStore()
const newsStore = useNewsStore()

let props = defineProps({
  feed: Object,
  can: Object,
})

userStore.currentPage = 'newsFeed'
userStore.showFlashMessage = true;

onMounted(() => {
  videoPlayerStore.makeVideoTopRight();
  if (userStore.isMobile) {

    appSettingStore.ott = 0
appSettingStore.pageIsHidden = false
  }
  document.getElementById("topDiv").scrollIntoView()
});

function newFormatDate(dateString) {
  const date = dayjs(dateString)
  return date.format('dddd MMMM D, YYYY')
}


</script>
