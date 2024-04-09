<template>

  <Head title="News RSS Feeds"/>
  <!--    <div class="sticky top-0 w-full nav-mask">-->
  <!--        <ResponsiveNavigationMenu/>-->
  <!--        <NavigationMenu />-->
  <!--    </div>-->

  <!--    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">-->
  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white dark:bg-gray-800 text-black dark:text-gray-50 mb-10">
      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>
      <NewsHeader :can="can">Newsroom</NewsHeader>

      <div class="w-full overflow-hidden bg-white shadow-sm sm:rounded-lg p-5">
        <div class="w-full p-6 bg-white dark:bg-gray-900 border-b border-gray-200">

          <div class="w-full relative overflow-x-auto shadow-md sm:rounded-lg">
            <table
                class="table-auto w-full text-sm text-left text-gray-500 dark:text-gray-400"
            >
              <thead
                  class="flex flex-justify text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
              <tr class="w-full">
                <th scope="col"
                    class="w-full flex flex-row justify-between px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                  <div>
                    <span class="text-xl md:text-2xl font-medium">News RSS Feeds</span>
                    <button
                        v-if="can.manageFeeds"
                        @click="appSettingStore.btnRedirect(`newsRssFeeds`)"
                        class="bg-blue-600 hover:bg-blue-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
                    >List Feeds
                    </button>
                  </div>
                  <div class="flex items-center mt-6 lg:mt-0">
                    <div class="relative">
                      <input v-model="search" type="search" class="bg-gray-50 text-black text-sm rounded-full
                            focus:outline-none focus:shadow w-64 pl-8 px-3 py-1" placeholder="Search...">
                      <div class="absolute top-0 flex items-center h-full ml-2">
                        <svg class="fill-current text-gray-400 w-4" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 512 512">
                          <path
                              d="M456.69 421.39 362.6 327.3a173.81 173.81 0 0 0 34.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 0 0 327.3 362.6l94.09 94.09a25 25 0 0 0 35.3-35.3ZM97.92 222.72a124.8 124.8 0 1 1 124.8 124.8 124.95 124.95 0 0 1-124.8-124.8Z"/>
                        </svg>

                      </div>
                    </div>
                  </div>
                </th>
                <th scope="col" class="">
                  <!-- Paginator -->
                </th>

              </tr>
              </thead>
              <Pagination :data="feeds" class="mt-6"/>
              <tbody>
              <tr
                  v-for="feed in feeds.data"
                  :key="feed.id"
                  class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
              >
                <td class="px-6 py-10 text-gray-900 dark:text-white">
                  <div class="flex flex-col items-center w-full"> <!-- Ensures full width for centering -->

                    <div class="w-full text-4xl font-semibold mb-2">
                      <a :href="feed.url" target="_blank">{{ feed.title }}</a>
                    </div>
                    <div class="text-left w-full mb-2">{{ formatDate(feed.pubDate) }}</div>
                    <div v-html="feed.description" class="text-xl mb-6"></div>
                    <a :href="feed.url" target="_blank" class="flex justify-center w-full">
                      <!-- Flex container for centering -->
                      <img v-if="!feed.image" :src="feed.image_url" alt="" class="max-w-full h-auto">
                      <SingleImage v-else :image="feed.image.data"/>
                    </a>
                    <div class="w-full flex flex-wrap justify-between mt-2 gap-y-2">
                      <div>
                        <p v-if="feed.feedName" class="text-left w-full mt-2 tracking-wider hover:text-blue-300">
                          <Link :href="`/newsRssFeeds/${feed.feedSlug}`">
                            <span class="font-semibold">{{ feed.feedName }}</span>
                          </Link>
                        </p>
                      </div>
                      <div>
                        <div v-if="feed.is_saved" class="text-green-500 italic font-semibold uppercase text-sm">Archived
                        </div>
                        <div v-else>
                          <button @click="addToArchive(feed.id)"
                                  class="bg-green-500 text-white rounded-lg px-4 py-2 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                            Add To Archive
                          </button>
                        </div>
                      </div>

                    </div>

                  </div>
                </td>
              </tr>
              </tbody>

            </table>
            <!-- Paginator -->
            <Pagination :data="feeds" class="mt-6"/>
          </div>
        </div>
      </div>


    </div>

  </div>

</template>

<script setup>
import { onMounted, ref, watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { useForm } from '@inertiajs/inertia-vue3'
import throttle from 'lodash/throttle'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import NewsHeader from '@/Components/Pages/News/NewsHeader'
import Pagination from '@/Components/Global/Paginators/Pagination'
import Message from '@/Components/Global/Modals/Messages'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'

usePageSetup('newsRssFeeds.index')

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()

let props = defineProps({
  filters: Object,
  can: Object,
  feeds: Object,
})

let form = useForm({})

let search = ref(props.filters.search)

watch(search, throttle(function (value) {
  Inertia.get('/newsRssFeedItemsTemp', {search: value}, {
    preserveState: true,
    replace: true,
  })
}, 300))

function scrollToCities() {
  document.getElementById('cities').scrollIntoView({behavior: 'smooth'})
}


function destroy(slug) {
  if (confirm('Are you sure you want to Delete')) {
    form.delete(route('newsRssFeeds.destroy', slug))

  }
}

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

onMounted(() => {
  const appSettingStore = useAppSettingStore()
  appSettingStore.shouldScrollToTop = true
})

</script>
