<template>
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <Head title="Channels" />

        <div class="fixed bg-black bg-opacity-90 text-white p-5 pt-16 h-screen w-full channels-mask">
            <div class="grid grid-cols-3 gap-4 px-16 justify-center text-center">
                <div class="col-span-3">
                    <h1 class="text-3xl font-semibold">Channels</h1>
                    <div class="bg-red-800 col-span-3 mt-8 mb-4 py-1">
                        Channels are not currently enabled.
                    </div>
                </div>
                <div class="bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-4 cursor-pointer">Ambient</div>
                <div class="bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-4 cursor-pointer">Local</div>
                <div class="bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-4 cursor-pointer">News</div>
                <div class="bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-4 cursor-pointer">Talk</div>
                <div class="bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-4 cursor-pointer">Variety</div>
                <div class="bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-4 cursor-pointer">Music</div>
                <div class="bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-4 cursor-pointer">Kids</div>
                <div class="bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-4 cursor-pointer">Live</div>
                <div class="bg-orange-300 hover:bg-blue-500 text-black hover:text-white p-4 cursor-pointer">Random</div>
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


