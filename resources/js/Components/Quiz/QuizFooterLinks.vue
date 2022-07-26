<template>
    <div>
        <h5 class="text-green-400">{{ state.name }}</h5>

        <button @click="state.name = 'A new quiz name'" class="bg-gray-300 text-indigo-700 py-2">Change quiz name</button>

        <ul>
            <li><a href="#">Get a job</a></li>
            <li><a href="#">Contact us</a></li>
        </ul>
    </div>
</template>

<script setup>
import { state } from "@/Stores/QuizStore.js";

defineProps({
    quiz: Object
});

</script>
