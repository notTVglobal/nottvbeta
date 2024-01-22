<template>
  <div>
    <Head title="Newsroom"/>

    <div class="place-self-center flex flex-col gap-y-3">
      <div id="topDiv" class="bg-white text-black dark:bg-gray-900 dark:text-gray-50 p-5 mb-10">

        <Messages v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>
        <section class="text-center p-4 md:p-8 mb-8">

        </section>

        <section class="text-center p-4 md:p-8 mb-8">
          <h1 class="text-2xl md:text-4xl font-bold mb-4">Welcome To Your Digital Newsroom</h1>
          <p class="text-md md:text-lg mb-8 text-indigo-800 ">
            Where seasoned journalism intersects with digital innovation.<br>
          </p>
          <p class="mb-8 mx-auto w-1/2 text-center text-indigo-800 font-semibold">
            Here, we are redefining news for the digital era, cutting-edge tools and collaborative expertise help us deliver impactful stories with precision and depth.
          </p>

          <h2 class="text-center text-xl md:text-3xl  mb-4">Core Pillars of the Newsroom</h2>

          <ul class="list-disc list-inside text-left mx-auto w-1/2 md:w-1/2">
            <li class="mb-2"><span class="font-semibold">Advanced Information Gathering:</span> Utilizing a vast network of digital resources and on-the-ground insights to capture the full spectrum of news.</li>
            <li class="mb-2"><span class="font-semibold">Precision Editing:</span> Combining journalistic rigor with digital acumen to sharpen narratives and enhance factual accuracy.</li>
            <li class="mb-2"><span class="font-semibold">Innovative Content Production:</span> Crafting stories that thrive across mediums – from immersive articles to engaging digital broadcasts.</li>
            <li class="mb-2"><span class="font-semibold">Strategic Coverage Planning:</span> Focused on impactful journalism, we dissect and deliver stories that matter in real-time.</li>
            <li class="mb-2"><span class="font-semibold">Collaborative Dynamics:</span> Our strength lies in our collective expertise, fostering a culture of continuous learning and mutual enhancement.</li>
            <li><span class="font-semibold">Technology at the Forefront:</span> Harnessing the power of the latest digital tools to keep our newsroom ahead in a rapidly evolving media landscape.</li>
          </ul>
        </section>

        <NewsHeader :can="can">Newsroom</NewsHeader>

        <!--            <header class="flex justify-between mb-3 border-b border-gray-800">-->
        <!--                <div class="container mx-auto flex flex-col lg:flex-row items-center justify-between">-->

        <!--                    -->
        <!--                </div>-->

        <!--            </header>-->

        <section hidden class="p-4">
          <div class="alert alert-info text-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 class="stroke-current shrink-0 w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>
              <span class="font-semibold">This page is only visible to members of the Newsroom.</span> <br>Create news articles, share resources, edit stories, and when they are ready the News Producer(s) can publish them!
            </span>
          </div>
        </section>



        <h2 class="text-center text-xl md:text-3xl font-semibold mb-4">News Stories</h2>

        <div class="flex justify-center my-3 lg:mt-0">
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

        <div class="flex justify-end">
          <button
              v-if="can.createNewsStory"
              @click="appSettingStore.btnRedirect(`newsStory/create`)"
              class="bg-green-600 hover:bg-green-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
          >Create News Story
          </button>
        </div>

        <div class="overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 border-b border-gray-200">
            <div
                class="relative overflow-x-auto shadow-md sm:rounded-lg"
            >

              <div
                  class="table w-full text-sm text-left text-gray-500 dark:text-gray-400"
              >
                <div
                    class="table-header-group text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                >
                  <div class="table-row">
                    <div scope="col" class="hidden md:table-cell min-w-[8rem] px-6 py-3">
                      Image
                    </div>
                    <div scope="col" class="table-cell px-6 py-3">
                      Title
                    </div>
                    <div scope="col" class="hidden xl:table-cell px-6 py-3">
                      Created On
                    </div>
                    <div scope="col" class="hidden 2xl:table-cell px-6 py-3">
                      Status
                    </div>
                    <div scope="col" class="hidden lg:table-cell px-6 py-3">
                      Published On
                    </div>
                    <div scope="col" class="hidden lg:table-cell px-6 py-3">

                    </div>
                  </div>
                </div>
                <div class="table-row-group">
                  <div
                      v-for="news in news.data"
                      :key="news.id"
                      class="table-row bg-white border-b dark:bg-gray-900 dark:border-gray-700 "
                  >
                    <div
                        scope="row"
                        class="hidden md:table-cell min-w-[8rem] px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                    >
                      <button
                          @click="appSettingStore.btnRedirect(`/news/story/${news.slug}`)"
                      ><img :src="`/storage/images/${news.image}`" class="rounded-full h-20 w-20 object-cover"
                            alt="news cover"></button>
                    </div>
                    <div
                        scope="row"
                        class="table-cell px-6 py-4 font-medium text-gray-900 dark:text-gray-50 whitespace-nowrap align-middle"
                    >
                      <button
                          @click="appSettingStore.btnRedirect(`/news/story/${news.slug}`)"
                          class="text-lg font-semibold text-blue-800 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-200"
                      >{{ news.title }}
                      </button>
                    </div>
                    <div
                        scope="row"
                        class="hidden xl:table-cell px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap align-middle"
                    >
                      <span :class="{'text-gray-500':news.published_at, 'italic':news.published_at}">{{
                          formatDate(news.created_at)
                        }}</span>
                    </div>
                    <div class="hidden 2xl:table-cell px-6 py-4">
                      <span>{{ news.status }}</span>
                    </div>
                    <div class="hidden lg:table-cell px-6 py-4 align-middle space-x-2">
                      <button
                          v-if="props.can.publishNewsStory && !news.published_at"
                          @click="publish(news.id)"
                          class="bg-green-600 hover:bg-green-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
                      >Publish
                      </button>
                      <span v-if="!props.can.publishNewsStory && !news.published_at" class="mr-6 text-gray-500 italic"> not yet published</span>
                      <span v-if="news.published_at" class="mr-6 text-gray-800 dark:text-white font-semibold">{{
                          formatDate(news.published_at)
                        }}</span>
                    </div>
                    <div class="hidden lg:table-cell px-6 py-4 align-middle space-x-2 space-y-2">
                      <button
                          v-if="news.can.editNewsStory"
                          @click="appSettingStore.btnRedirect(`/newsStory/${news.slug}/edit`)"
                          class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                      >Edit
                      </button>
                      <button
                          class="px-4 py-2 text-white font-semibold bg-red-600 hover:bg-red-500 rounded-lg"
                          @click="destroy(news.id)"
                          v-if="news.can.deleteNewsStory"
                      >
                        <font-awesome-icon icon="fa-trash-can"/>
                      </button>
                    </div>

                  </div>
                </div>
              </div>
              <!-- Paginator -->
              <Pagination :data="news" class=""/>
            </div>
          </div>
        </div>


      </div>
    </div>
  </div>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'
import { ref, watch } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import throttle from 'lodash/throttle'
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useUserStore } from '@/Stores/UserStore'
import NewsHeader from '@/Components/Pages/News/NewsHeader'
import Pagination from '@/Components/Global/Paginators/Pagination'
import Messages from '@/Components/Global/Modals/Messages'

usePageSetup('newsroom')

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()

function scrollToCities() {
  document.getElementById('cities').scrollIntoView({behavior: 'smooth'})
}

let props = defineProps({
  filters: Object,
  can: Object,
  news: {
    type: Object,
    default: () => ({}),
  },
})

let search = ref(props.filters.search)

let form = useForm({})

watch(search, throttle(function (value) {
  Inertia.get('/newsroom', {search: value}, {
    preserveState: true,
    replace: true,
  })
}, 300))

function publish(id) {
  if (confirm('Are you sure you want to Publish')) {
    // form.put(route('news.publish', id));
    Inertia.patch(route('newsroom.publish', {id: id}))

  }
}

function destroy(id) {
  if (confirm('Are you sure you want to Delete')) {
    form.delete(route('newsStory.destroy', id))

  }
}

</script>
