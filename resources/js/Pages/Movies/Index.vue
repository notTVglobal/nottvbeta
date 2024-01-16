<template>

    <Head title="Movies"/>

    <div id="topDiv" class="place-self-center flex flex-col gap-y-3">
        <div class="bg-gray-900 text-white px-5">

            <Message v-if="userStore.showFlashMessage" :flash="$page.props.flash"/>

            <header class="flex justify-between mb-3 border-b border-gray-800">
                <div class="container mx-auto flex flex-col lg:flex-row items-center justify-between px-4 py-6">
                    <div class="flex flex-col lg:flex-row items-center">
                        <h1 class="text-3xl font-semibold">Movies</h1>
                        <ul class="flex ml-0 lg:ml-16 mt-6 lg:mt-0 space-x-8">
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
                    <div class="flex items-center mt-6 lg:mt-0">
                        <div class="relative">
                            <input v-model="search" type="search" class="bg-gray-800 text-sm rounded-full
                            focus:outline-none focus:shadow w-64 pl-8 px-3 py-1" placeholder="Search...">
                            <div class="absolute top-0 flex items-center h-full ml-2">
                                <svg class="fill-current text-gray-400 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M456.69 421.39 362.6 327.3a173.81 173.81 0 0 0 34.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 0 0 327.3 362.6l94.09 94.09a25 25 0 0 0 35.3-35.3ZM97.92 222.72a124.8 124.8 0 1 1 124.8 124.8 124.95 124.95 0 0 1-124.8-124.8Z"/></svg>

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
                                <Link :href="`/movies/${movie.slug}`">
<!--                                    <img :src="`/storage/images/EBU_Colorbars.svg.png`" alt="movie cover" class="h-48 min-w-[8rem] w-28 object-cover hover:opacity-75 transition ease-in-out duration-150">-->
                                    <SingleImage :image="movie.image" :alt="'movie cover'" :class="'h-48 min-w-[8rem] w-28 object-cover hover:opacity-75 transition ease-in-out duration-150'"/>

                                </Link>
                                <div class="absolute bottom-0 right-0 w-12 h-12 bg-gray-800 rounded-full" style="right:-20px; bottom:-20px;">
                                    <div class="font-semi-bold text-xs flex justify-center items-center h-full">80%</div>

                                </div>
                            </div>
                            <Link :href="`/movies/${movie.slug}`" class="block text-base font-semibold leading-tight max-w-[8rem] hover:text-gray-400 mt-4 mb-2">{{ movie.name }}</Link>
                            <div class="text-gray-400 mt-1">{{ movie.category }}
                                <span v-if="movie.release_year">({{movie.release_year}})</span></div>
                            <div class="text-gray-400 mt-1 hidden">{{ movie.subCategory }}</div>
                        </div>

                    </div>
                    <!-- Paginator -->
                    <Pagination :data="movies" class=""/>

                </div>

                <div class="flex flex-col lg:flex-row my-10">
                    <div class="recently-reviewed w-full lg:w-3/4 mr-0 md:mr-16 lg:mr-32">
                        <h2 id="review" class="text-yellow-500 uppercase tracking-wide font-semibold text-2xl">Recently Reviewed</h2>
                        <div class="recently-reviewed-container space-y-12 mt-8">
                            <div v-for="movie in recentlyReviewed.data"
                                 :key="movie.id"
                                 class="movie bg-gray-800 rounded-lg shadow-md flex px-6 py-6">

                                <div class="relative flex-none">
                                    <Link :href="`/movies/${movie.slug}`" class="hover:text-blue-400 hover:opacity-75 transition ease-in-out duration-150">
                                    <SingleImage :image="movie.image" :alt="'movie cover'" class="h-32 md:h-64 md:min-w-[8rem] w-24 md:w-48 object-cover hover:opacity-75 transition ease-in-out duration-150"/>
<!--                                        <img :src="`/storage/images/EBU_Colorbars.svg.png`" alt="movie cover" class="h-32 md:h-64 md:min-w-[8rem] w-24 md:w-48 object-cover hover:opacity-75 transition ease-in-out duration-150">-->
                                    </Link>
                                    <div class="absolute bottom-0 right-0 w-12 h-12 bg-gray-900 rounded-full" style="right:-20px; bottom:-20px;">
                                        <div class="font-semi-bold text-xs flex justify-center items-center h-full">80%</div>
                                    </div>
                                </div>

                                <div class="ml-12">
                                    <Link :href="`/movies/${movie.slug}`" class="block text-lg font-semibold leading-tight max-w-[8rem] hover:text-gray-400 mt-4">
                                        {{ movie.name }}</Link>
                                    <div class="text-gray-400 mt-1">{{ movie.category }}<span class="hidden">, {{ movie.subCategory }}</span></div>
                                    <p class="mt-6 pr-4 text-gray-300 hidden lg:block">
                                        {{ movie.logline }}
                                    </p>
                                </div>
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
                                    <div class="text-gray-400 text-sm mt-1">{{ movie.category }}<span class="hidden">, {{ movie.subCategory }}</span></div>
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
                                    <div class="text-gray-400 text-sm mt-1">{{ movie.category }}<span class="hidden">, {{ movie.subCategory }}</span></div>
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
import { onMounted, watch, onBeforeMount, ref } from "vue"
import throttle from "lodash/throttle"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
const appSettingStore = useAppSettingStore()
import { useUserStore } from "@/Stores/UserStore"
import Pagination from "@/Components/Global/Paginators/PaginationDark"
import Message from "@/Components/Global/Modals/Messages"
import SingleImage from "@/Components/Global/Multimedia/SingleImage"

const videoPlayerStore = useVideoPlayerStore()
const userStore = useUserStore()

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

userStore.currentPage = 'movies'
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


