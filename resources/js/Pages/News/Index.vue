<template>

    <Head title="News" />
<!--    <div class="sticky top-0 w-full nav-mask">-->
<!--        <ResponsiveNavigationMenu/>-->
<!--        <NavigationMenu />-->
<!--    </div>-->

<!--    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">-->
    <div id="topDiv"></div>
    <div class="place-self-center flex flex-col gap-y-3">
        <div class="light:bg-light dark:bg-gray-800 light:text-black dark:text-gray-50 p-5 mb-10">



            <header class="flex justify-between mb-3 border-b border-gray-500">
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
                        <h1 class="text-3xl font-semibold">News</h1>
                        <ul class="flex ml-0 lg:ml-16 mt-6 lg:mt-0 space-x-8" >
                            <li>
                                <Link :href="``" class="text-gray-700 cursor-not-allowed">Categories</Link>
                            </li>
                            <li>
                                <Link :href="``" @click.prevent="scrollToCities" class="hover:text-blue-500">Cities</Link>
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

            <div class="flex flex-row justify-between mb-4">
                <div class="mt-4">
                    Events, shows, episodes, movies, news, channel updates, announcements, etc.
                </div>
                <Link
                    :href="`/newsroom`"><button
                    class="bg-yellow-600 hover:bg-yellow-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
                    v-if="props.can.viewNewsroom"
                >Newsroom</button>
                </Link>
            </div>



            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 light:bg-white dark:bg-gray-900 border-b border-gray-200">
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
                                <th scope="col" class="text-right px-6 py-2 space-x-2 space-y-2">

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
                                    <Link :href="`/news/${post.slug}`" class="text-blue-800 uppercase font-semibold text-md hover:text-blue-600 hover:opacity-75 transition ease-in-out duration-150">
                                    <div class="flex flex-row"><img :src="'/storage/images/' + post.image" alt="news cover" class="pr-4 h-6 max-w-[6rem] object-cover ">
                                        {{ post.title }}</div></Link>
                                </td>
                                <td class="text-right px-6 py-2 space-x-2 space-y-2">
                                    <div class="flex flex-row space-x-2">
                                        <Link :href="`/news/${post.slug}/edit`"><button
                                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                                        v-if="post.can.editNewsPost"
                                    >Edit</button>
                                    </Link>
                                    <Button
                                        class="px-4 py-2 text-white bg-red-600 hover:bg-red-500 rounded-lg"
                                        @click="destroy(post.id)"
                                        v-if="post.can.deleteNewsPost"
                                    >
                                        Delete
                                    </Button>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <!-- Paginator -->
                        <Pagination :links="news.links" class="mt-6"/>
                    </div>
                </div>
            </div>


        </div>

    </div>

</template>

<script setup>
import Pagination from "@/Components/Pagination"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useForm } from '@inertiajs/inertia-vue3'
import {onBeforeMount, onMounted, ref, watch} from "vue";
import throttle from "lodash/throttle";
import {Inertia} from "@inertiajs/inertia";
import {useUserStore} from "@/Stores/UserStore";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'news'

onBeforeMount(() => {
    userStore.scrollToTopCounter = 0;
})

function scrollToCities() {
    document.getElementById("cities").scrollIntoView({behavior: "smooth"})
}

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.scrollToTopCounter === 0 ) {
        document.getElementById("topDiv").scrollIntoView()
        userStore.scrollToTopCounter ++;
    }
});

let props = defineProps({
    filters: Object,
    can: Object,
    news: {
        type: Object,
        default: () => ({}),
    },
    message: String,
});

let search = ref(props.filters.search);

let form = useForm({

});




watch(search, throttle(function (value) {
    Inertia.get('/news', { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));

function destroy(id) {
    if (confirm("Are you sure you want to Delete")) {
        form.delete(route('news.destroy', id));

    }
}

</script>
