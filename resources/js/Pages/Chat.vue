<template>
    <Head title="Chat" />

    <div class="place-self-start flex flex-col justify-start w-50 h-screen w-3/4 px-10 break-words overflow-y-scroll ">
        <div class="fixed flex flex-col w-64 p-5 mb-10 bg-blue-400 ">
            <div class="text-3xl font-semibold">Chat</div>
        </div>
            <div class="mt-28 pl-32">
                <ChatContainer :user="props.user"/>
            </div>

    </div>
</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useChatStore } from "@/Stores/ChatStore.js"
import { onMounted } from "vue";
import ChatContainer from "@/Components/Chat/ChatContainer"

let videoPlayerStore = useVideoPlayerStore()
let chat = useChatStore()

videoPlayerStore.currentView = 'chat'
videoPlayerStore.currentPage = 'chat'

let props = defineProps ({
    user: Object,
})

onMounted(() => {
    videoPlayerStore.makeVideoTopRight();
});




</script>


