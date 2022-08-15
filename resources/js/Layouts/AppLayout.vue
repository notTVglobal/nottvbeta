<template>
    <div>
        <JetBanner/>



        <div class="relative top-0 bg-gray-800 text-gray-200 h-full w-full ">








            <!-- Page Heading -->
                        <header v-if="$slots.header" class="bg-white shadow">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                <slot name="header" />
                            </div>
                        </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
            <div class="relative w-full h-full">
                <VideoPlayer :class="videoPlayerStore.class" class="videoContainer"/>
            </div>
            <Chat :user="user" :class="chat.class" class="chatContainer"/>

        </div>

    </div>

</template>

<script setup>
import ResponsiveNavigationMenu from "@/Components/ResponsiveNavigationMenu"
import NavigationMenu from "@/Components/NavigationMenu"
import VideoPlayer from "@/Components/VideoPlayer/VideoPlayer.vue"
import Chat from "@/Components/Chat/Chat"
import { Inertia } from '@inertiajs/inertia'
import JetBanner from '@/Jetstream/Banner.vue'
import { useChatStore } from "@/Stores/ChatStore"
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js"

let videoPlayerStore = useVideoPlayerStore()
let chat = useChatStore()


defineProps({
    user: Object,
});


</script>

<style>
.chatContainer {

}
.videoContainer {
    z-index:50;
}
</style>
