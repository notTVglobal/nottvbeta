<template>
    <Head title="Stream" />
    <div class="sticky top-0 w-full nav-mask">
        <ResponsiveNavigationMenu/>
        <NavigationMenu />
    </div>

<div class="opacity-80 chatButtonForStreamPage w-20 h-20 rounded-full bg-orange-400 text-orange-100 hover:bg-orange-600 hover:text-orange-300 cursor-pointer grid justify-center content-center">
    <font-awesome-icon icon="fa-comments" class="text-3xl"/><div>CHAT</div>
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

onMounted(() => {
    videoPlayer.makeVideoFullPage();
});

videoPlayer.loggedIn = true

defineProps ({
    video: Object
})

</script>

