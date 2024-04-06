<template>

  <Head title="News RSS Archive"/>
  <!--    <div class="sticky top-0 w-full nav-mask">-->
  <!--        <ResponsiveNavigationMenu/>-->
  <!--        <NavigationMenu />-->
  <!--    </div>-->

  <!--    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">-->
  <div class="place-self-center flex flex-col gap-y-3">
    <div id="topDiv" class="bg-white dark:bg-gray-800 text-black dark:text-gray-50 p-5 mb-10">
      <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>
      <NewsHeader>Newsroom</NewsHeader>

      <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-900 border-b border-gray-200">
<!--          <div class="flex justify-center">-->
<!--            <div class="flex items-center">-->
<!--              <div class="relative w-full">-->
<!--                <input v-model="search" type="search" class="bg-gray-50 text-black text-sm rounded-full focus:outline-none focus:shadow w-full pl-10 py-2" placeholder="Search...">-->
<!--                <div class="absolute top-0 left-0 flex items-center h-full ml-4">-->
<!--                  <svg class="fill-current text-gray-400 w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">-->
<!--                    <path d="M456.69 421.39 362.6 327.3a173.81 173.81 0 0 0 34.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 0 0 327.3 362.6l94.09 94.09a25 25 0 0 0 35.3-35.3ZM97.92 222.72a124.8 124.8 0 1 1 124.8 124.8 124.95 124.95 0 0 1-124.8-124.8Z"/>-->
<!--                  </svg>-->
<!--                </div>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->


          <table
              class="table-auto w-full text-sm text-left text-gray-500 dark:text-gray-400"
          >
            <thead
                class="flex flex-justify text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr class="w-full">
              <th scope="col"
                  class="w-full flex flex-row justify-between px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                <div>
                  <span class="text-xl md:text-2xl font-medium">News RSS Archive</span>

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
          </table>

          <Pagination :data="archive"/>

          <div class="flex flex-col items-center w-full">
            <div class="py-4 px-3 space-y-3 w-full max-w-4xl">
              <div v-for="item in archive.data" class="bg-gray-600 text-white p-5 rounded-xl">
                <div class="flex flex-row mb-2">
                  <div class="w-full text-xl font-semibold"><a :href="item.url" target="_blank">{{ item.title }}</a></div>
                </div>
                <div class="text-xs mb-2">{{ formatDate(item.pubDate) }}</div>
                <div v-html="item.description" class="mb-2"></div>
                <div class="flex justify-center"><a :href="item.url" target="_blank">
                  <img v-if="!item.image" :src="item.image_url" class="max-w-full h-auto">
                  <SingleImage v-if="item.image" :image="item.image.data"/>
                </a></div>
                <p v-if="item.feedName" class="mt-2 text-xs tracking-wider hover:text-blue-300"><Link :href="`/newsRssFeeds/${item.feedSlug}`"><span class="font-semibold">{{ item.feedName }}</span></Link></p>
              </div>
              <div class="py-8">
                <Pagination :data="archive"/>
              </div>
            </div>
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
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import throttle from 'lodash/throttle'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import NewsHeaderButtons from '@/Components/Pages/News/NewsHeaderButtons'
import NewsHeader from '@/Components/Pages/News/NewsHeader'
import Pagination from '@/Components/Global/Paginators/Pagination'
import Message from '@/Components/Global/Modals/Messages'
import SingleImage from '@/Components/Global/Multimedia/SingleImage.vue'

usePageSetup('newsRssArchive.index')

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()

let props = defineProps({
  filters: Object,
  archive: Object,
})

let form = useForm({})

let search = ref(props.filters.search)

watch(search, throttle(function (value) {
  Inertia.get('/newsRssArchive', {search: value}, {
    preserveState: true,
    replace: true,
  })
}, 300))

onMounted(() => {
  const appSettingStore = useAppSettingStore();
  appSettingStore.shouldScrollToTop = true;
});

</script>
