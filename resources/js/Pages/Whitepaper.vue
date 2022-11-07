<template>
    <Head title="Terms of Service"/>

    <div class="font-sans text-gray-200 antialiased">
        <div class="pt-4 bg-gray-800">
            <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
                <div>
                    <JetApplicationLogo/>
                </div>

                <div class="font-['Open_Sans'] w-full min-w-fit sm:max-w-2xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg prose"
                     v-html="whitepaper"/>



            </div>
        </div>
    </div>
</template>

<script setup>
import {Head} from '@inertiajs/inertia-vue3';
import JetApplicationLogo from '@/Jetstream/ApplicationLogo.vue';
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js";

let videoPlayer = useVideoPlayerStore();
videoPlayer.class = "videoTopRight"
videoPlayer.fullPage = false

defineProps({
    whitepaper: String,
});
</script>
<script>
import NoLayout from '@/Layouts/NoLayout';

export default {
    layout: NoLayout,
}
</script>
