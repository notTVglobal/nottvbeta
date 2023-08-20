<template>
    <div class="vh-100 vw-100 z-40">
        <div class="flex flex-col p-5">
            <div class="text-3xl font-semibold">Conversation</div>
        </div>
        <div class="italic">The newest message is at the bottom.</div>
<!--        <chat-messages />-->
<!--        <input-message :user="props.user" class="fixed bottom-20 z-50" />-->
        <div class="absolute">
            <div class="relative h-[calc(h-100%-20rem)] top-0 pb-20 z-40">
                <chat-messages />
            </div>
            <div class="relative h-16">
                <input-message :user="props.user" class="fixed bottom-20 z-50" />
            </div>
        </div>
    </div>
</template>

<script setup>
import InputMessage from "@/Components/Chat/FullPageChatInput"
import ChatMessages from "@/Components/Chat/FullPageChatMessages"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore";
import { useChatStore } from "@/Stores/ChatStore";

let videoPlayer = useVideoPlayerStore()
let chatStore = useChatStore()

let props = defineProps({
    user: Object,
})

</script>
