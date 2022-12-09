<template>
    <Head title="Schedule" />

    <div id="topDiv"></div>
    <div class="place-self-center flex flex-col">
        <div class="flex justify-between p-5 mb-5">

            <div class="text-3xl font-semibold pt-4">Schedule</div>

        </div>
        <div class="ml-5 mb-5 text-red-600">This section is in development. Not currently working.</div>
        <ul class="ml-12">
            <li>Scheduled new releases</li>
            <li>Scheduled live events</li>
        </ul>
    </div>
</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore.js"
import { onBeforeMount, onMounted } from "vue";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'schedule';

onBeforeMount(() => {
    userStore.scrollToTopCounter = 0;
})

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.scrollToTopCounter === 0 ) {
        document.getElementById("topDiv").scrollIntoView()
        userStore.scrollToTopCounter ++;
    }
});

let props = defineProps({
    can: Object
})

// chat.class = "chatSmall"

</script>

