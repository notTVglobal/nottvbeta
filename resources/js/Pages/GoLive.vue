<template>
    <Head title="Go Live" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>
<!--        <template #header>-->
<!--            <h2 class="font-semibold text-xl text-gray-800 leading-tight">-->
<!--                Dashboard-->
<!--            </h2>-->
<!--        </template>-->

<!--        <div class="py-12">-->
<!--            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">-->
<!--                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">-->

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="bg-white text-black p-5 mb-10">

            <div class="flex justify-between mb-6">
                <h1 class="text-3xl pb-3 font-semibold">Go Live</h1>
                <Link href="/dashboard" class="text-blue-500 text-sm ml-2">Dashboard</Link>
            </div>


                            <p>
                                Here we will place instructions on how to go live..
                            </p>
                            <ul class="list-disc list-inside pt-4 space-y-1">
                                <li>
                                    Add a dropdown of your shows to select which show you want to go live on.
                                </li>
                                <li>
                                    Display go live instructions here.
                                </li>
                                <li>
                                    If you want to stream from a mobile device we will include a video here to show you how to download and setup the Larix Broadcaster app.
                                </li>
                                <li>
                                    If you want to stream from software like Open Broadcaster or vMix we will include a video to show you how here.
                                </li>

                            </ul>
                        </div>
                    </div>
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
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
chat.class = "chatSmall"

</script>

