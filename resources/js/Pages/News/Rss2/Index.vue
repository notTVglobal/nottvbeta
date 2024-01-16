<template>

  <Head title="News RSS Feeds"/>
  <!--    <div class="sticky top-0 w-full nav-mask">-->
  <!--        <ResponsiveNavigationMenu/>-->
  <!--        <NavigationMenu />-->
  <!--    </div>-->

  <!--    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">-->
  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white dark:bg-gray-800 text-black dark:text-gray-50 p-5 mb-10">
      <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>
      <NewsHeader :can="can">News Feeds</NewsHeader>

      <div class="bg-orange-500 mb-1 px-2 py-1 text-black font-semibold">TODO: create special parser for <a
          href="https://www.canada.ca/en/news/web-feeds.html#a7" target="_blank">Government of Canada Feeds</a></div>

      <div class="w-full overflow-hidden bg-white shadow-sm sm:rounded-lg">
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
                    <span class="text-lg">News Feeds</span>
                    <button
                        v-if="can.viewNewsroom"
                        @click="userStore.btnRedirect(`/rss2/create`)"
                        class="bg-green-600 hover:bg-green-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
                    >Add Feed
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

                </th>

              </tr>
              </thead>
              <tbody>
              <tr
                  v-for="feed in feeds.data"
                  :key="feed.id"
                  class="bg-white border-b dark:bg-gray-300 dark:border-gray-700"
              >

                <td
                    scope="row"
                    class="px-6 py-4 text-gray-900 dark:text-white whitespace-nowrap"
                >
                  <div class="flex flex-row justify-between space-x-2">
                    <div class="">
                      <!--                                                <Link type="text/javascript" :href="`/rss2/${feed.slug}`" class="text-blue-800 uppercase font-semibold text-md hover:text-blue-600 hover:opacity-75 transition ease-in-out duration-150">-->
                      <!--                                                {{feed.name}}-->
                      <!--                                            </Link>-->
                      <button
                          @click="userStore.btnRedirect(`/rss2/${feed.slug}`)"
                          class="text-blue-800 uppercase font-semibold text-md hover:text-blue-600 hover:opacity-75 transition ease-in-out duration-150"
                      >{{ feed.name }}
                      </button>
                    </div>
                    <div class="space-x-1">
                      <button
                          v-if="userStore.isNewsPerson"
                          @click="userStore.btnRedirect(`/rss2/${feed.slug}/edit`)"
                          class="px-2 py-1 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                      >Edit
                      </button>
                      <button
                          v-if="userStore.isAdmin"
                          @click="destroy(feed.id)"
                          class="px-2 py-1 text-white bg-red-600 hover:bg-red-500 rounded-lg"
                      >
                        <font-awesome-icon icon="fa-trash-can"/>
                      </button>
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
import { onBeforeMount, onMounted, ref, watch } from "vue"
import { Inertia } from "@inertiajs/inertia"
import { useForm } from '@inertiajs/inertia-vue3'
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import throttle from "lodash/throttle"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
const appSettingStore = useAppSettingStore()
import { useUserStore } from "@/Stores/UserStore"
import NewsHeaderButtons from "@/Components/Pages/News/NewsHeaderButtons"
import NewsHeader from "@/Components/Pages/News/NewsHeader"
import Pagination from "@/Components/Global/Paginators/Pagination"
import Message from "@/Components/Global/Modals/Messages"

const videoPlayerStore = useVideoPlayerStore()
const userStore = useUserStore()

let props = defineProps({
  filters: Object,
  can: Object,
  feeds: Object,
});

let form = useForm({})

let search = ref(props.filters.search)

userStore.currentPage = 'rss2'
userStore.showFlashMessage = true;

onMounted(() => {
  videoPlayerStore.makeVideoTopRight();
  if (userStore.isMobile) {

    appSettingStore.ott = 0
appSettingStore.pageIsHidden = false
  }
  document.getElementById("topDiv").scrollIntoView()
});

watch(search, throttle(function (value) {
  Inertia.get('/rss2', {search: value}, {
    preserveState: true,
    replace: true
  });
}, 300));

function scrollToCities() {
  document.getElementById("cities").scrollIntoView({behavior: "smooth"})
}


function destroy(id) {
  if (confirm("Are you sure you want to Delete")) {
    form.delete(route('feeds.destroy', id));

  }
}

</script>
