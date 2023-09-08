<template>
    <Transition
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        enter-active-class="transition duration-3000"
        leave-active-class="transition duration-2000"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >

            <div v-if="chatStore.showChat" class="chatFullPageContainer">

                <full-page-standard-chat :user="user"/>

                <button v-touch="()=>closeChat()"
                        v-if="chatStore.showChat" class="chatCloseButton">
                    CLOSE CHAT
                </button>
            </div>

    </Transition>
</template>

<script setup>
import { useChatStore } from "@/Stores/ChatStore";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore";
import FullPageStandardChat from "@/Components/VideoPlayer/Chat/FullPageStandardChat.vue";

let chatStore = useChatStore();
let videoPlayerStore = useVideoPlayerStore();

defineProps({
    user: Object,
})

function closeChat() {
    videoPlayerStore.toggleChat()
    videoPlayerStore.showOSD = true
}

</script>
