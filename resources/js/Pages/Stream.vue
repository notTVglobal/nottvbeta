<template>
    <Head title="Stream" />
<!--        <template #header>-->
<!--            <h2 class="font-semibold text-xl text-gray-800 leading-tight">-->
<!--                Dashboard-->
<!--            </h2>-->
<!--        </template>-->



        <ChatBox
        />
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white text-black shadow-xl sm:rounded-lg">
                    <div class="flex m-auto mt-10 mb-10 w-1/2">
                        <div class="py-10 bg-orange-400">If this page does not show a full screen video, then click "Stream" again.</div>
                    </div>
                </div>
            </div>
        </div>
</template>


<script setup>
import ChatBox from "@/Components/ChatBox.vue";

defineProps ({
    video: Object
})

</script>

