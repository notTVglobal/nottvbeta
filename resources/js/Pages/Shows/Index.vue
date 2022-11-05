<template>
    <Head title="Shows" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black dark:bg-gray-900 dark:text-white p-5 mb-10">

            <div class="flex justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-semibold pb-3">Shows</h1>
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
                                        class="relative overflow-x-auto shadow-md sm:rounded-lg"
                                    >
                                        <div class="flex flex-row justify-between gap-x-4 mr-2">
                                            <div>
                                                <!-- Paginator -->
                                                <Pagination :links="props.shows.links" class=""/>
                                            </div>
                                            <div>
                                                <input v-model="search" type="search" placeholder="Search..." class="border px-2 rounded-lg mb-6" />
                                            </div>
                                        </div>

                                        <table
                                            class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
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
                                                    Edit
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
                                                    <div v-if="show.statusId===5" class="font-semibold text-gray-900">
                                                        {{ show.status }}
                                                    </div>
                                                    <div v-if="show.statusId===6" class="font-italic text-gray-500">
                                                        {{ show.status }}
                                                    </div>
                                                    <div v-if="show.statusId===7" class="font-medium font-italic text-red-600">
                                                        {{ show.status }}
                                                    </div>
                                                </th>
                                                <td class="px-6 py-4">
                                                    <Link :href="`/shows/${show.slug}/edit`"><button
                                                        v-if="show.can.editShow"
                                                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                                                    >Edit</button>
                                                    </Link>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <!-- Paginator -->
                                        <Pagination :links="shows.links" class="mt-6"/>
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
import {onMounted, ref, watch} from "vue"
import {Inertia} from "@inertiajs/inertia"
import throttle from "lodash/throttle"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/Navigation/NavigationMenu"

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

onMounted(() => {
    videoPlayer.makeVideoTopRight();
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
    Inertia.get('/shows', { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));

</script>


