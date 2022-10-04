<template>
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <Head title="Channels" />

        <div class="fixed bg-black bg-opacity-90 text-white p-5 pt-16 h-screen w-full channels-mask">

            <h1 class="text-3xl font-semibold text-center">Channels</h1>
            <div class="bg-red-800 col-span-3 mt-8 mb-4 py-1 text-center">
                Channels are not currently enabled.
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-2 space-y-2 px-16 text-center h-3/4 overflow-y-auto pb-20 items-stretch">
                <div class="bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto">Ambient</div>
                <div class="bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto">Local</div>
                <div class="bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto">News</div>
                <div class="bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto">Talk</div>
                <div class="bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto">Variety</div>
                <div class="bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto">Music</div>
                <div class="bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto">Kids</div>
                <div class="bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto">Live</div>
                <div class="bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto">Random</div>
                <div class="bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto">Documentaries</div>
                <div class="bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto">Sports</div>
                <div class="bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-10 cursor-pointer my-auto">Education</div>
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


