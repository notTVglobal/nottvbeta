<template>
    <main>
        <Quiz :quiz="state" />
        <div class="w-full h-10 bg-orange-300"></div>
        <VideoPlayer2 :videoPlayer2="{ name: 'Video Player2', class: 'blue', videoId: '' }" />

        <div class="mt-6">
            <h1 class="text-lg">{{ counter.count }}</h1>
            <button @click="counter.count++" class="p-3 bg-blue-800 text-white rounded">Increment</button>
        </div>

        <div class="mt-6">
            <h1 class="text-lg">{{ videoPlayer3.class }}</h1>
            <button @click="videoPlayer3.class = 'blue'" class="p-3 bg-blue-800 text-white rounded">Change Class</button>
        </div>
    </main>

</template>


<script setup>
import Quiz from "@/Components/Quiz/Quiz.vue";
import { state } from "@/Stores/QuizStore.js";
import VideoPlayer2 from "@/Components/VideoPlayer/VideoPlayer2.vue";
import {useCounterStore} from "@/Stores/CounterStore";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js";

let counter = useCounterStore();
let videoPlayer3 = useVideoPlayerStore();

</script>
