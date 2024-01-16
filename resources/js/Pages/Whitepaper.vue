<template>
    <Head title="White Paper"/>

    <div class="pt-12 font-sans text-gray-900 bg-gray-100 antialiased">
        <div class="pt-6 pb-10">
            <div class="flex flex-col items-center">
                <div class="mb-4">
                    <Link :href="`/`"><img :src="`/storage/images/logo_black_311.png`" alt="image" class=""></Link>
                </div>

                <div class="w-full sm:max-w-2xl px-6 py-8 bg-white shadow-lg rounded-lg">
                    <div class="whitepaper w-full overflow-y-scroll overflow-x-hidden prose prose-lg max-w-none" v-html="whitepaper"></div>
                </div>
            </div>
        </div>
    </div>



</template>

<script setup>
import {Head} from '@inertiajs/inertia-vue3';
import JetApplicationLogo from '@/Jetstream/ApplicationLogo'
import { ref, nextTick } from 'vue'
// import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js";

defineProps({
    whitepaper: String,
});
</script>
<script>
import MarkdownLayout from '@/Layouts/MarkdownLayout'

export default {
    layout: MarkdownLayout,
}
</script>

<style>

.whitepaper {
    height: 80vh;
}
/* Additional styles (if necessary) */
.prose {
    font-family: 'Open Sans', sans-serif;
}
.prose h1, .prose h2, .prose h3 {
    color: #2a4365; /* Dark blue color for headings */
}
</style>

