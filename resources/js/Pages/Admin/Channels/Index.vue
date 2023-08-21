<template>
    <Head title="Channels Admin" />

    <div class="place-self-center flex flex-col">
        <div id="topDiv" class="bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10">

            <Message v-if="showMessage" @close="showMessage = false" :message="props.message"/>

            <div class="flex justify-between">
                <h1 class="text-3xl font-semibold pb-3">Channels</h1>
                <Link :href="`/dashboard`"><button
                    class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"
                >Dashboard</button>
                </Link>
            </div>

            <div class="flex flex-row justify-between gap-x-4">
                <Link :href="`/users/create`"><button
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded disabled:bg-gray-400"
                    disabled
                >Add Channel</button>
                </Link>
                <input v-model="search" type="search" placeholder="Search..." class="border px-2 rounded-lg" />
            </div>

            <div class="p-2 text-red-600">This section is in development. Not currently working.</div>
            <div class="bg-orange-300 px-2">
                Add a channel: create playlist and add shows.
            </div>

            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

                            CHANNELS GO HERE

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>


<script setup>
import { onBeforeMount, onMounted, ref } from "vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useUserStore } from "@/Stores/UserStore";
import Message from "@/Components/Modals/Messages";

let videoPlayerStore = useVideoPlayerStore()
let userStore = useUserStore()

videoPlayerStore.currentPage = 'admin'

// onBeforeMount(() => {
//     userStore.scrollToTopCounter = 0;
// })

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
    if (userStore.isMobile) {
        videoPlayerStore.ottClass = 'ottClose'
        videoPlayerStore.ott = 0
    }
    document.getElementById("topDiv").scrollIntoView()
    // if (userStore.scrollToTopCounter === 0 ) {
    //
    //     userStore.scrollToTopCounter ++;
    // }
});

let props = defineProps({
    message: String,
})

let showMessage = ref(true);


</script>
