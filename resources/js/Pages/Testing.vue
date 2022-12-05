<template>

    <div class="divZ space-x-4">
        <button class="bg-blue-800 text-white" @click="videoPlayerStore.makeVideoTopRight()">VIDEO TOP RIGHT</button>
        <button class="bg-blue-800 text-white" @click="videoPlayerStore.makeVideoFullPage()">VIDEO FULL PAGE</button>

    </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import { onBeforeMount, onMounted } from "vue"

let videoPlayerStore = useVideoPlayerStore()
let chat = useChatStore()

videoPlayerStore.currentPage = 'testing'

onBeforeMount(() => {

})

function makeVideoFullPage() {
    videoPlayerStore.class = 'fullPageVideoClass'
    videoPlayerStore.videoContainerClass = 'fullPageVideoContainer'
}

function makeVideoTopRight() {
    videoPlayerStore.class = 'topRightVideoClass'
    videoPlayerStore.videoContainerClass = 'topRightVideoContainer'
}

</script>

<style scoped>
.divZ {
    position: absolute;
    z-index: 900;
    top: 20px;

}

</style>
