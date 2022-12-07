<template>

    <Head :title="props.creator.name" />

    <div class="place-self-center flex flex-col gap-y-3">
        <div class="bg-white text-black p-5 mb-10">

            <div id="topDiv" class="flex justify-between mb-6">
                <h1 class="text-2xl pb-3">{{props.creator.name}}</h1>
                <Link href="/shows" class="text-blue-500 text-sm ml-2">Go back</Link>
            </div>
            <p>
                <img :src="props.creator.profile_photo_url" />
            </p>

        </div>
    </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useStreamStore } from "@/Stores/StreamStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import {onMounted} from "vue";

let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let chat = useChatStore()

videoPlayerStore.currentPage = 'creators'

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    document.getElementById("topDiv").scrollIntoView()
});

let props = defineProps({
    creator: Object
});
</script>
