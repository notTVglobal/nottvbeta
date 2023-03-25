<template>
    <Head title="Shows" />


    <div class="place-self-center flex flex-col gap-y-3 w-full overscroll-x-none">
        <div class="bg-gray-900 text-white p-5 mb-10">
            <div id="topDiv"></div>

            <Message v-if="showMessage" @close="showMessage = false" :message="props.message"/>

            <header class="flex justify-between mb-3 border-b border-gray-800">
                <div class="container mx-auto flex flex-col lg:flex-row items-center justify-between px-4 py-6">

                    <div class="flex flex-col lg:flex-row items-center">
                        <h1 class="text-3xl font-semibold">Shows</h1>
                        <ul class="flex ml-0 lg:ml-16 mt-6 lg:mt-0 space-x-8" >
                            <li>
                                <button :href="``" class="text-gray-700 cursor-not-allowed">Categories</button>
                            </li>
                            <li>
                                <button :href="``" @click.prevent="scrollToNewEpisodes" class="hover:text-blue-800">New Episodes</button>
                            </li>
                            <li>
                                <button :href="``" @click.prevent="scrollToComingSoon" class="hover:text-blue-800">Coming Soon</button>
                            </li>
                        </ul>
                    </div>
                    <div class="flex items-center mt-6 lg:mt-0">
                        <div class="relative">
                            <input v-model="search" type="search" class="bg-gray-50 text-black text-sm rounded-full
                            focus:outline-none focus:shadow w-64 pl-8 px-3 py-1" placeholder="Search...">
                            <div class="absolute top-0 flex items-center h-full ml-2">
                                <svg class="fill-current text-gray-400 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M456.69 421.39 362.6 327.3a173.81 173.81 0 0 0 34.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 0 0 327.3 362.6l94.09 94.09a25 25 0 0 0 35.3-35.3ZM97.92 222.72a124.8 124.8 0 1 1 124.8 124.8 124.95 124.95 0 0 1-124.8-124.8Z"/></svg>

                            </div>
                        </div>
                    </div>
                </div>

            </header>

            <main class="py-8">


                <div class="container mx-auto px-4 border-b border-gray-800 pb-16 max-w-calc[100%-96rem]">
                    <h2 class="text-purple-500 uppercase tracking-wide font-semibold text-2xl">Popular Shows</h2>
                    <div class="popular-shows text-sm grid grid-cols-1 md:grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-12 pb-12 justify-items-center">

                        <div v-for="show in shows.data"
                             :key="show.id"
                             class="show mt-8 ">
                            <Link :href="`/shows/${show.slug}`" class="hover:text-blue-400 hover:opacity-75 transition ease-in-out duration-150">
                                <div class="relative inline-block">
<!--                                    <SingleImage :image="show.image" :alt="'show cover'" class="min-h-[24rem]  max-h-[24rem] min-w-[16rem] w-fit object-cover"/>-->
                                    <SingleImage :image="show.image" :alt="'show cover'" class="h-96 min-w-[16rem] w-64 mb-6 object-cover lg:mb-0 m-auto lg:m-0"/>
                                </div>
                                <div class="block text-base text-xl font-semibold leading-tight mt-4 max-w-[16rem]">{{ show.name }}</div>
                                <div class="text-gray-400 text-lg mt-1">{{ show.categoryName }} &middot;
                                    <span v-if="show.last_release_year">({{show.last_release_year}})</span>
                                    <span v-if="!show.last_release_year">({{show.first_release_year}})</span></div>
                                <div class="text-gray-400 mt-1 font-thin">{{ show.categorySubName }}</div>
                            </Link>
                        </div>

                    </div>
                    <!-- Paginator -->
                    <Pagination :data="shows" class=""/>
                </div>

                <div class="flex flex-col lg:flex-row my-10">

                    <div class="newest-episodes w-full lg:w-3/4 mr-0 md:mr-16 lg:mr-32">
                        <h2 id="new-episodes" class="text-purple-500 uppercase tracking-wide font-semibold text-2xl">Newest Episodes</h2>
                        <div class="recently-reviewed-container space-y-12 mt-8">
                            <div v-for="episode in newestEpisodes.data"
                                 :key="episode.id"
                                 class="show bg-gray-800 rounded-lg shadow-md flex px-6 py-6">

                                <Link :href="`/shows/${episode.showSlug}/episode/${episode.slug}`" class="hover:text-blue-400 hover:opacity-75 transition ease-in-out duration-150">


                                    <div class="relative flex flex-row">
<!--                                        <img :src="'/storage/images/' + episode.poster" alt="show cover" class="h-32 md:h-64 md:min-w-[8rem] w-24 md:w-48 object-cover">-->
                                        <SingleImage :image="episode.image" :alt="'episode cover'" class="h-32 md:h-64 md:min-w-[16rem] w-24 md:w-48 object-cover"/>

                                        <div class="ml-12 block text-lg font-semibold leading-tight mt-4 ">
<!--                                            <div class="text-gray-400 font-light text-xs mt-1 ">Released on {{ episode.release_date }}</div>-->
        <!--                                    <Link :href="`/shows/${episode.showSlug}/episode/${episode.slug}`" class="block text-lg font-semibold leading-tight hover:text-gray-400 mt-4">-->
                                                {{ episode.showName }}
        <!--                                </Link>-->

                                            <div class="text-gray-400 font-light text-sm">{{ episode.name }}</div>

                                            <p class="mt-4 pr-4 text-gray-300 lg:block">
                                                {{ episode.description}}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-4 w-full justify-end text-yellow-300">{{episode.categoryName}}</div>
                                    <div class="w-full justify-end text-yellow-500 font-thin">{{episode.categorySubName}}</div>
                                </Link>


                            </div>

                        </div>
                    </div>

                    <div class="most-anticipated lg:w-1/4 mt-12 lg:mt-0">
                        <h2 class="text-purple-500 uppercase tracking-wide font-semibold text-2xl">Most Anticipated</h2>
                        <div class="most-anticipated-container space-y-10 mt-8">
                            <div v-for="show in mostAnticipated.data"
                                 :key="show.id"
                                 class="show flex">
                                <Link :href="`/shows/${show.slug}`">
                                    <SingleImage :image="show.image" :alt="'show cover'" class="h-24 min-w-[4rem] w-16 object-cover hover:opacity-75 transition ease-in-out duration-150"/>
                                </Link>
                                <div class="ml-4">
                                    <Link :href="`/shows/${show.slug}`" class="hover:text-gray-300">{{ show.name }}</Link>
                                    <div class="text-gray-400 text-sm mt-1">{{ show.categoryName }}</div>
                                    <div class="text-gray-400 text-sm font-thin mt-1">{{ show.categorySubName }}</div>
                                </div>
                            </div>
                        </div>

                        <h2 id="coming-soon" class="text-purple-500 uppercase tracking-wide font-semibold mt-16 text-2xl">Coming Soon</h2>
                        <div class="most-anticipated-container space-y-10 mt-8">

                            <div v-for="show in comingSoon.data"
                                 :key="show.id"
                                 class="show flex">
                                <Link :href="`/shows/${show.slug}`">
                                    <SingleImage :image="show.image" :alt="'show cover'" class="h-24 min-w-[4rem] w-16 object-cover hover:opacity-75 transition ease-in-out duration-150"/>
                                </Link>
                                <div class="ml-4">
                                    <Link :href="`/shows/${show.slug}`" class="hover:text-gray-300">{{ show.name }}</Link>
                                    <div class="text-gray-400 text-sm mt-1">{{ show.categoryName }}</div>
                                    <div class="text-gray-400 text-sm font-thin mt-1">{{ show.categorySubName }}</div>
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
import { onBeforeMount, onMounted, ref, watch } from "vue"
import { Inertia } from "@inertiajs/inertia"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore.js"
import Pagination from "@/Components/PaginationDark"
import throttle from "lodash/throttle"
import Message from "@/Components/Modals/Messages";
import SingleImage from "@/Components/Multimedia/SingleImage";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'shows'

function scrollToNewEpisodes() {
    document.getElementById("new-episodes").scrollIntoView({behavior: "smooth"})
}
function scrollToComingSoon() {
    document.getElementById("coming-soon").scrollIntoView({behavior: "smooth"})
}

onBeforeMount(() => {
    userStore.scrollToTopCounter = 0;
})

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.scrollToTopCounter === 0 ) {
        document.getElementById("topDiv").scrollIntoView()
        userStore.scrollToTopCounter ++;
    }
});

let props = defineProps({
    shows: Object,
    newestEpisodes: Object,
    mostAnticipated: Object,
    comingSoon: Object,
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

let showMessage = ref(true);

</script>


