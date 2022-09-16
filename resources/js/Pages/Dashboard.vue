<template>
    <Head title="Dashboard" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">

            <div class="mb-4">

            <div class="flex justify-between mb-3">
                <h1 class="text-3xl font-semibold pb-3">Dashboard</h1>
                <Link :href="`/golive`"><button
                    class="bg-red-500 hover:bg-red-600 text-white mt-1 mx-2 px-4 py-2 rounded disabled:bg-gray-400"
                >Go Live</button>
                </Link>
            </div>

                <div class="bg-gray-300 rounded pb-2 p-3 mx-2">
                    <div class="font-semibold text-xl pb-2">Administrator only links</div>
                    <!--disable button if ! admin-->
                    <Link :href="`/admin/users`"><button
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

                    <div class="pl-5 pt-2">
                        <Link :href="`/video`" class="text-blue-800 hover:text-blue-400">
                            MistServer API
                        </Link>
                    </div>
                    <div class="pl-5">
                        <Link :href="`/image`" class="text-blue-800 hover:text-blue-400">
                            Image Uploader
                        </Link>
                    </div>
                </div>
                </div>

            <section class="grid grid-cols-1 lg:grid-cols-3 gap-4 my-3 mx-2 m-auto text-black">
                <div class="p-5 bg-gray-200 rounded">
                    <div class="mb-1 flex-col-1">
                        <div class="font-semibold text-xl">My Assignments</div>
                        <div><Link :href="`#`" class="text-blue-500 text-xs">Create New Assignment</Link></div>
                    </div>
                    <div class="ml-3">
                        <li>
                            Assignments list goes here
                        </li>
                    </div>
                </div>
                <div class="p-5 bg-gray-200 rounded">
                    <div class="mb-1 flex-col-1">
                        <div class="font-semibold text-xl">My Shows</div>
                        <div><Link :href="`/shows/create`" class="text-blue-500 text-xs">Create New Show</Link></div>
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
                    <div class="mb-1 flex-col-1">
                        <div class="font-semibold text-xl">My Teams</div>
                        <div><Link :href="`/teams/create`" class="text-blue-500 text-xs">Create New Team</Link></div>
                    </div>
                    <p class=""><Link
                        @click="videoPlayer.makeVideoTopRight()"
                        :href="`/teams/1`"
                        class="text-blue-800 hover:text-blue-400">
                        notTV Founders
                    </Link></p>
                    <p class=""><Link
                        @click="videoPlayer.makeVideoTopRight()"
                        :href="`/teams/1`"
                        class="text-blue-800 hover:text-blue-400">
                        notTV News Team
                    </Link></p>
                    <p class=""><Link
                        @click="videoPlayer.makeVideoTopRight()"
                        :href="`/teams/1`"
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
                <div class="grid grid-cols-2 border-2 pb-3">
                    <h2 class="bg-gray-800 text-white text-sm p-2 col-span-2">Membership: 000000</h2>
                    <h2 class="bg-blue-400 font-semibold text-sm text-black px-2 mb-3 col-span-2">Account Name</h2>
                    <p class="px-2">Chequing</p>
                    <p class="px-2 justify-self-end">0.00</p>
                    <p class="px-2">Equity Shares</p>
                    <p class="px-2 justify-self-end">10.00</p>
                    <p class="px-2">Savings</p>
                    <p class="px-2 justify-self-end">0.00</p>

                    <h2 class="bg-blue-400 font-semibold text-sm text-black my-3 px-2 col-span-2">Team Accounts</h2>
                    <p class="px-2">notTV Founders Shares</p>
                    <p class="px-2 justify-self-end">0.00</p>
                    <p class="px-2">notTV News Team Shares</p>
                    <p class="px-2 justify-self-end">0.00</p>
                    <p class="px-2">RJG Production Shares</p>
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
</script>



