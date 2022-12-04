<template>
    <Head title="Teams" />

    <div class="place-self-center flex flex-col gap-y-3">
        <div class="bg-white text-black p-5 mb-10">

            <div v-if="props.can.viewCreator" class="flex justify-end flex-wrap-reverse gap-x-2">

                <Link v-if="can.createTeam" :href="`/teams/create`"><button
                    class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded disabled:bg-gray-400"
                >Add Team</button>
                </Link>
                <Link :href="`/dashboard`"><button
                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                >Dashboard</button>
                </Link>
            </div>

            <h1 class="text-3xl font-semibold pb-3">Teams</h1>

            <div class="flex flex-row justify-end gap-x-4">
                <input v-model="search" type="search" placeholder="Search..." class="border px-2 rounded-lg" />
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
                            <div class="p-6 bg-white border-b border-gray-200">
                                <!-- Paginator -->
                                <Pagination :links="teams.links" class="mb-6"/>
                                <div
                                    class="relative overflow-x-auto shadow-md sm:rounded-lg"
                                >


                                    <table
                                        class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
                                    >
                                        <thead
                                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                                        >
                                        <tr>
                                            <th scope="col" class="min-w-[8rem] px-6 py-3">
                                                Logo
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Team Name
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Team Owner
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                # of Members
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                # of Shows
                                            </th>
                                            <th v-if="props.can.viewCreator" scope="col" class="px-6 py-3">
                                                <!--Manage/Edit-->
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr
                                            v-for="team in teams.data"
                                            :key="team.id"
                                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                                        >
                                            <th
                                                scope="row"
                                                class="min-w-[8rem] px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                            >
<!--                                                <img :src="`/storage/${team.logo}`" class="rounded-full h-20 w-20 object-cover">-->
                                                <img :src="`/storage/images/` + team.logo" class="rounded-full h-20 w-20 object-cover">
                                            </th>
                                            <th
                                                scope="row"
                                                class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                            >
                                                <Link :href="`/teams/${team.slug}`" class="text-blue-800 hover:text-blue-600">{{ team.name }}</Link>
                                            </th>
                                            <th
                                                scope="row"
                                                class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                            >
                                                {{ team.teamOwner }}
                                            </th>
                                            <td
                                                scope="row"
                                                class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                            >
                                                {{ team.memberSpots }}
                                            </td>
                                            <td
                                                scope="row"
                                                class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                            >
                                                {{ team.totalShows }}
                                            </td>
                                            <td  class="px-6 py-4 space-x-2">
                                                <Link v-if="team.can.viewTeam" :href="`/teams/${team.slug}/manage`"><button
                                                    class="px-4 py-2 text-white bg-purple-600 hover:bg-purple-500 rounded-lg"
                                                >Manage</button>
                                                </Link>
                                                <Link v-if="team.can.editTeam" :href="`/teams/${team.slug}/edit`"><button
                                                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                                                >Edit</button>
                                                </Link>
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
                </div>
            </div>
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

let videoPlayerStore = useVideoPlayerStore()
let chat = useChatStore()

videoPlayerStore.currentPage = 'admin'

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
});

let props = defineProps({
    teams: Object,
    filters: Object,
    can: Object,
    message: String,
});

let search = ref(props.filters.search);

watch(search, throttle(function (value) {
    Inertia.get('/teams', { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));
</script>


