<template>
    <Head title="Dashboard" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">

        <div class="bg-white rounded text-black dark:text-white dark:bg-gray-900 p-5 mb-10">

            <div class="flex justify-between mb-3">


                <h1 class="text-3xl font-semibold pb-3">Dashboard</h1>

            </div>

                <div class="flex flex-end flex-wrap-reverse justify-end gap-2 mr-4 mb-4">
                    <Link
                        :href="`/news/upload`"><button
                        class="bg-green-600 hover:bg-green-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
                        disabled
                    >Share News</button>
                    </Link>
                    <Link
                        :href="`/invite`"><button
                        class="bg-orange-600 hover:bg-orange-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
                    >Invite Creator</button>
                    </Link>
                    <Link :href="`/golive`" hidden><button
                        class="bg-red-600 hover:bg-red-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
                    >Go Live</button>
                    </Link>
                </div>



            <div
                class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                role="alert"
                v-if="props.message"
            >
                                <span class="font-medium">
                                    {{props.message}}
                                </span>
            </div>

            <div v-show="can.viewAdmin" class="bg-gray-300 dark:bg-gray-900 rounded pb-8 p-3 mb-6 mx-2 border-b border-2">
                <div class="font-semibold text-xl pb-2">Administrator only links</div>
                <div class="flex flex-wrap md:flex-row justify-items-start gap-2">
                    <!--disable button if ! admin-->
                    <Link :href="`/users`"><button
                        class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 col-span-1 rounded disabled:bg-gray-400"
                    >All Users</button>
                    </Link>
                    <!--disable button if ! admin-->
                    <Link :href="`/admin/channels`"><button
                        class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 rounded disabled:bg-gray-400"
                    >All Channels</button>
                    </Link>
                    <!--disable button if ! admin-->
                    <Link :href="`/teams`"><button
                        class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 rounded disabled:bg-gray-400"
                    >All Teams</button>
                    </Link>

                    <!--disable button if ! admin-->
                    <Link :href="`/video`"><button
                        class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 rounded disabled:bg-gray-400"
                    >MistServer API</button>
                    </Link>

                    <!--disable button if ! admin-->
                    <Link :href="`/image`"><button
                        class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 rounded disabled:bg-gray-400"
                    >Image Uploader</button>
                    </Link>

                    <!--disable button if ! admin-->
                    <Link :href="`/admin/settings`"><button
                        class="bg-blue-600 hover:bg-blue-500 text-white mt-1 p-2 rounded disabled:bg-gray-400"
                    >Settings</button>
                    </Link>
                    <Link
                        :href="`/movies/create`"><button
                        class="bg-blue-600 hover:bg-blue-500 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
                    >Upload Movie</button>
                    </Link>
                </div>

            </div>

            <div class="bg-gray-300 dark:bg-gray-900 rounded pb-8 p-3 mb-6 mx-2 border-b border-2">
                <div class="font-semibold text-xl pb-2">Stats</div>
                <div class="flex flex-wrap md:flex-row justify-center gap-2">

                    <div class="px-4 py-4 w-1/4 h-48 bg-gray-400 text-black rounded shadow-md text-center">
                        <div class="font-semibold">
                            Total Storage
                        </div>
                        <div class="">
                            Graph here
                        </div>
                    </div>

                    <div class="px-4 py-4 w-1/4 h-48 bg-gray-400 text-black rounded shadow-md text-center">
                        <div class="font-semibold">
                            Total Subscribers
                        </div>
                        <div>
                            Graph here
                        </div>
                    </div>

                    <div class="px-4 py-4 w-1/4 h-48 bg-gray-400 text-black rounded shadow-md text-center">
                        <div class="font-semibold">
                            Total Creators
                        </div>
                        <div>
                            Graph here
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap md:flex-row justify-center gap-2 mt-2">
                    <div class="px-4 py-4 w-1/4 h-48 bg-gray-400 text-black rounded shadow-md text-center">
                        <div class="font-semibold">
                            Avg. View Time
                        </div>
                        <div>
                            Graph here
                        </div>
                    </div>

                    <div class="px-4 py-4 w-1/4 h-48 bg-gray-400 text-black rounded shadow-md text-center">
                        <div class="font-semibold">
                            Daily Peak Bandwidth
                        </div>
                        <div>
                            Graph here
                        </div>
                    </div>

                    <div class="px-4 py-4 w-1/4 h-48 bg-gray-400 text-black rounded shadow-md text-center">
                        <div class="font-semibold">
                            Today's Top Show
                        </div>
                        <div>
                            Display poster here
                        </div>
                    </div>

                </div>
            </div>

            <section class="grid grid-cols-1 lg:grid-cols-3 gap-4 my-3 mx-2 m-auto text-black">
                <div class="p-5 bg-gray-200 dark:bg-gray-800 rounded">
                    <div class="mb-3 grid grid-cols-1">
                        <div class="mb-1 font-semibold text-xl justify-self-start dark:text-gray-50">Open Assignments</div>
                    </div>
                    <div class="mb-3 bg-orange-300 py-1 px-2 text-xs font-semibold text-red-800">
                        In development. Not currently working.
                    </div>
                    <div class="ml-3">
                        <li class="text-blue-800 hover:text-blue-400 dark:text-blue-100 dark:hover:text-blue-400">
                            Assignments list goes here
                        </li>
                    </div>
                </div>

                <div class="p-5 bg-gray-200 dark:bg-gray-800 rounded relative">
                    <div class="mb-3 flex justify-between">
                        <div class="mb-1 font-semibold text-xl dark:text-gray-50">My Teams</div>

                        <div v-if="can.createTeam" class="">
                            <Link :href="`/teams/create`"><button
                                class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 text-xs rounded disabled:bg-gray-400"
                            >New Team</button>
                            </Link>
                        </div>
                    </div>
                    <div v-if="props.teams.data == 0" class="italic"> You have no teams.</div>
                    <div
                        v-for="team in teams.data"
                        :key="team.id"
                        class="border-b bg-white hover:bg-blue-300 dark:bg-gray-600 dark:border-gray-700 dark:hover:bg-blue-800 inset-x-0 bottom-0"
                    >
                        <Link
                            @click="videoPlayer.makeVideoTopRight()"
                            :href="`/teams/${team.slug}/manage`"

                            class="text-blue-800 hover:text-blue-900 dark:text-blue-100 dark:hover:text-white"><p class="px-2 py-1">
                            {{ team.name }}
                        </p></Link>
                    </div>
                    <div class="flex flex-row justify-between bottom-0 align-items-bottom py-2 px-2">
                        <!-- Paginator -->
                        <div><Pagination v-if="teams.links > 3" :links="teams.links" class="mt-6 absolute inset-x-0 bottom-0 py-2 px-2 "/></div>

                        <div>
                        <Popper
                            hover openDelay="50" closeDelay="50"
                            arrow
                            ><template #content>
                                <p class="text-xl mb-2 font-semibold">🎉 Start a new team</p>
                                <p class="">Teams manage shows, </p>
                                <p class="">even if you're a solo creator</p>

                            </template>
                            <button><font-awesome-icon
                                icon="fa-solid fa-question"
                                class="dark:text-white dark:hover:text-blue-400 hover:text-blue-400 mt-6 absolute bottom-0 text-right pr-4 py-2 "
                            /></button>
                        </Popper>
                        </div>

                    </div>


                </div>

                <div class="p-5 bg-gray-200 dark:bg-gray-800 rounded relative">
                    <div class="mb-3 grid grid-cols-1">
                        <div class="mb-1 font-semibold text-xl justify-self-start dark:text-gray-50">My Shows</div>
                    </div>
                    <div v-if="props.shows.data == 0" class="italic"> You have no shows.</div>
                    <div
                        v-for="show in shows.data"
                        :key="show.id"
                        class="border-b bg-white hover:bg-blue-300 dark:bg-gray-600 dark:border-gray-700 dark:hover:bg-blue-800 inset-x-0 bottom-0"
                    >
                        <Link
                            @click="videoPlayer.makeVideoTopRight()"
                            :href="`/shows/${show.slug}/manage`"
                            class="text-blue-800 hover:text-blue-900 dark:text-blue-100 dark:hover:text-white"><p class="px-2 py-1">
                            {{ show.name }}
                        </p></Link>
                    </div>
                    <div class="flex flex-row justify-between bottom-0 align-items-bottom py-2 px-2">
                        <!-- Paginator -->
                        <div><Pagination v-if="shows.links > 3" :links="shows.links" class="mt-6 absolute inset-x-0 bottom-0 py-2 px-2 "/></div>
                        <div>
                        <Popper
                            hover openDelay="50" closeDelay="50"
                            arrow
                        ><template #content class="space-y-3">
                            <p class="text-xl font-semibold mb-2">🍿 These are your shows </p>
                            <p class="">Join or create a team to start a show.</p>
                        </template>
                            <button><font-awesome-icon
                                icon="fa-solid fa-question"
                                class="dark:text-white dark:hover:text-blue-400 hover:text-blue-400 mt-6 absolute bottom-0 text-right pr-4 py-2 "
                            /></button>
                        </Popper>
                        </div>
                    </div>
                </div>


            </section>

            <div class="mt-6 h-0.5 bg-gray-800"></div>

            <section class="grid grid-cols-1 mt-6 gap-2">
                <div class="font-semibold text-2xl text-gray-800 dark:text-white px-2">
                    Account Summary
                </div>

                <div class="mb-3 bg-orange-300 py-1 px-2 text-xs font-semibold text-red-800">
                    In development. Not currently working.
                </div>

                <div class="border-2">
                    <div class="grid justify-items-stretch grid-cols-3 ">
                        <div class="bg-gray-800 text-white text-sm p-2 col-span-3">Membership: 000000</div>
                    </div>
                    <table class="w-full">
                        <thead class="">
                        <td class="bg-blue-400 font-semibold text-sm text-black px-2 mb-3">Account Name</td>
                        <td class="bg-blue-400 px-2 mb-3 text-right font-semibold text-sm text-black">Balance</td>
                        </thead>
                        <tr class="border-b border-1 border-gray-100">
                            <td class="px-2 col-span-2 py-2">Chequing</td>
                            <td class="px-2 text-right">0.00</td>
                        </tr>
                        <tr class="border-b border-1 border-gray-100">
                            <td class="px-2 col-span-2 py-2">Equity Shares</td>
                            <td class="px-2 text-right">10.00</td>
                        </tr>
                        <tr class="border-b border-1 border-gray-100">
                            <td class="px-2 col-span-2 py-2">Public Good Fund</td>
                            <td class="px-2 text-right">0.00</td>
                        </tr>
                        <tr class="border-b border-1 border-gray-100">
                            <td class="px-2 col-span-2 py-2">Production Fund for Members</td>
                            <td class="px-2 text-right">0.00</td>
                        </tr>
                        <tr class="border-b border-1 border-gray-100">
                            <td class="px-2 col-span-2 py-2">News Fund</td>
                            <td class="px-2 text-right">0.00</td>
                        </tr>
                    </table>

                        <table class="w-full">
                            <thead class="">
                                <td class="bg-blue-400 font-semibold text-sm text-black px-2">Team Shares</td>
                                <td class="bg-blue-400 px-2 text-right font-semibold text-sm text-black">Balance</td>
                            </thead>
                            <tr v-for="team in teams.data"
                                :key="team.id"
                                class="border-b border-1 border-gray-100">
                                <td class="px-2 py-2">{{ team.name }}</td>
                                <td class="px-2 text-right">0.00</td>
                            </tr>
                        </table>

                    </div>


            </section>

        </div>
    </div>

</template>

<script setup>
import Pagination from "@/Components/Pagination"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/Navigation/NavigationMenu"
import {onMounted} from "vue";

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

onMounted(() => {
    videoPlayer.makeVideoTopRight();
});

videoPlayer.loggedIn = true

let props = defineProps({
    shows: Object,
    teams: Object,
    can: Object,
    message: String
});
</script>


