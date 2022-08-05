<template>
    <Head title="Training" />
<!--        <template #header>-->
<!--            <h2 class="font-semibold text-xl text-gray-800 leading-tight">-->
<!--                Dashboard-->
<!--            </h2>-->
<!--        </template>-->

<!--        <div class="py-12">-->
<!--            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">-->
<!--                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">-->

    <div class="place-self-center flex flex-col gap-y-3 pageWidth">
        <div class="bg-white text-black p-5 mb-10">

                            <h1 class="text-3xl pb-3 font-semibold">Training</h1>
                            <p>
                                Travis will create some training videos to help people create content in a higher broadcast quality.
                            </p>
                            <ul class="list-disc list-inside pt-4">
                                <li>
                                    Field production
                                </li>
                                <li>
                                    Studio production
                                </li>

                            </ul>
                        </div>
                    </div>
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
</template>

<script setup>
import Welcome from '@/Jetstream/Welcome.vue';
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js";
let videoPlayer = useVideoPlayerStore();
videoPlayer.class = "videoTopRight"
videoPlayer.fullPage = false

</script>

