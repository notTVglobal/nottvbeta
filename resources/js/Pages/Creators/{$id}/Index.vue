<template>

    <Head :title="props.creator.name" />

    <div id="topDiv"></div>
    <div class="place-self-center flex flex-col gap-y-3">
        <div class="bg-white text-black p-5 mb-10">

            <Message v-if="showMessage" @close="showMessage = false" :message="props.message"/>

            <div class="flex justify-between mb-6">
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
import { onBeforeMount, onMounted, ref } from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useStreamStore } from "@/Stores/StreamStore.js"
import { useUserStore } from "@/Stores/UserStore";
import Message from "@/Components/Modals/Messages";

let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'creators'

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
    creator: Object,
    message: String,
});

let showMessage = ref(true);

</script>
