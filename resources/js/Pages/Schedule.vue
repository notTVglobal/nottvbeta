<template>
    <Head title="Schedule" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col md:pageWidth pageWidthSmall h-screen">
        <div class="flex justify-between p-5 mb-5">

                    <div class="text-3xl font-semibold">Schedule</div>

        </div>
        <div class="ml-5 mb-5 text-red-600">This section is in development. Not currently working.</div>
    </div>
</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import ResponsiveNavigationMenu from "@/Components/Navigation/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/Navigation/NavigationMenu"
import {onMounted} from "vue";

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

videoPlayer.currentPage = 'schedule'

let props = defineProps({
    can: Object
})

onMounted(() => {
    videoPlayer.makeVideoTopRight();
});

// chat.class = "chatSmall"

</script>

