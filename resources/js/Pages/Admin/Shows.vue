<template>
    <Head title="Shows" />

    <div id="topDiv"></div>
    <div class="place-self-center flex flex-col gap-y-3">
        <div class="bg-white text-black dark:bg-gray-900 dark:text-white p-5 mb-10">

            <div class="flex justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-semibold pb-3">Shows</h1>
                </div>
                <Link :href="`/dashboard`"><button
                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                >Dashboard</button>
                </Link>
            </div>
            <div class="flex justify-end mb-6">
                <div>
                    <input v-model="search" type="search" placeholder="Search..." class="text-black border px-2 rounded-lg mb-6" />
                </div>
            </div>


            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

                            <div
                                class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                                role="alert"
                                v-if="props.message"
                            >
                                <span class="font-medium">
                                    {{props.message}}
                                </span>
                            </div>


                            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200">
                                    <div
                                        class="relative shadow-md sm:rounded-lg"
                                    >

                                            <div>
                                                <!-- Paginator -->
                                                <Pagination :data="shows" class=""/>
                                            </div>


                                        <table
                                            class="w-full text-sm text-left text-gray-500 dark:text-gray-400 overflow-x-auto"
                                        >
                                            <thead
                                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                                            >
                                            <tr class="">
                                                <th scope="col" class="min-w-[8rem] px-6 py-3">
                                                    Poster
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Show Name
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Show Runner
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Team
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    # of Episodes
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Status
                                                </th>
                                                <th v-if="props.can.viewCreator" scope="col" class="px-6 py-3">
                                                    <!--Manage/Edit-->
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr
                                                v-for="show in props.shows.data"
                                                :key="show.id"
                                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                                            >
                                                <th
                                                    scope="row"
                                                    class="min-w-[8rem] px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                                >
<!--                                                    <img :src="`/storage/images/${show.poster}`" class="rounded-full h-20 w-20 object-cover">-->
                                                    <Link :href="`/shows/${show.slug}`" class="text-blue-800 hover:text-blue-600">
                                                    <img :src="'/storage/images/' + show.poster" class="rounded-full h-20 w-20 object-cover"></Link>
                                                </th>
                                                <th
                                                    scope="row"
                                                    class="px-6 py-4 text-xl text-gray-900 dark:text-white whitespace-nowrap"
                                                >
                                                    <Link :href="`/shows/${show.slug}`" class="text-blue-800 hover:text-blue-600 dark:text-blue-200 dark:hover:text-blue-400">{{ show.name }}</Link>
                                                </th>
                                                <th
                                                    scope="row"
                                                    class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                                    >
                                                     {{ show.showRunnerName }}
                                                </th>
                                                <th
                                                    scope="row"
                                                    class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                                >
                                                    <Link :href="`/teams/${show.teamSlug}`" class="text-blue-800 hover:text-blue-600 dark:text-blue-200 dark:hover:text-blue-400">{{ show.teamName }}</Link>
                                                </th>
                                                <th
                                                    scope="row"
                                                    class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                                >
                                                    {{ show.totalEpisodes }}
                                                </th>
                                                <th
                                                    scope="row"
                                                    class="px-6 py-4 whitespace-nowrap"
                                                >
                                                    <div v-if="show.statusId===1" class="font-semibold text-pink-500">
                                                        {{ show.status }}
                                                    </div>
                                                    <div v-if="show.statusId===2" class="font-semibold text-green-600">
                                                        {{ show.status }}
                                                    </div>
                                                    <div v-if="show.statusId===3" class="font-medium text-orange-400">
                                                        {{ show.status }}
                                                    </div>
                                                    <div v-if="show.statusId===4" class="text-gray-500">
                                                        {{ show.status }}
                                                    </div>
                                                    <div v-if="show.statusId===5" class="font-semibold py-1 px-2 w-fit rounded-md bg-black text-gray-50">
                                                        {{ show.status }}
                                                    </div>
                                                    <div v-if="show.statusId===6" class="font-italic text-gray-500">
                                                        {{ show.status }}
                                                    </div>
                                                    <div v-if="show.statusId===7" class="font-medium font-italic text-red-600">
                                                        {{ show.status }}
                                                    </div>
                                                </th>
                                                <td class="px-6 py-4 space-x-2">
                                                    <Link v-if="show.can.viewShow" :href="`/shows/${show.slug}/manage`"><button
                                                        class="px-4 py-2 mb-2 text-white bg-purple-600 hover:bg-purple-500 rounded-lg"
                                                    >Manage</button>
                                                    </Link>
                                                    <Link v-if="show.can.editShow" :href="`/shows/${show.slug}/edit`"><button
                                                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                                                    >Edit</button>
                                                    </Link>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <!-- Paginator -->
                                        <Pagination :data="shows" class="pb-6"/>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <div class="flex items-center">
<!--               <Link :href="`/shows/${show.id}`" class="text-indigo-600 hover:text-indigo-900">Link to a show</Link>-->

            </div>
        </div>
    </div>

</template>

<script setup>
import Pagination from "@/Components/Pagination"
import {onBeforeMount, onMounted, ref, watch} from "vue"
import {Inertia} from "@inertiajs/inertia"
import throttle from "lodash/throttle"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import {useUserStore} from "@/Stores/UserStore";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'admin'

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
    teamName: String,
    poster: String,
    filters: Object,
    can: Object,
    message: String,

});

let search = ref(props.filters.search);

watch(search, throttle(function (value) {
    Inertia.get('/admin/shows', { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));

</script>


