<template>
    <Head title="Privacy Policy"/>

    <div id="topDiv" class="font-sans text-gray-900 antialiased">
        <div class="pt-4 rounded">
            <div class="flex flex-col items-center pt-6 sm:pt-0 pb-32">
                <div>
                    <img :src="`/storage/images/logo_white_512.png`" alt="image" class="w-1/2 justify-center">
                </div>

                <div class="changelog w-full mt-10 mb-32 px-4">
                        <span class="mt-6 p-6 bg-gray-800 text-gray-50 shadow-md sm:rounded-lg prose"
                         v-html="changelog"/>
                </div>

            </div>
        </div>
    </div>

</template>

<script setup>
import { Head } from '@inertiajs/inertia-vue3';
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue';

import { onMounted } from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

userStore.currentPage = 'changeLog'
userStore.showFlashMessage = true;

onMounted(() => {
    videoPlayerStore.makeVideoTopRight()
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()
});

defineProps({
    changelog: String,
});

</script>

<style scoped>

.changelog {
    height: 80vh;
}

pre {
    overflow-x: auto;
    white-space: pre-wrap;
    white-space: -moz-pre-wrap;
    white-space: -pre-wrap;
    white-space: -o-pre-wrap;
    word-wrap: break-word;
}
</style>
