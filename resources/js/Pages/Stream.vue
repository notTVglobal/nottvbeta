<template>
    <Head title="Stream" />
<!--        <template #header>-->
<!--            <h2 class="font-semibold text-xl text-gray-800 leading-tight">-->
<!--                Dashboard-->
<!--            </h2>-->
<!--        </template>-->



        <ChatBox class="z-20" />
</template>


<script setup>
import ChatBox from "@/Components/ChatBox.vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js";

let videoPlayer = useVideoPlayerStore();

videoPlayer.fullPage = true;
videoPlayer.class = "videoFullPage";

defineProps ({
    video: Object
})

</script>

