<template>
    <Head title="Stream" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>


</template>


<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"

let videoPlayer = useVideoPlayerStore()

videoPlayer.fullPage = true
videoPlayer.loggedIn = true
videoPlayer.class = "videoFullPage"
videoPlayer.videoContainerClass = "videoContainerFullPage"

defineProps ({
    video: Object
})

</script>

