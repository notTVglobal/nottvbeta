<template>
    <main>
        <Quiz :quiz="state" />
        <div class="w-full h-10 bg-orange-300"></div>

        <div class="mt-6">
            <h1 class="text-lg">{{ counter.count }}</h1>
            <button @click="counter.count++" class="p-3 bg-blue-800 text-white rounded">Increment</button>
        </div>

    </main>

</template>


<script setup>
import Quiz from "@/Components/Quiz/Quiz.vue"
import { state } from "@/Stores/QuizStore.js"
import {useCounterStore} from "@/Stores/CounterStore"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"

let counter = useCounterStore()

let videoPlayer = useVideoPlayerStore()
videoPlayer.class = "videoTopRight"
videoPlayer.videoContainerClass = "videoContainerTopRight"
videoPlayer.fullPage = false

</script>
