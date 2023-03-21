<template>

    <Head title="My Library" />

    <div id="topDiv"></div>
    <div class="place-self-center flex flex-col gap-y-3">
        <div class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

            <header class="flex justify-between mb-3">
                <div class="pt-4">
                    <h1 class="text-3xl font-semibold pb-3">My Library</h1>
                </div>

            </header>
            <div class="flex flex-row justify-end gap-x-4 mb-4">

                <input v-model="search" type="search" placeholder="Search..." class="border px-2 rounded-lg text-black" />
            </div>

            <div class="mb-4">
                <div class="p-2 text-red-600">This section is in development. Not currently working.</div>
                <p class="">
                    Your notTV Library... Shows and creators you follow will appear here. Movies and episodes on your Watch Later list. And live events you're interested in. Also, items from the shop you have saved will all appear here!
                </p>
            </div>



            <div class="bg-orange-300 text-black px-2">
                Display library items here.
            </div>

        </div>
    </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import {onBeforeMount, onMounted} from "vue"
import {useUserStore} from "@/Stores/UserStore";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'library'

onBeforeMount(() => {
    userStore.scrollToTopCounter = 0;
})

onMounted(() => {
    videoPlayerStore.makeVideoTopRight()
    if (userStore.scrollToTopCounter === 0 ) {
        document.getElementById("topDiv").scrollIntoView()
        userStore.scrollToTopCounter ++;
    }
});

let props = defineProps({
    can: Object,
})

</script>

