<template>
    <Head title="Shows" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black dark:bg-gray-900 dark:text-white p-5 mb-10">

            <header class="flex justify-between mb-3 border-b border-gray-800">
                <div class="container mx-auto flex flex-col lg:flex-row items-center justify-between px-4 py-6">

                    <div
                        class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                        role="alert"
                        v-if="props.message"
                    >
                                <span class="font-medium">
                                    {{props.message}}
                                </span>
                    </div>

                    <div class="flex flex-col lg:flex-row items-center">
                        <h1 class="text-3xl font-semibold">Shows</h1>
                        <ul class="flex ml-0 lg:ml-16 mt-6 lg:mt-0 space-x-8" >
                            <li>
                                <Link :href="``" class="hover:text-blue-800">Shows</Link>
                            </li>
                            <li>
                                <Link :href="``" class="hover:text-blue-800">New Episodes</Link>
                            </li>
                            <li>
                                <Link :href="``" class="hover:text-blue-800">Coming Soon</Link>
                            </li>
                        </ul>
                    </div>
                    <div class="flex items-center mt-6 lg:mt-0">
                        <div class="relative">
                            <input v-model="search" type="search" class="bg-gray-50 text-sm rounded-full
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
                    <h2 class="text-purple-800 uppercase tracking-wide font-semibold">Popular Shows</h2>
                    <div class="popular-shows text-sm grid grid-cols-1 md:grid-cols-2 space-x-6 lg:grid-cols-3 xl:grid-cols-4 gap-8 pb-12">

                        <div v-for="show in shows.data"
                             :key="show.id"
                             class="show mt-8 max-w-[12rem]">
                            <div class="relative inline-block">
                                <Link :href="`/shows/${show.slug}`">
                                    <img :src="'/storage/images/' + show.poster" alt="show cover" class="h-48 min-w-[8rem] w-28 object-cover hover:opacity-75 transition ease-in-out duration-150">
                                </Link>
                            </div>
                            <Link :href="`/shows/${show.slug}`" class="block text-base font-semibold leading-tight hover:text-gray-400 mt-4">{{ show.name }}</Link>
                            <div class="text-gray-400 mt-1">Category (YYYY)</div>
                            <div class="text-gray-400 mt-1">Sub-category</div>
                        </div>

                    </div>
                    <!-- Paginator -->
                    <Pagination :links="shows.links" class="mt-6"/>
                </div>

                <div class="flex flex-col lg:flex-row my-10">
                    <div class="recently-reviewed w-full lg:w-3/4 mr-0 md:mr-16 lg:mr-32">
                        <h2 class="text-purple-800 uppercase tracking-wide font-semibold">Newest Episodes</h2>
                        <div class="recently-reviewed-container space-y-12 mt-8">

                            <div v-for="episode in episodes.data"
                                 :key="episode.id"
                                 class="show bg-gray-100 rounded-lg shadow-md flex px-6 py-6">
                                <div class="relative flex-none">
                                    <Link :href="`/shows/${episode.showSlug}/episode/${episode.slug}`">
                                        <img :src="'/storage/images/' + episode.poster" alt="show cover" class="h-64 min-w-[8rem] w-48 object-cover hover:opacity-75 transition ease-in-out duration-150">
                                    </Link>
                                </div>
                                <div class="ml-12">
                                    <Link :href="`/shows/${episode.showSlug}/episode/${episode.slug}`" class="block text-lg font-semibold leading-tight hover:text-gray-400 mt-4">
                                        {{ episode.name }}</Link>
                                    <div class="text-gray-600 mt-1"><Link :href="`/shows/${episode.showSlug}`">{{ episode.showName }}</Link></div>
                                    <p class="mt-6 pr-4 text-gray-800 hidden lg:block">
                                        {{ episode.description}}
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="most-anticipated lg:w-1/4 mt-12 lg:mt-0">
                        <h2 class="text-purple-800 uppercase tracking-wide font-semibold">Trending Now</h2>
                        <div class="most-anticipated-container space-y-10 mt-8">

                            <div v-for="show in showsTrending.data"
                                 :key="show.id"
                                 class="show flex">
                                <Link :href="`/shows/${show.slug}`">
                                    <img :src="'/storage/images/' + show.poster"
                                         alt="show cover"
                                         class="h-24 min-w-[4rem] w-16 object-cover hover:opacity-75 transition ease-in-out duration-150">
                                </Link>
                                <div class="ml-4">
                                    <Link :href="`/shows/${show.slug}`" class="hover:text-gray-300">{{ show.name }}</Link>
                                    <div class="text-gray-400 text-sm mt-1">{{ show.copyrightYear }}</div>
                                </div>
                            </div>
                        </div>

                        <h2 class="text-purple-800 uppercase tracking-wide font-semibold mt-16">Coming Soon</h2>
                        <div class="most-anticipated-container space-y-10 mt-8">

                            <div v-for="show in showsComingSoon.data"
                                 :key="show.id"
                                 class="show flex">
                                <Link :href="`/shows/${show.slug}`">
                                    <img :src="'/storage/images/' + show.poster"
                                         alt="show cover"
                                         class="h-24 min-w-[4rem] w-16 object-cover hover:opacity-75 transition ease-in-out duration-150">
                                </Link>
                                <div class="ml-4">
                                    <Link :href="`/shows/${show.slug}`" class="hover:text-gray-300">{{ show.name }}</Link>
                                    <div class="text-gray-400 text-sm mt-1">{{ show.copyrightYear }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>

            <footer class="border-t border-gray-800">
                <div class="container text-right text-gray-200 mx-auto px-4 py-6">
                    Powered by not.tv
                </div>

            </footer>

        </div>
    </div>

</template>

<script setup>
import Pagination from "@/Components/Pagination"
import {onMounted, ref, watch} from "vue"
import {Inertia} from "@inertiajs/inertia"
import throttle from "lodash/throttle"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/Navigation/NavigationMenu"

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

videoPlayer.currentPage = 'shows'

onMounted(() => {
    videoPlayer.makeVideoTopRight();
});

let props = defineProps({
    shows: Object,
    episodes: Object,
    showsTrending: Object,
    showsComingSoon: Object,
    poster: String,
    filters: Object,
    can: Object,
    message: String,

});

let search = ref(props.filters.search);

watch(search, throttle(function (value) {
    Inertia.get('/shows', { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));

</script>


