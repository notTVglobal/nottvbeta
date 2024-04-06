<template>

  <Head title="List RSS Feeds"/>
  <!--    <div class="sticky top-0 w-full nav-mask">-->
  <!--        <ResponsiveNavigationMenu/>-->
  <!--        <NavigationMenu />-->
  <!--    </div>-->

  <!--    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">-->
  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white dark:bg-gray-800 text-black dark:text-gray-50 p-5 mb-10">
      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>
      <NewsHeader :can="can">Newsroom</NewsHeader>

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
                    <span class="text-xl md:text-2xl font-medium">List RSS Feeds</span>
                    <button
                        v-if="can.viewNewsroom"
                        @click="appSettingStore.btnRedirect(`newsRssFeeds/create`)"
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
                    <div class="flex flex-row justify-between w-full">
                      <!--                                                <Link type="text/javascript" :href="`/rss2/${feed.slug}`" class="text-blue-800 uppercase font-semibold text-md hover:text-blue-600 hover:opacity-75 transition ease-in-out duration-150">-->
                      <!--                                                {{feed.name}}-->
                      <!--                                            </Link>-->
                      <div>
                        <button
                            @click="appSettingStore.btnRedirect(`/newsRssFeeds/${feed.slug}`)"
                            class="text-blue-800 uppercase font-semibold text-md hover:text-blue-600 hover:opacity-75 transition ease-in-out duration-150"
                        >{{ feed.name }}
                        </button>
                      </div>
                      <div class="mr-2"><span v-if="feed.lastSuccessfulUpdate">Last update on {{userStore.formatDateTimeFromUtcToUserTimezone(feed.lastSuccessfulUpdate)}}</span><span v-else>Never updated</span></div>
                    </div>
                    <div class="space-x-1">
                      <button
                          v-if="userStore.isNewsPerson"
                          @click="appSettingStore.btnRedirect(`/newsRssFeeds/${feed.slug}/edit`)"
                          class="px-2 py-1 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                      >
                        <font-awesome-icon icon="fa-pencil"/>
                      </button>
                      <button
                          v-if="userStore.isAdmin"
                          @click="destroy(feed.slug)"
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
import { ref, watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { useForm } from '@inertiajs/inertia-vue3'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import throttle from 'lodash/throttle'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import NewsHeaderButtons from '@/Components/Pages/News/NewsHeaderButtons'
import NewsHeader from '@/Components/Pages/News/NewsHeader'
import Pagination from '@/Components/Global/Paginators/Pagination'
import Message from '@/Components/Global/Modals/Messages'

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
  Inertia.get('/newsRssFeeds', {search: value}, {
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

</script>
