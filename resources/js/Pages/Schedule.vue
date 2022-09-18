<template>
    <Head title="Schedule" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col gap-y-3 md:pageWidth pageWidthSmall">
        <div class="p-5 mb-10">
<!--        <template #header>-->
<!--            <h2 class="font-semibold text-xl text-gray-800 leading-tight">-->
<!--                Dashboard-->
<!--            </h2>-->
<!--        </template>-->

<!--        <div class="py-12">-->
<!--            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">-->
<!--                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">-->
                    <div class="text-3xl font-semibold">Schedule</div>
                    <button v-if="can.addEvent" class="border-gray-800">Add Event</button>
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
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

let props = defineProps({
    can: Object
})

videoPlayer.class = "videoTopRight"
videoPlayer.videoContainerClass = "videoContainerTopRight"
videoPlayer.fullPage = false
chat.class = "chatSmall"

</script>

