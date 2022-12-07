<template>
    <Head title="Settings" />

    <div class="place-self-center flex flex-col gap-y-3">
        <div class="bg-dark text-light p-5 mb-10">

            <header class="flex justify-between mb-3">
                <div id="topDiv">
                    <h1 class="font-semibold text-4xl text-gray-200 text-center leading-tight">Settings</h1>
                </div>
                <div> This page is under going repairs.</div>
            </header>



        </div>
    </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import { onMounted } from "vue"

let videoPlayerStore = useVideoPlayerStore()
let chat = useChatStore()

videoPlayerStore.currentPage = 'settings'

let props = defineProps({
    can: Object,
})

onMounted(() => {
    videoPlayerStore.makeVideoTopRight()
    document.getElementById("topDiv").scrollIntoView()
});

</script>

