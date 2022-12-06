<template>

    <Head title="Shop" />

    <div class="place-self-center flex flex-col gap-y-3">
        <div class="bg-white text-black p-5 mb-10">

            <header class="flex justify-between mb-3">
                <div id="topDiv">
                    <h1 class="text-3xl font-semibold pb-3">Shop</h1>
                </div>
            </header>

            <div class="mb-4">
                <div class="p-2 text-red-600">This section is in development. Not currently working.</div>
                <p class="">
                    Our notTV Shop... Add sponsors, who add commercials for products, services and events.
                </p>
            </div>

            <div class="flex flex-row justify-end gap-x-4 mb-4">

                <input v-model="search" type="search" placeholder="Search..." class="border px-2 rounded-lg" />
            </div>

            <div class="bg-orange-300 px-2">
                Display items, services and events here.
            </div>

        </div>
    </div>

</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import { onBeforeMount, onMounted } from "vue"

let videoPlayerStore = useVideoPlayerStore()
let chat = useChatStore()

videoPlayerStore.currentPage = 'shop'


onBeforeMount(() => {

})

const loadVideoCSS = async () => {
    await videoPlayerStore.makeVideoFullPage();
    await videoPlayerStore.makeVideoTopRight();
}

onMounted(() => {
    loadVideoCSS();
    document.getElementById("topDiv").scrollIntoView()
});


let props = defineProps({
    can: Object,
})

</script>

