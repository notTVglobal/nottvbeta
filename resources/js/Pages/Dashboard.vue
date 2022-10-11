<template>
    <Head title="Dashboard" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">



            <div class="flex justify-between mb-3">
                <h1 class="text-3xl font-semibold pb-3">Dashboard</h1>
                <Link :href="`/golive`"><button
                    class="bg-red-500 hover:bg-red-600 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
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

            <div v-show="can.viewAdmin" class="bg-gray-300 rounded pb-8 p-3 mb-6 mx-2 border-b border-2">
                <div class="font-semibold text-xl pb-2">Administrator only links</div>
                <div class="flex flex-wrap md:flex-row justify-items-start gap-2">
                    <!--disable button if ! admin-->
                    <Link :href="`/users`"><button
                        class="bg-blue-500 hover:bg-blue-600 text-white mt-1 p-2 col-span-1 rounded disabled:bg-gray-400"
                    >All Users</button>
                    </Link>
                    <!--disable button if ! admin-->
                    <Link :href="`/admin/channels`"><button
                        class="bg-blue-500 hover:bg-blue-600 text-white mt-1 p-2 rounded disabled:bg-gray-400"
                    >All Channels</button>
                    </Link>
                    <!--disable button if ! admin-->
                    <Link :href="`/teams`"><button
                        class="bg-blue-500 hover:bg-blue-600 text-white mt-1 p-2 rounded disabled:bg-gray-400"
                    >All Teams</button>
                    </Link>

                    <!--disable button if ! admin-->
                    <Link :href="`/video`"><button
                        class="bg-blue-500 hover:bg-blue-600 text-white mt-1 p-2 rounded disabled:bg-gray-400"
                    >MistServer API</button>
                    </Link>

                    <!--disable button if ! admin-->
                    <Link :href="`/image`"><button
                        class="bg-blue-500 hover:bg-blue-600 text-white mt-1 p-2 rounded disabled:bg-gray-400"
                    >Image Uploader</button>
                    </Link>
                </div>

            </div>

            <section class="grid grid-cols-1 lg:grid-cols-3 gap-4 my-3 mx-2 m-auto text-black">
                <div class="p-5 bg-gray-200 rounded">
                    <div class="mb-3 bg-orange-300 py-1 px-2 text-xs font-semibold text-red-800">
                        In development. Not currently working.
                    </div>
                    <div class="mb-3 grid grid-cols-1">
                        <div class="mb-1 font-semibold text-xl justify-self-start">Open Assignments</div>
                    </div>
                    <div class="ml-3">
                        <li>
                            Assignments list goes here
                        </li>
                    </div>
                </div>

                <div class="p-5 bg-gray-200 rounded relative">
                    <div class="mb-3 flex justify-between">
                        <div class="mb-1 font-semibold text-xl">My Teams</div>
                        <div class="">
                            <Link :href="`/teams/create`"><button
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 text-xs rounded disabled:bg-gray-400"
                            >New Team</button>
                            </Link>
                        </div>
                    </div>
                    <div
                        v-for="team in teams.data"
                        :key="team.id"
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 inset-x-0 bottom-0"
                    >
                        <p class=""><Link
                            @click="videoPlayer.makeVideoTopRight()"
                            :href="`/teams/${team.id}/manage`"
                            class="text-blue-800 hover:text-blue-400">
                            {{ team.name }}
                        </Link></p>
                    </div>
                    <div class="flex flex-row justify-between bottom-0 align-items-bottom py-2 px-2">
                        <!-- Paginator -->
                        <div><Pagination :links="teams.links" class="mt-6 absolute inset-x-0 bottom-0 py-2 px-2 "/></div>

                        <div>
                        <Popper
                            hover openDelay="50" closeDelay="50"
                            arrow
                            ><template #content>
                                <p class="text-xl mb-2 font-semibold">🎉 Start a new team</p>
                                <p class="">Teams manage shows, </p>
                                <p class="">even if you're a solo creator</p>

                            </template>
                            <Button><font-awesome-icon
                                icon="fa-solid fa-question"
                                class="mt-6 absolute bottom-0 text-right pr-4 py-2 "
                            /></Button>
                        </Popper>
                        </div>

                    </div>


                </div>

                <div class="p-5 bg-gray-200 rounded relative">
                    <div class="mb-3 grid grid-cols-1">
                        <div class="mb-1 font-semibold text-xl justify-self-start">My Shows</div>
                    </div>
                    <div
                        v-for="show in shows.data"
                        :key="show.id"
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                    >
                        <p class=""><Link
                            @click="videoPlayer.makeVideoTopRight()"
                            :href="`/shows/${show.id}/manage`"
                            class="text-blue-800 hover:text-blue-400">
                            {{ show.name }}
                        </Link></p>
                    </div>
                    <div class="flex flex-row justify-between bottom-0 align-items-bottom py-2 px-2">
                        <!-- Paginator -->
                        <div><Pagination :links="shows.links" class="mt-6 absolute inset-x-0 bottom-0 py-2 px-2 "/></div>
                        <div>
                        <Popper
                            hover openDelay="50" closeDelay="50"
                            arrow
                        ><template #content class="space-y-3">
                            <p class="text-xl font-semibold mb-2">🍿 These are your shows </p>
                            <p class="">Join or create a team to start a show.</p>
                        </template>
                            <Button><font-awesome-icon
                                icon="fa-solid fa-question"
                                class="mt-6 absolute bottom-0 text-right pr-4 py-2 "
                            /></Button>
                        </Popper>
                        </div>
                    </div>
                </div>


            </section>

            <div class="mt-6 h-0.5 bg-gray-800"></div>

            <section class="grid grid-cols-1 mt-6 gap-2">
                <div class="font-semibold text-2xl text-gray-800 px-2">
                    Account Summary
                </div>

                <div class="p-2 text-red-600">This section is in development. Not currently working.</div>

                <div class="border-2 pb-3">
                    <div class="grid justify-items-stretch grid-cols-3 ">
                        <div class="bg-gray-800 text-white text-sm p-2 col-span-3">Membership: 000000</div>
                    </div>
                    <table class="w-full mb-2">
                        <thead class="">
                        <td class="bg-blue-400 font-semibold text-sm text-black px-2 mb-3">Account Name</td>
                        <td class="bg-blue-400 px-2 mb-3 text-right font-semibold text-sm text-black">Balance</td>
                        </thead>
                        <tr class="border-b border-1 border-gray-100 py-2">
                            <td class="px-2 col-span-2">Chequing</td>
                            <td class="px-2 text-right">0.00</td>
                        </tr>
                        <tr class="border-b border-1 border-gray-100 py-2">
                            <td class="px-2 col-span-2">Equity Shares</td>
                            <td class="px-2 text-right">10.00</td>
                        </tr>
                        <tr class="border-b border-1 border-gray-100 py-2">
                            <td class="px-2 col-span-2">Team Account Example</td>
                            <td class="px-2 text-right">0.00</td>
                        </tr>
                    </table>

                        <table class="w-full">
                            <thead class="">
                                <td class="bg-blue-400 font-semibold text-sm text-black px-2 mb-3">Team Shares</td>
                                <td class="bg-blue-400 px-2 mb-3 text-right font-semibold text-sm text-black">Balance</td>
                            </thead>
                            <tr v-for="team in teams.data"
                                :key="team.id"
                                class="border-b border-1 border-gray-100 py-2">
                                <td class="px-2">{{ team.name }}</td>
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
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

videoPlayer.class = "videoTopRight"
videoPlayer.videoContainerClass = "videoContainerTopRight"
videoPlayer.fullPage = false
videoPlayer.loggedIn = true
chat.class = "chatSmall"
// onload(videoPlayer.class = "videoTopRight")

let props = defineProps({
    shows: Object,
    teams: Object,
    can: Object,
    message: String
});
</script>


