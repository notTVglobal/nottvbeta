<template>

  <Head title="News"/>
  <!--    <div class="sticky top-0 w-full nav-mask">-->
  <!--        <ResponsiveNavigationMenu/>-->
  <!--        <NavigationMenu />-->
  <!--    </div>-->

  <!--    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">-->
  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white dark:bg-gray-800 text-black dark:text-gray-50 p-5 mb-10">

      <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>
      <NewsHeader :can="can">News</NewsHeader>

      <div class="my-4">
        Events, shows, episodes, movies, news, channel updates, announcements, etc.
      </div>

      <div class="flex items-center my-3 lg:mt-0">
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

      <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-900 border-b border-gray-200">
          <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table
                class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
            >
              <thead
                  class="w-full text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
              >
              <tr>
                <th scope="col" class="w-full px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                  News Posts
                </th>
                <th scope="col" class="text-left px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                  Published on
                </th>
              </tr>
              </thead>
              <tbody>
              <tr
                  v-for="post in news.data"
                  :key="post.id"
                  class="bg-white border-b dark:bg-gray-300 dark:border-gray-700"
              >

                <td
                    scope="row"
                    class="px-6 py-4 text-gray-900 dark:text-white whitespace-nowrap"
                >
                  <Link :href="`/news/${post.slug}`"
                        class="text-blue-800 uppercase font-semibold text-md hover:text-blue-600 hover:opacity-75 transition ease-in-out duration-150">
                    <div class="flex flex-row"><img :src="'/storage/images/' + post.image" alt="news cover"
                                                    class="pr-4 h-6 max-w-[6rem] object-cover ">
                      {{ post.title }}
                    </div>
                  </Link>
                </td>
                <td class="text-gray-900">
                  {{ formatDate(post.published_at) }}
                </td>
              </tr>
              </tbody>
            </table>
            <!-- Paginator -->
            <Pagination :data="news" class="mt-6"/>
          </div>
        </div>
      </div>


    </div>

  </div>

</template>

<script setup>
import { Inertia } from "@inertiajs/inertia"
import { ref, watch } from "vue"
import { useForm } from '@inertiajs/inertia-vue3'
import throttle from "lodash/throttle"
import { usePageSetup } from '@/Utilities/PageSetup'
import { useUserStore } from "@/Stores/UserStore"
import NewsHeader from "@/Components/Pages/News/NewsHeader"
import NewsHeaderButtons from "@/Components/Pages/News/NewsHeaderButtons"
import Pagination from "@/Components/Global/Paginators/Pagination"
import Message from "@/Components/Global/Modals/Messages"

usePageSetup('newsIndex')

const userStore = useUserStore()

function scrollToCities() {
  document.getElementById("cities").scrollIntoView({behavior: "smooth"})
}

let props = defineProps({
  filters: Object,
  can: Object,
  news: Object,
});

let form = useForm({
  id: '',
});

let search = ref(props.filters.search)

watch(search, throttle(function (value) {
  Inertia.get('/news', {search: value}, {
    preserveState: true,
    replace: true
  })
}, 300))

function destroy(id) {
  if (confirm("Are you sure you want to Delete")) {
    form.delete(route('news.destroy', ['news', id]))
  }
}

</script>
