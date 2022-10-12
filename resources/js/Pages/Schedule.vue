<template>
    <Head title="Schedule" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

    <div class="place-self-center flex flex-col md:pageWidth pageWidthSmall h-screen">
        <div class="flex justify-between p-5 mb-5">

                    <div class="text-3xl font-semibold">Schedule</div>
                    <Link :href="`#`"><button
                        v-if="can.addEvent"
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded disabled:bg-gray-400"
                        disabled
                    >Add Event</button></Link>


        </div>
        <div class="ml-5 mb-5 text-red-600">This section is in development. Not currently working.</div>
    </div>
</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"
import {onMounted} from "vue";

let videoPlayer = useVideoPlayerStore()
let chat = useChatStore()

let props = defineProps({
    can: Object
})

onMounted(() => {
    videoPlayer.makeVideoTopRight();
});

// chat.class = "chatSmall"

</script>

