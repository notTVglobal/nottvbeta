<template>
    <Head title="Stream" />
<!--        <template #header>-->
<!--            <h2 class="font-semibold text-xl text-gray-800 leading-tight">-->
<!--                Dashboard-->
<!--            </h2>-->
<!--        </template>-->



        <ChatBox />
</template>


<script setup>
import ChatBox from "@/Components/ChatBox.vue";

defineProps ({
    video: Object
})

</script>

