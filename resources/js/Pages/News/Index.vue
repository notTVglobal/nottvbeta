<template>
  <Head title="News"/>
  <div id="topDiv" class="flex flex-col min-h-screen bg-gray-50 text-black" :class="marginTopClass">

    <header class="place-self-center flex flex-col bg-gray-50 text-black dark:bg-gray-800 dark:text-gray-50">
      <PublicNewsNavigationButtons :can="can"/>
    </header>

    <PublicNavigationMenu v-if="!appSettingStore.loggedIn" class="fixed top-0 w-full nav-mask"/>




    <main class="flex-grow text-black mx-auto pb-64">
      <div class="mx-auto px-4 border-b border-gray-800 flex justify-center">

        <section>
          <NewsStoriesTable :newsStories="newsStories" :filters="filters"/>
        </section>




<!--        <div class="mb-20 w-[calc(80vw)] px-10 2xl:px-96 py-10 text-gray-900 rounded">-->
<!--          <div class="container mx-auto flex flex-col md:flex-row justify-between items-center">-->
<!--            <div class="w-full flex justify-center">-->
<!--              <div class="overflow-hidden shadow-sm sm:rounded-lg">-->
<!--                <div class="p-6 border-b border-gray-200">-->
<!--                  <div class="relative overflow-x-auto shadow-md sm:rounded-lg">-->
<!--                    <div class="text-center text-3xl font-semibold tracking-widest uppercase mb-6">News Stories</div>-->
<!--                    &lt;!&ndash;Search Box&ndash;&gt;-->
<!--                    <div class="flex flex-row justify-center items-center my-3 lg:mt-0">-->
<!--                      <div class="relative">-->
<!--                        <input v-model="search" type="search" class="bg-gray-50 text-black text-sm rounded-full-->
<!--                            focus:outline-none focus:shadow w-64 pl-8 px-3 py-1" placeholder="Search...">-->
<!--                        <div class="absolute top-0 flex items-center h-full ml-2">-->
<!--                          <svg class="fill-current text-gray-400 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">-->
<!--                            <path-->
<!--                                d="M456.69 421.39 362.6 327.3a173.81 173.81 0 0 0 34.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 0 0 327.3 362.6l94.09 94.09a25 25 0 0 0 35.3-35.3ZM97.92 222.72a124.8 124.8 0 1 1 124.8 124.8 124.95 124.95 0 0 1-124.8-124.8Z"/>-->
<!--                          </svg>-->

<!--                        </div>-->
<!--                      </div>-->
<!--                    </div>-->


<!--                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">-->
<!--                      <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">-->
<!--                      <tr>-->
<!--                        <th class="hidden md:table-cell px-6 py-3 min-w-[8rem]">Image</th>-->
<!--                        <th class="px-6 py-3">Title</th>-->
<!--                        <th class="hidden xl:table-cell px-6 py-3">Published On</th>-->
<!--                      </tr>-->
<!--                      </thead>-->
<!--                      <tbody>-->
<!--                      <tr v-for="news in news.data" :key="news.id" class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">-->
<!--                        <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap">-->
<!--                          <button @click="appSettingStore.btnRedirect(`/news/story/${news.slug}`)">-->
<!--                            <img :src="`/storage/images/${news.image}`" class="rounded-full h-20 w-20 object-cover" alt="news cover">-->
<!--                          </button>-->
<!--                        </td>-->
<!--                        <td class="px-6 py-4 whitespace-nowrap align-middle">-->
<!--                          <button @click="appSettingStore.btnRedirect(`/news/story/${news.slug}`)"-->
<!--                                  class="text-lg font-semibold text-blue-800 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-200">-->
<!--                            {{ news.title }}-->
<!--                          </button>-->
<!--                        </td>-->
<!--                        <td class="hidden xl:table-cell px-6 py-4 whitespace-nowrap align-middle">-->
<!--                <span :class="{'text-gray-500': news.published_at, 'italic': !news.published_at}">-->
<!--&lt;!&ndash;                  {{ formatDate(news.published_at) }}&ndash;&gt;-->
<!--                  {{news.published_at}}-->
<!--                </span>-->
<!--                        </td>-->
<!--                      </tr>-->
<!--                      </tbody>-->
<!--                    </table>-->
                    <!-- Paginator -->
<!--                    <Pagination :data="newsStories" class=""/>-->
<!--                  </div>-->
<!--                </div>-->
<!--              </div>-->



















<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
      </div>
    </main>

    <Footer v-if="!appSettingStore.loggedIn"/>

  </div>
</template>


<script setup>

import { computed, nextTick, onMounted, ref, watch } from 'vue'
// import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import PublicNewsNavigationButtons from '@/Components/Pages/Public/PublicNewsNavigationButtons.vue'
import PublicNavigationMenu from '@/Components/Global/Navigation/PublicNavigationMenu'
import Footer from '@/Components/Global/Layout/Footer.vue'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useForm } from '@inertiajs/inertia-vue3'
import throttle from 'lodash/throttle'
import { Inertia } from '@inertiajs/inertia'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import Pagination from '@/Components/Global/Paginators/Pagination'
import { formatDate } from '@vueuse/shared'
import NewsStoriesTable from '@/Components/Pages/Newsroom/NewsStoriesTable.vue'

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()

appSettingStore.noLayout = true
appSettingStore.currentPage = 'news'

// appSettingStore.pageIsHidden = false
// videoPlayerStore.makeVideoTopRight()

// Watch for changes in the loggedIn state of appSettingStore
watch(() => appSettingStore.loggedIn, (loggedIn) => {
  appSettingStore.noLayout = !loggedIn;

  // Call usePageSetup if loggedIn is true
  if (loggedIn) {
    console.log('Logged In:', loggedIn, 'Video Player Loaded:', videoPlayerStore.videoPlayerLoaded);
    usePageSetup('news');
  }
  nextTick(() => {
    videoPlayerStore.makeVideoTopRight()
    appSettingStore.pageIsHidden = false
  });
}, {
  immediate: true // This ensures the watcher runs immediately on setup
});

const props = defineProps({
  newsStories: Array,
  filters: Object,
  can: Object,
  // news: {
  //   type: Object,
  //   default: () => ({}),
  // },
})

let search = ref(props.filters.search)

let form = useForm({})

watch(search, throttle(function (value) {
  Inertia.get('/news', {search: value}, {
    preserveState: true,
    replace: true,
  })
}, 300))

const marginTopClass = computed(() => {
  return appSettingStore.loggedIn ? '' : 'mt-16';
});


</script>
