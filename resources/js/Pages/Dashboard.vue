<template>
    <Head title="Dashboard" />

    <div class="place-self-center flex flex-col gap-y-3 pageWidth">
        <div class="bg-white text-black p-5 mb-10">

            <h1 class="text-3xl font-semibold pb-3">Dashboard</h1>

            <section class="grid grid-cols-1 lg:grid-cols-3 gap-4 my-3 m-auto p-1 text-black">
                <div class="p-5 bg-gray-200 rounded">
                    <h2 class="font-semibold text-xl">Assignments</h2>
                    <p class="mt-1">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                </div>
                <div class="p-5 bg-gray-200 rounded">
                    <h2 class="font-semibold text-xl">Shows</h2>
                    <p class="mt-1">Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                </div>
                <div class="p-5 bg-gray-200 rounded">
                    <h2 class="font-semibold text-xl">Teams</h2>
                    <p class="mt-1">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."</p>
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
                </div>
            </section>

        </div>
    </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"

let videoPlayer = useVideoPlayerStore()

videoPlayer.class = "videoTopRight"
videoPlayer.videoContainerClass = "videoContainerTopRight"
videoPlayer.fullPage = false
videoPlayer.loggedIn = true
// onload(videoPlayer.class = "videoTopRight")
</script>



