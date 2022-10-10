<template>
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <Head title="Channels" />

        <div class="fixed bg-black bg-opacity-90 text-white p-5 pt-16 h-screen w-full channels-mask">

            <h1 class="text-3xl font-semibold text-center">Channels</h1>
            <h2 class="bg-red-800 col-span-3 mt-8 mb-4 py-1 text-center">
                Channels are not currently enabled.
            </h2>


            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-2 space-y-2 text-center px-16 pb-20 h-3/4 overflow-y-auto items-stretch">
                <div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Live</div>
                <div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Ambient</div>
                <div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Local</div>
                <div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">News</div>
                <div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Talk</div>
                <div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Music</div>
                <div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Sports</div>
                <div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Variety</div>
                <div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Kids</div>
                <div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Documentaries</div>
                <div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Education</div>
                <div class="font-semibold text-3xl bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto h-40 text-center">Random</div>
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

videoPlayer.class = "videoFullPage"
videoPlayer.videoContainerClass = "videoContainerFullPage"
videoPlayer.fullPage = true
chat.class = "chatSmall"

</script>

<style>
.channels-mask {
    z-index:90;
}
</style>


