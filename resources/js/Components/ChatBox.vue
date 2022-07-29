<template>
    <div class="w-1/3 absolute top-16 right-0 h-full pt-16 overflow-hidden p-10 chat text-white" :class="chat.class">
    <div class="chatBoxes text-white opacity-100">Insert Chat Box here. It needs to be on top of the video player as an overlay on desktops and tablets, but below the video on mobile devices.</div>
    </div>
    <div class="bg-black opacity-25 w-1/3 absolute top-16 right-0 z-20 h-full pt-16 overflow-hidden p-10 chat text-white">

    </div>
</template>

<script setup>
import { useChatStore } from "@/Stores/ChatStore.js";
let chat = useChatStore();
</script>

<style scoped>
.chat {
    --chat-container-height: 500px;
    --chat-size: calc(var(--chat-container-height) / 2);
    height: var(--chat-size);
}
</style>
