<template>

    <Head title="Movies"/>

    <div id="topDiv" class="place-self-center flex flex-col gap-y-3">
        <div class="bg-gray-900 text-white px-5 min-h-screen">

            <Message v-if="appSettingStore.showFlashMessage" :flash="$page.props.flash"/>

          <header class="flex justify-between mb-3 border-b border-gray-800">
            <div class="container mx-auto flex flex-col xl:flex-row items-center justify-between px-4 py-6">
              <div class="flex flex-col xl:flex-row items-center">
                <h1 class="text-3xl font-semibold">Movies</h1>
                <ul class="hidden flex ml-0 xl:ml-16 mt-6 xl:mt-0 space-x-8">
                  <li>
                    <button :href="``" class="text-gray-700 cursor-not-allowed">Categories</button>
                  </li>
                  <li>
                    <button :href="``" @click.prevent="scrollToReview" class="hover:text-blue-800">Reviews</button>
                  </li>
                  <li>
                    <button :href="``" @click.prevent="scrollToComingSoon" class="hover:text-blue-800">Coming Soon</button>
                  </li>
                </ul>
              </div>
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
          </header>


            <main class="py-8">

                <div class="container mx-auto px-4 border-b border-gray-800 pb-16">
                    <h2 class="text-yellow-500 uppercase tracking-wide font-semibold text-2xl">Popular Movies</h2>
                    <div class="popular-movies text-sm grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-12 pb-12 justify-items-center">

                        <div v-for="movie in movies.data"
                             :key="movie.id"
                             class="movie mt-8">
                            <div class="relative inline-block">
                              <div v-if="movie.statusId === 9" class="absolute flex justify-end w-full -mt-3 z-50">
                                <CreatorsOnlyBadge />
                              </div>
                              <div v-else-if="movie.isNew" class="absolute flex justify-end w-full -mt-3 z-50">
                                <NewContentBadge />
                              </div>
                                <Link :href="`/movies/${movie.slug}`">
<!--                                    <img :src="`/storage/images/EBU_Colorbars.svg.png`" alt="movie cover" class="h-48 min-w-[8rem] w-28 object-cover hover:opacity-75 transition ease-in-out duration-150">-->
                                    <SingleImage :image="movie.image" :alt="'movie cover'" :class="'h-48 min-w-[8rem] w-28 object-cover hover:opacity-75 transition ease-in-out duration-150'"/>

                                </Link>
                                <div class="hidden absolute bottom-0 right-0 w-12 h-12 bg-gray-800 rounded-full" style="right:-20px; bottom:-20px;">
                                    <div class="font-semi-bold text-xs flex justify-center items-center h-full">80%</div>

                                </div>
                            </div>
                            <Link :href="`/movies/${movie.slug}`" class="block text-base font-semibold leading-tight max-w-[8rem] hover:text-gray-400 mt-4 mb-2">{{ movie.name }}</Link>
                            <div class="text-yellow-700 mt-1 uppercase tracking-wide">{{ movie.category?.name }}
                                <span v-if="movie.release_year">({{movie.release_year}})</span></div>
                            <div class="text-yellow-500 mt-1 tracking-wide">{{ movie.subCategory?.name }}</div>
                        </div>

                    </div>
                    <!-- Paginator -->
                    <Pagination :data="movies" class=""/>

                </div>

                <div class="hidden w-full flex-col xl:flex-row my-10">

<!--                    <div class="recently-reviewed w-full lg:w-3/4 mr-0 md:mr-16 lg:mr-32">-->
                  <div class="w-full xl:w-3/4 mr-0 md:mr-16 xl:mr-32">
                    <h2 id="review" class="text-yellow-500 uppercase tracking-wide font-semibold text-2xl">Recently Reviewed</h2>
                    <div class="recently-reviewed-container space-y-12 mt-8">
                      <div v-for="movie in recentlyReviewed.data"
                           :key="movie.id"
                           class="hover:text-blue-400 hover:opacity-75 transition ease-in-out duration-150">
                        <Link :href="`/movies/${movie.slug}`" class="flex flex-row bg-gray-800 rounded-lg shadow-md px-6 py-6">
                          <div class="relative w-48 min-w-[12rem]">
                            <div v-if="movie.statusId === 9" class="absolute flex justify-end w-full -mt-3 z-50">
                              <CreatorsOnlyBadge />
                            </div>
                            <div v-else-if="movie.isNew" class="absolute flex justify-end w-full -mt-3 z-50">
                              <NewContentBadge />
                            </div>
                            <SingleImage :image="movie.image" :alt="'movie cover'" class="h-28 w-48 min-w-[12rem] object-cover bg-black"/>
                            <div class="hidden absolute right-0 -mt-6 -mr-3 w-12 h-12 bg-gray-900 rounded-full">
                              <div class="font-semi-bold text-xs flex justify-center items-center h-full">80%</div>
                            </div>
                            <div class="flex flex-col mt-4 w-full">
                              <div class="uppercase tracking-wider text-yellow-700 font-semibold">{{ movie.category?.name }}</div>
                              <div v-if="movie.subCategory?.name" class="text-yellow-500 font-thin">{{ movie.subCategory?.name }}</div>
                            </div>
                          </div>


                          <div class="ml-4 block">
                            <span class="text-lg font-semibold tracking-wide">{{ movie.name }}</span>
                            <div class="flex flex-row w-full text-gray-200 font-light text-sm">
                              {{ movie.release_year }}
                            </div>
                            <p class="font-medium mt-2 pr-4 text-gray-300 xl:block">
                              {{ movie.logline }}
                            </p>
                          </div>
                        </Link>
                      </div>
                    </div>
                  </div>


                  <div class="flex flex-col xl:flex-row my-10 pl-4">

                    <div class="most-anticipated w-full xl:w-3/4 mr-0 md:mr-16 xl:mr-32">
                        <h2 class="text-yellow-500 uppercase tracking-wide font-semibold text-2xl">Most Anticipated</h2>
                        <div class="most-anticipated-container space-y-10 mt-8">
                            <div v-for="movie in mostAnticipated.data"
                                 :key="movie.id"
                                 class="movie flex">
                                <Link :href="`/movies/${movie.slug}`">
                                    <SingleImage :image="movie.image" :alt="'movie cover'" class="h-24 min-w-[4rem] w-16 object-cover hover:opacity-75 transition ease-in-out duration-150"/>
                                </Link>
                                <div class="ml-4">
                                    <Link :href="`/movies/${movie.slug}`" class="hover:text-gray-300">{{ movie.name }}</Link>
                                    <div class="text-gray-400 text-sm mt-1">{{ movie.category?.name }}<span class="hidden">, {{ movie.subCategory?.name }}</span></div>
                                </div>
                            </div>
                        </div>

                        <h2 id="coming-soon" class="text-yellow-500 uppercase tracking-wide font-semibold mt-16 text-2xl">Coming Soon</h2>
                        <div class="most-anticipated-container space-y-10 mt-8">
                            <div v-for="movie in comingSoon.data"
                                 :key="movie.id"
                                 class="movie flex">
                                <Link :href="`/movies/${movie.slug}`">
                                    <SingleImage :image="movie.image" :alt="'movie cover'" class="h-24 min-w-[4rem] w-16 object-cover hover:opacity-75 transition ease-in-out duration-150"/>
                                </Link>
                                <div class="ml-4">
                                    <Link :href="`/movies/${movie.slug}`" class="hover:text-gray-300">{{ movie.name }}</Link>
                                    <div class="text-gray-400 text-sm mt-1">{{ movie.category?.name }}<span class="hidden">, {{ movie.subCategory?.name }}</span></div>
                                </div>
                            </div>
                        </div>

                    </div>
                    </div>


                </div>



            </main>

            <footer class="border-t border-gray-800">
                <div class="container text-right text-gray-800 mx-auto px-4 py-6">
                    Powered by not.tv
                </div>

            </footer>




        </div>
    </div>

</template>

<script setup>
import { Inertia } from "@inertiajs/inertia"
import { watch, ref } from "vue"
import throttle from "lodash/throttle"
import { usePageSetup } from '@/Utilities/PageSetup'
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import Pagination from "@/Components/Global/Paginators/PaginationDark"
import Message from "@/Components/Global/Modals/Messages"
import SingleImage from "@/Components/Global/Multimedia/SingleImage"
import CreatorsOnlyBadge from '@/Components/Global/Badges/CreatorsOnlyBadge.vue'
import NewContentBadge from '@/Components/Global/Badges/NewContentBadge.vue'

usePageSetup('movies')

const appSettingStore = useAppSettingStore()

let props = defineProps({
    movies: Object,
    recentlyReviewed: Object,
    mostAnticipated: Object,
    comingSoon: Object,
    filters: Object,
    can: Object,
})

let movie = 'test-movie-2'
let search = ref(props.filters.search);

watch(search, throttle(function (value) {
    Inertia.get('/movies', { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));

function scrollToReview() {
    document.getElementById("review").scrollIntoView({behavior: "smooth"})
}

function scrollToComingSoon() {
    document.getElementById("coming-soon").scrollIntoView({behavior: "smooth"})
}

</script>


