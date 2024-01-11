<template>
    <div>
        <div class="flex flex-col ">
            <div class="text-3xl font-semibold">Conversation</div>
        </div>
        <div class="italic mb-10">The newest message is at the bottom.</div>
        <div class="absolute">
            <div>
                <chat-messages />
            </div>
            <div>
                <input-message
                    :user="props.user"
                    class="chatFullPageInput"
                    :class="{ 'text-gray-100': !chatStore.inputTooLong, 'text-red-600': chatStore.inputTooLong }"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import InputMessage from "@/Components/VideoPlayer/Chat/FullPageChatInput"
import ChatMessages from "@/Components/VideoPlayer/Chat/FullPageChatMessages"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore";
import { useChatStore } from "@/Stores/ChatStore";

let videoPlayer = useVideoPlayerStore()
let chatStore = useChatStore()

let props = defineProps({
    user: Object,
})

</script>
