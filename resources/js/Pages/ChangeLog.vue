<template>
    <Head title="Privacy Policy"/>

    <div id="topDiv" class="font-sans text-gray-900 antialiased ">
        <div class="pt-4 bg-gray-100 rounded">
            <div class="flex flex-col items-center pt-6 sm:pt-0">
                <div>
                    <JetAuthenticationCardLogo/>
                </div>

                <div class="changelog mb-5 mt-10 w-full px-4">
                        <span class="mt-6 p-6 bg-white shadow-md sm:rounded-lg prose"
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
