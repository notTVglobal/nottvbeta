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
                <!--disable button if ! admin-->
                <Link :href="`/users`"><button
                    class="bg-blue-500 hover:bg-blue-600 text-white mt-1 ml-6 px-4 py-2 rounded disabled:bg-gray-400"
                >All Users</button>
                </Link>
                <!--disable button if ! admin-->
                <Link :href="`/admin/channels`"><button
                    class="bg-blue-500 hover:bg-blue-600 text-white mt-1 ml-6 px-4 py-2 rounded disabled:bg-gray-400"
                >All Channels</button>
                </Link>
                <!--disable button if ! admin-->
                <Link :href="`/teams`"><button
                    class="bg-blue-500 hover:bg-blue-600 text-white mt-1 ml-6 px-4 py-2 rounded disabled:bg-gray-400"
                >All Teams</button>
                </Link>

                <!--disable button if ! admin-->
                <Link :href="`/video`"><button
                    class="bg-blue-500 hover:bg-blue-600 text-white mt-1 ml-6 px-4 py-2 rounded disabled:bg-gray-400"
                >MistServer API</button>
                </Link>

                <!--disable button if ! admin-->
                <Link :href="`/image`"><button
                    class="bg-blue-500 hover:bg-blue-600 text-white mt-1 ml-6 px-4 py-2 rounded disabled:bg-gray-400"
                >Image Uploader</button>
                </Link>

            </div>

            <section class="grid grid-cols-1 lg:grid-cols-3 gap-4 my-3 mx-2 m-auto text-black">
                <div class="p-5 bg-gray-200 rounded">
                    <div class="mb-3 bg-orange-300 px-2 text-xs">
                        In development. Not currently working.
                    </div>
                    <div class="mb-3 grid grid-cols-1">
                        <div class="mb-1 font-semibold text-xl justify-self-start">My Assignments</div>
                        <div class="justify-self-start">
                            <Link :href="`#`"><button
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 text-xs rounded disabled:bg-gray-400"
                            >Create Assignment</button>
                            </Link>
                        </div>
                    </div>
                    <div class="ml-3">
                        <li>
                            Assignments list goes here
                        </li>
                    </div>
                </div>
                <div class="p-5 bg-gray-200 rounded">
                    <div class="mb-3 bg-orange-300 px-2 text-xs">
                        In development. Not currently working.
                    </div>
                    <div class="mb-3 grid md:grid-cols-1">
                        <div class="mb-1 font-semibold text-xl justify-self-start">My Episodes</div>
                        <div class="justify-self-start">
                            <Link :href="`#`"><button
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 text-xs rounded disabled:bg-gray-400"
                            >New Episode</button>
                            </Link>
                        </div>
                    </div>
                    <div class="grid grid-cols-1">
                        <div class="">
                            <p class=""><Link
                                @click="videoPlayer.makeVideoTopRight()"
                                :href="`#`"
                                class="text-blue-800 hover:text-blue-400">
                                Epsiode ### or NAME
                            </Link></p>
                            <p class=""><Link
                                @click="videoPlayer.makeVideoTopRight()"
                                :href="`#`"
                                class="text-blue-800 hover:text-blue-400">
                                Epsiode ### or NAME
                            </Link></p>
                            <p class=""><Link
                                @click="videoPlayer.makeVideoTopRight()"
                                :href="`#`"
                                class="text-blue-800 hover:text-blue-400">
                                Epsiode ### or NAME
                            </Link></p>
                        </div>
                    </div>

                </div>
                <div class="p-5 bg-gray-200 rounded">
                    <div class="mb-3 grid grid-cols-1">
                        <div class="mb-1 font-semibold text-xl justify-self-start">My Shows</div>
                        <div class="justify-self-start">
                            <Link :href="`/shows/create`"><button
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 text-xs rounded disabled:bg-gray-400"
                            >New Show</button>
                            </Link>
                        </div>
                    </div>
                    <p class=""><Link
                        @click="videoPlayer.makeVideoTopRight()"
                        :href="`/shows/1`"
                        class="text-blue-800 hover:text-blue-400">
                        Down The Rabbit Hole
                    </Link></p>
                    <p class=""><Link
                        @click="videoPlayer.makeVideoTopRight()"
                        :href="`/shows/1`"
                        class="text-blue-800 hover:text-blue-400">
                        News & Talk
                    </Link></p>
                </div>
                <div class="p-5 bg-gray-200 rounded">
                    <div class="mb-3 grid grid-cols-1">
                        <div class="mb-1 font-semibold text-xl justify-self-start">My Teams</div>
                        <div class="justify-self-start">
                            <Link :href="`/teams/create`"><button
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 text-xs rounded disabled:bg-gray-400"
                            >New Team</button>
                            </Link>
                        </div>
                    </div>
                    <p class=""><Link
                        @click="videoPlayer.makeVideoTopRight()"
                        :href="`/teams/1`"
                        class="text-blue-800 hover:text-blue-400">
                        notTV Founders
                    </Link></p>
                    <p class=""><Link
                        @click="videoPlayer.makeVideoTopRight()"
                        :href="`/teams/2`"
                        class="text-blue-800 hover:text-blue-400">
                        notTV News Team
                    </Link></p>
                    <p class=""><Link
                        @click="videoPlayer.makeVideoTopRight()"
                        :href="`/teams/3`"
                        class="text-blue-800 hover:text-blue-400">
                        RJG Productions
                    </Link></p>

                </div>

            </section>
<div class="mt-6 h-0.5 bg-gray-800"></div>
            <section class="grid grid-cols-1 mt-6 gap-2">
                <div class="font-semibold text-2xl text-gray-800 px-2">
                    Account Summary
                </div>
                <div class="px-2">
                    Account: 000000
                </div>
                <div class="grid grid-cols-3 border-2 pb-3">
                    <h2 class="bg-gray-800 text-white text-sm p-2 col-span-3">Membership: 000000</h2>
                    <h2 class="bg-blue-400 font-semibold text-sm text-black px-2 mb-3 col-span-3">Account Name</h2>
                    <p class="px-2 col-span-2">Chequing</p>
                    <p class="px-2 justify-self-end">0.00</p>
                    <p class="px-2 col-span-2">Equity Shares</p>
                    <p class="px-2 justify-self-end">10.00</p>
                    <p class="px-2 col-span-2">Savings</p>
                    <p class="px-2 justify-self-end">0.00</p>

                    <h2 class="bg-blue-400 font-semibold text-sm text-black my-3 px-2 col-span-3">Team Accounts</h2>
                    <p class="px-2 col-span-2">notTV Founders Shares</p>
                    <p class="px-2 justify-self-end">0.00</p>
                    <p class="px-2 col-span-2">notTV News Team Shares</p>
                    <p class="px-2 justify-self-end">0.00</p>
                    <p class="px-2 col-span-2">RJG Production Shares</p>
                    <p class="px-2 justify-self-end">0.00</p>
                </div>
            </section>

        </div>
    </div>

</template>

<script setup>
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
    can: Object,
    message: String
});
</script>



