<template>
<div>
    <Head title="Newsroom" />

    <div id="topDiv"></div>
    <div class="place-self-center flex flex-col gap-y-3">
        <div class="bg-white text-black dark:bg-gray-900 dark:text-gray-50 p-5 mb-10">

            <div
                class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                role="alert"
                v-if="props.message"
            >
                                                <span class="font-medium">
                                                    {{props.message}}
                                                </span>
            </div>

            <header class="flex justify-between mb-3 border-b border-gray-800">
                <div class="container mx-auto flex flex-col lg:flex-row items-center justify-between px-4 py-6">



                    <div class="flex flex-col lg:flex-row items-center">
                        <h1 class="text-3xl font-semibold text-center lg:text-left">Welcome to the Newsroom</h1>
                        <ul class="flex ml-0 lg:ml-16 mt-6 mr-6 lg:mt-0 space-x-8" >
                            <li>
                                <Link :href="``" class="text-gray-700 cursor-not-allowed">Categories</Link>
                            </li>
                            <li>
                                <Link :href="``" @click.prevent="scrollToCities" class="text-gray-700 cursor-not-allowed">Cities</Link>
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

            <div class="px-4 w-full">
                This page is only visible to members of the newsroom. Create news articles, share resources, edit stories, and when they are ready the News Producer(s) can publish them!
            </div>




            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div
                        class="relative overflow-x-auto shadow-md sm:rounded-lg"
                    >
                        <div class="flex flex-end flex-wrap-reverse justify-end gap-2 mr-4 mb-4">
                            <Link
                                :href="`/news/create`"><button
                                class="bg-green-600 hover:bg-green-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
                                v-if="props.can.createNewsPost"
                            >Create News</button>
                            </Link>
                        </div>

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
                                    <Link :href="`/news/${news.slug}`"><img :src="`/storage/images/${news.image}`" class="rounded-full h-20 w-20 object-cover"></Link>
                                </div>
                                <div
                                    scope="row"
                                    class="table-cell px-6 py-4 font-medium text-gray-900 dark:text-gray-50 whitespace-nowrap align-middle"
                                >
                                    <Link :href="`/news/${news.slug}`" class="text-lg font-semibold text-blue-800 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-200">{{ news.title }}</Link>
                                </div>
                                <div
                                    scope="row"
                                    class="hidden xl:table-cell px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap align-middle"
                                >
                                    <span :class="{'text-gray-500':news.published_at, 'italic':news.published_at}">{{ formatDate(news.created_at) }}</span>
                                </div>
                                <div class="hidden 2xl:table-cell px-6 py-4">
                                    <span >{{ news.status }}</span>
                                </div>
                                <div class="hidden lg:table-cell px-6 py-4 align-middle space-x-2">
                                    <button
                                        class="bg-green-600 hover:bg-green-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
                                        v-if="props.can.publishNewsPost && !news.published_at"
                                        @click="publish(news.id)"
                                    >Publish</button>
                                    <span v-if="!props.can.publishNewsPost && !news.published_at" class="mr-6 text-gray-500 italic"> not yet published</span>
                                    <span v-if="news.published_at" class="mr-6 text-gray-800 dark:text-white font-semibold">{{ formatDate(news.published_at) }}</span>
                                </div>
                                <div class="hidden lg:table-cell px-6 py-4 align-middle space-x-2">
                                    <Link :href="`/news/${news.slug}/edit`"><button
                                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                                        v-if="news.can.editNewsPost"
                                    >Edit</button>
                                    </Link>
                                    <button
                                        class="px-4 py-2 text-white bg-red-600 hover:bg-red-500 rounded-lg"
                                        @click="destroy(news.id)"
                                        v-if="news.can.deleteNewsPost"
                                    >
                                        Delete
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
import Pagination from "@/Components/Pagination"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useForm } from '@inertiajs/inertia-vue3'
import {onBeforeMount, onMounted, ref, watch} from "vue";
import throttle from "lodash/throttle";
import { Inertia } from "@inertiajs/inertia";
import {useUserStore} from "@/Stores/UserStore";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'newsroom'

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
    Inertia.get('/newsroom', { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));



function publish(id) {
    if (confirm("Are you sure you want to Publish")) {
        // form.put(route('news.publish', id));
        Inertia.put(route('newsroom.publish', { id: id }));

    }
}

function destroy(id) {
    if (confirm("Are you sure you want to Delete")) {
        form.delete(route('news.destroy', id));

    }
}

</script>
