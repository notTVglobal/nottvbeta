<template>
    <Head title="Create Episode"/>

    <div id="topDiv" class="place-self-center flex flex-col gap-y-3">
        <div class="bg-white text-black p-5 mb-10">

            TEST INDEX
        </div>
    </div>

</template>

<script setup>
import {onMounted} from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"

let videoPlayerStore = useVideoPlayerStore()
let chat = useChatStore()

videoPlayerStore.currentPage = 'users'

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    document.getElementById("topDiv").scrollIntoView()
});

</script>
