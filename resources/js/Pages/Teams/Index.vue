<template>
    <Head title="Teams" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>


    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">


<!--    <div class="bg-white rounded text-black p-5 mb-10 py-20 w-3/4">-->
        <div class="flex justify-between mb-6">
            <div class="grid grid-cols-1 grid-rows-2">
                <h1 class="text-3xl font-semibold">Teams</h1>
                <Link href="/teams/create" class="text-blue-500 text-sm">New Team</Link>
            </div>
            <div class="grid grid-cols-1 grid-rows-2">
                <div class="justify-self-end mb-4">
                    <Link :href="`/dashboard`"><button
                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                    >Dashboard</button>
                    </Link>
                </div>
                <input v-model="search" type="search" placeholder="Search..." class="border px-2 rounded-lg" />
            </div>
        </div>



        <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">


                        <table class="min-w-full divide-y divide-gray-200">
                            <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="team in teams.data" :key="team.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                <Link :href="`/teams/${team.id}`" class="text-indigo-600 hover:text-indigo-900">{{ team.name }}</Link>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <Link :href="`/teams/${team.id}/edit`" class="text-indigo-600 hover:text-indigo-900">Edit</Link>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <!-- Paginator -->
                        <Pagination :links="teams.links" class="mt-6"/>

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
import { ref, watch } from "vue"
import {Inertia} from "@inertiajs/inertia"
import throttle from "lodash/throttle"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

videoPlayer.class = "videoTopRight"
videoPlayer.videoContainerClass = "videoContainerTopRight"
videoPlayer.fullPage = false
chat.class = "chatSmall"

let props = defineProps({
    teams: Object,
    filters: Object,
    can: Object
});

let search = ref(props.filters.search);

watch(search, throttle(function (value) {
    Inertia.get('/teams', { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));
</script>


