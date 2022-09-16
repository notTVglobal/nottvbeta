<template>
    <Head title="Dashboard" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">

            <div class="mb-4">
            <h1 class="text-3xl font-semibold pb-3">Dashboard</h1>
<!--disable button if ! admin-->
            <Link :href="`/admin/users`"><button
                class="bg-green-500 hover:bg-green-600 text-white mt-1 ml-6 px-4 py-2 rounded disabled:bg-gray-400"
            >All Users</button>
            </Link>
<!--disable button if ! admin-->
            <Link :href="`/admin/channels`"><button
                class="bg-green-500 hover:bg-green-600 text-white mt-1 ml-6 px-4 py-2 rounded disabled:bg-gray-400"
            >All Channels</button>
            </Link>
<!--disable button if ! admin-->
            <Link :href="`/teams`"><button
                class="bg-green-500 hover:bg-green-600 text-white mt-1 ml-6 px-4 py-2 rounded disabled:bg-gray-400"
            >All Teams</button>
            </Link>
            <Link :href="`/golive`"><button
                class="bg-red-500 hover:bg-red-600 text-white mt-1 ml-6 px-4 py-2 rounded disabled:bg-gray-400"
            >Go Live</button>
            </Link>
            </div>
            <div class="pl-5">
                <Link :href="`/video`" class="text-blue-800 hover:text-blue-400">
                    MistServer API
                </Link>
            </div>
            <div class="pl-5">
                <Link :href="`/image`" class="text-blue-800 hover:text-blue-400">
                    Image Uploader
                </Link>
            </div>

            <section class="grid grid-cols-1 lg:grid-cols-3 gap-4 my-3 m-auto p-1 text-black">
                <div class="p-5 bg-gray-200 rounded">
                    <h2 class="font-semibold text-xl">My Assignments</h2>
                    <p class="mt-1">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                </div>
                <div class="p-5 bg-gray-200 rounded">
                    <h2 class="font-semibold text-xl mb-1">My Shows</h2>
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
                    <h2 class="font-semibold text-xl mb-1">My Teams</h2>
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



