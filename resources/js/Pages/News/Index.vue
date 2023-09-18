<template>

    <Head title="News" />
<!--    <div class="sticky top-0 w-full nav-mask">-->
<!--        <ResponsiveNavigationMenu/>-->
<!--        <NavigationMenu />-->
<!--    </div>-->

<!--    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">-->
    <div class="place-self-center flex flex-col gap-y-3">
        <div id="topDiv" class="bg-white dark:bg-gray-800 text-black dark:text-gray-50 p-5 mb-10">

            <Message v-if="showMessage" @close="showMessage = false" :message="props.message"/>

            <header class="w-full mx-auto flex flex-wrap justify-between mb-3 pb-3 border-b border-gray-500 space-x-2 space-y-3">
                    <div></div>
                    <NewsHeader>News</NewsHeader>
                    <div class="flex items-center">
                        <div class="relative">
                            <input v-model="search" type="search" class="bg-gray-50 text-black text-sm rounded-full
                            focus:outline-none focus:shadow w-64 pl-8 px-3 py-1" placeholder="Search...">
                            <div class="absolute top-0 flex items-center h-full ml-2">
                                <svg class="fill-current text-gray-400 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M456.69 421.39 362.6 327.3a173.81 173.81 0 0 0 34.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 0 0 327.3 362.6l94.09 94.09a25 25 0 0 0 35.3-35.3ZM97.92 222.72a124.8 124.8 0 1 1 124.8 124.8 124.95 124.95 0 0 1-124.8-124.8Z"/></svg>

                            </div>
                        </div>
                    </div>

            </header>

            <div class="flex flex-wrap justify-between mb-4">
                <div class="my-4">
                    Events, shows, episodes, movies, news, channel updates, announcements, etc.
                </div>
                <NewsHeaderButtons :can="can"/>
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
                                <td class="text-gray-900">
                                    {{ formatDate(post.published_at) }}
                                </td>
                                <td class="text-right px-6 py-2 space-x-2 space-y-2">
                                    <div class="flex flex-row space-x-2">
                                        <Link :href="`/news/${post.slug}/edit`"><button
                                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                                        v-if="post.can.editNewsPost"
                                    >Edit</button>
                                    </Link>
                                    <button
                                        class="px-4 py-2 text-white bg-red-600 hover:bg-red-500 rounded-lg"
                                        @click="destroy(post.id)"
                                        v-if="post.can.deleteNewsPost"
                                    >
                                        Delete
                                    </button>
                                    </div>
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
import { onBeforeMount, onMounted, ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { useForm } from '@inertiajs/inertia-vue3'
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore";
import throttle from "lodash/throttle";
import Pagination from "@/Components/Pagination"
import Message from "@/Components/Modals/Messages";
import NewsHeader from "@/Components/News/NewsHeader.vue";
import NewsHeaderButtons from "@/Components/News/NewsHeaderButtons.vue";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'news'



function scrollToCities() {
    document.getElementById("cities").scrollIntoView({behavior: "smooth"})
}

// onBeforeMount(() => {
//     userStore.scrollToTopCounter = 0;
// })

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()
    // if (userStore.scrollToTopCounter === 0 ) {

    //     userStore.scrollToTopCounter ++;
    // }
});

let props = defineProps({
    filters: Object,
    can: Object,
    news: Object,
    message: String,
});

let form = useForm({
    id: '',
});

let search = ref(props.filters.search);

watch(search, throttle(function (value) {
    Inertia.get('/news', { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));

function destroy(id) {
    if (confirm("Are you sure you want to Delete")) {
        form.delete(route('news.destroy', ['news', id]));

    }
}

let showMessage = ref(true);

</script>
