<template>
    <Transition
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        enter-active-class="transition duration-3000"
        leave-active-class="transition duration-2000"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >

            <div v-if="chatStore.showChat" class="chatForStreamPageStandard w-96 max-w-[12rem] bottom-0 bg-opacity-30 bg-blue-600 text-sm text-white pb-12
                chat-mask overflow-y-auto scroll-smooth hover:scroll-auto break-words">

                <full-page-standard-chat :user="user"/>

                <button v-touch="()=>videoPlayerStore.toggleChat()"
                        v-if="chatStore.showChat" class="fixed bottom-5 right-32 opacity-100 w-15 h-15 p-4 rounded-full
                    text-gray-50 bg-blue-800 hover:bg-blue-600 grid justify-center content-center
                    cursor-pointer font-semibold text-xs">
                    CLOSE CHAT
                </button>
            </div>

    </Transition>
</template>

<script setup>
import { useChatStore } from "@/Stores/ChatStore";
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore";
import FullPageStandardChat from "@/Components/Chat/FullPageStandardChat.vue";

let chatStore = useChatStore();
let videoPlayerStore = useVideoPlayerStore();

defineProps({
    user: Object,
})

</script>
