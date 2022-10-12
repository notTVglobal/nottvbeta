<template>
    <Head title="Shows" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">


            <div v-if="props.can.viewCreator" class="flex justify-end flex-wrap-reverse gap-x-2">
                <Link :href="`/shows/create`"><button
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded disabled:bg-gray-400"
                >Add Episode</button>
                </Link>
                <Link :href="`/dashboard`"><button
                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                >Dashboard</button>
                </Link>
            </div>

            <h1 class="text-3xl font-semibold pb-3"><Link :href="`/shows/${props.showId}/manage`" class="text-blue-800 hover:text-blue-600">{{props.showName}}</Link></h1>
            <h1 class="text-xl font-semibold pb-3">Episodes</h1>

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

                            <div class="flex flex-row justify-end gap-x-4 mr-2">
                                <input v-model="search" type="search" placeholder="Search..." class="border px-2 rounded-lg" />
                            </div>



                            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                                <div class="p-6 bg-white border-b border-gray-200">
                                    <div
                                        class="relative overflow-x-auto shadow-md sm:rounded-lg"
                                    >
                                        <!-- Paginator -->
                                        <Pagination :links="props.episodes.links" class="mb-6"/>

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
                                                    Episode Name
                                                </th>
                                                <th scope="col" class="px-6 py-3">
                                                    Notes
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
                                                v-for="episode in props.episodes.data"
                                                :key="episode.id"
                                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                                            >
                                                <th
                                                    scope="row"
                                                    class="min-w-[8rem] px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                                >
<!--                                                    <img :src="`/storage/images/${show.poster}`" class="rounded-full h-20 w-20 object-cover">-->
                                                    <Link :href="`/shows/${episode.id}`" class="text-blue-800 hover:text-blue-600">
                                                    <img :src="'/storage/images/' + episode.posterName" class="rounded-full h-20 w-20 object-cover"></Link>
                                                </th>
                                                <th
                                                    scope="row"
                                                    class="px-6 py-4 text-xl text-gray-900 dark:text-white whitespace-nowrap"
                                                >
                                                    <Link :href="`/shows/${episode.id}`" class="text-blue-800 hover:text-blue-600">{{ episode.name }}</Link>
                                                </th>
                                                <th
                                                    scope="row"
                                                    class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                                    >
                                                     {{ episode.notes }}
                                                </th>
                                                <td class="px-6 py-4">

                                                </td>
                                                <td v-if="episode.can.editShow" class="px-6 py-4">
                                                    <Link :href="`/shows/${episode.id}/edit`"><button
                                                        class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                                                    >Edit</button>
                                                    </Link>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <!-- Paginator -->
                                        <Pagination :links="episodes.links" class="mt-6"/>
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
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

onMounted(() => {
    videoPlayer.makeVideoTopRight();
});

let props = defineProps({
    episodes: Object,
    showName: String,
    showId: Number,
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


