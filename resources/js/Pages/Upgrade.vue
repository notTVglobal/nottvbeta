<template>

    <Head title="Upgrade Account" />
    <div id="topDiv"></div>
    <div class="place-self-center flex flex-col gap-y-3">
        <div class="bg-white text-black p-5 mb-10">

            <Message v-if="showMessage" @close="showMessage = false" :message="props.message"/>

            <h1 class="text-3xl font-semibold pb-3">Become a notTV Premium Subscriber</h1>
            <p class="mb-8">
            </p>
            <div class="bg-orange-300 px-2 text-2xl p-10 text-center font-semibold">
                Get full access to all features, shows, channels and movies!
            </div>
            <p class="mt-28 pb-28 text-center">
                A form to upgrade will go here.
            </p>

        </div>
    </div>

</template>

<script setup>
import { onBeforeMount, onMounted, ref } from "vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore";
import Message from "@/Components/Modals/Messages";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'upgrade'

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
    message: String,
})

let showMessage = ref(true);

</script>

