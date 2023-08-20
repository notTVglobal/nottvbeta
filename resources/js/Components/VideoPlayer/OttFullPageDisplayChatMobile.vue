<template>
    <Transition
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        enter-active-class="transition duration-3000"
        leave-active-class="transition duration-2000"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >

            <div v-if="chatStore.showChat" class="chatForStreamPageMobile w-100 bottom-0 sm:bottom-2 text-sm text-white pl-6 pb-12
                chat-mask overflow-y-auto scroll-smooth hover:scroll-auto break-words z-50">
                <button v-touch="()=>closeChat()"
                        class="chatCloseButtonMobile fixed right-5 opacity-80 w-15 h-15 p-4 rounded-full bg-orange-800
                        text-gray-50 hover:bg-blue-800 hover:text-blue-200 grid justify-center content-center
                        right-36 cursor-pointer font-semibold text-xs">
                    CLOSE CHAT
                </button>
                <full-page-mobile-chat :user="user"/>

<!--                <button v-touch="()=>videoPlayerStore.toggleChat() && videoPlayerStore.showOSD===true"-->

            </div>

    </Transition>
</template>

<script setup>
import { useChatStore } from "@/Stores/ChatStore";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore";
import FullPageMobileChat from "@/Components/Chat/FullPageMobileChat.vue";
import {onBeforeUnmount} from "vue";

let chatStore = useChatStore();
let videoPlayerStore = useVideoPlayerStore();

defineProps({
    user: Object,
})

function closeChat() {
    videoPlayerStore.toggleChat()
    videoPlayerStore.showOSD = true
}

onBeforeUnmount(() => {
});

</script>
