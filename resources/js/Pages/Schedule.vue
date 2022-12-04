<template>
    <Head title="Schedule" />

    <div class="place-self-center flex flex-col">
        <div class="flex justify-between p-5 mb-5">

                    <div class="text-3xl font-semibold">Schedule</div>

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
import { useChatStore } from "@/Stores/ChatStore.js"
import {onMounted} from "vue";

let videoPlayerStore = useVideoPlayerStore()
let chat = useChatStore()

videoPlayerStore.currentPage = 'schedule'

let props = defineProps({
    can: Object
})

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
});

// chat.class = "chatSmall"

</script>

