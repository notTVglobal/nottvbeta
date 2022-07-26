<template>
    <QuizHeader />

    <QuizQuestion />

    <QuizFooter />

</template>

<script setup>
import QuizHeader from "@/Components/Quiz/QuizHeader.vue";
import QuizQuestion from "@/Components/Quiz/QuizQuestion.vue";
import QuizFooter from "@/Components/Quiz/QuizFooter.vue";
import { state } from "@/Stores/QuizStore.js";


</script>
