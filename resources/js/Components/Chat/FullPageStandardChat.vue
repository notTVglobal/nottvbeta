<template>
    <div>
        <div class="flex flex-col py-5 mt-10">
            <div class="text-3xl font-semibold">Conversation</div>
        </div>
        <div class="italic">The newest message is at the bottom.</div>
        <div class="absolute">
            <div class="relative h-[calc(h-100%-16rem)] pb-24 top-0">
                <chat-messages />
            </div>
            <div class="relative h-16">
                <input-message :user="props.user" class="fixed bottom-32"/>
            </div>
        </div>
    </div>
</template>

<script setup>
import InputMessage from "@/Components/Chat/FullPageChatInput.vue"
import ChatMessages from "@/Components/Chat/FullPageChatMessages.vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore";
import { useChatStore } from "@/Stores/ChatStore";

let videoPlayer = useVideoPlayerStore()
let chatStore = useChatStore()

let props = defineProps({
    user: Object,
})

</script>
