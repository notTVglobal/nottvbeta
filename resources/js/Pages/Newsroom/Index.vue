<template>
<div>
    <Head title="Newsroom" />

    <div class="place-self-center flex flex-col gap-y-3">
        <div class="bg-white text-black p-5 mb-10">

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

                    <div id="topDiv" class="flex flex-col lg:flex-row items-center">
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




            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
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
                        <!-- Paginator -->
                        <Pagination :links="news.links" class="mb-6"/>

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
                            </div>
                            </div>
                            <div class="table-row-group">
                            <div
                                v-for="news in news.data"
                                :key="news.id"
                                class="table-row bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                            >
                                <div
                                    scope="row"
                                    class="hidden md:table-cell min-w-[8rem] px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                >
                                    <Link :href="`/news/${news.slug}`"><img :src="`/storage/images/${news.image}`" class="rounded-full h-20 w-20 object-cover"></Link>
                                </div>
                                <div
                                    scope="row"
                                    class="table-cell px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                >
                                    <Link :href="`/news/${news.slug}`" class="text-lg text-blue-800 hover:text-blue-600">{{ news.title }}</Link>
                                </div>
                                <div
                                    scope="row"
                                    class="hidden xl:table-cell px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                >
                                    {{ formatDate(news.created_at) }}
                                </div>
                                <div class="hidden 2xl:table-cell px-6 py-4">
                                    <span >{{ news.status }}</span>
                                </div>
                                <div class="hidden lg:table-cell px-6 py-4">
                                    <button
                                        class="bg-green-600 hover:bg-green-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
                                        v-if="props.can.createNewsPost && !news.published_at"
                                        @click="publish(news.id)"
                                    >Publish</button>
                                    <span v-if="news.published_at">{{ formatDate(news.published_at) }}</span>
                                </div>

                            </div>
                            </div>
                        </div>
                        <!-- Paginator -->
                        <Pagination :links="news.links" class="mt-6"/>
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
import { useChatStore } from "@/Stores/ChatStore.js"
import { useForm } from '@inertiajs/inertia-vue3'
import { onMounted, ref, watch } from "vue";
import throttle from "lodash/throttle";
import { Inertia } from "@inertiajs/inertia";

let videoPlayerStore = useVideoPlayerStore()
let chat = useChatStore()

videoPlayerStore.currentPage = 'newsroom'

let scrollToTopDivCounter = 0

function scrollToTopDiv() {
    if (scrollToTopDivCounter = 0 ) {
        document.getElementById("topDiv").scrollIntoView()
        scrollToTopDivCounter ++;
    }
}

function scrollToCities() {
    document.getElementById("cities").scrollIntoView({behavior: "smooth"})
}

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    scrollToTopDiv()
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



function publish(id) {
    if (confirm("Are you sure you want to Publish")) {
        // form.put(route('news.publish', id));
        Inertia.put(route('newsroom.publish', { id: id }));

    }
}

</script>
