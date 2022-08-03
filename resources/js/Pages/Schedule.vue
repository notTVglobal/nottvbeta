<template>
    <Head title="Schedule" />

    <div class="place-self-center flex flex-col gap-y-3 mr-96">
        <div class="p-5 mb-10">
<!--        <template #header>-->
<!--            <h2 class="font-semibold text-xl text-gray-800 leading-tight">-->
<!--                Dashboard-->
<!--            </h2>-->
<!--        </template>-->

<!--        <div class="py-12">-->
<!--            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">-->
<!--                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">-->
                    <div class="text-3xl font-semibold">Schedule</div>

<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
        </div>
    </div>
</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js";
let videoPlayer = useVideoPlayerStore();
videoPlayer.class = "videoTopRight"

</script>

