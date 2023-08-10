<template>
    <div class="scrollbar-hide">
        <div class="scrollbar-hide">
            <VideoOTTChatMessages />
        </div>
        <div>
            <VideoOTTChatInput :user="props.user" class="fixed bottom-20"/>
        </div>
    </div>
</template>

<script setup>
import VideoOTTChatInput from "@/Components/Chat/VideoOTTChatInput.vue";
import VideoOTTChatMessages from "@/Components/Chat/VideoOTTChatMessages.vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore";
import { useChatStore } from "@/Stores/ChatStore";

let videoPlayerStore = useVideoPlayerStore()
let chatStore = useChatStore()

let props = defineProps({
    user: Object,
})

</script>
