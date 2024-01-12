<template>

</template>

<script setup>
import VideoOTTChatInput from "@/Components/VideoPlayer/Chat/VideoOTTChatInput.vue";
import VideoOTTChatMessages from "@/Components/VideoPlayer/Chat/VideoOTTChatMessages.vue";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore";
import { useChatStore } from "@/Stores/ChatStore";

let videoPlayerStore = useVideoPlayerStore()
let chatStore = useChatStore()

let props = defineProps({
    user: Object,
})

</script>
