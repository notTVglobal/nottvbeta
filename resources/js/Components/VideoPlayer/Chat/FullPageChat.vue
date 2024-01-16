<template>
    <div>
        <div class="absolute">
            <div>
                <chat-messages />
            </div>
            <div>
                <input-message
                    :user="props.user"
                    class="chatFullPageInput"
                    :class="[{ 'text-gray-100': !chatStore.inputTooLong, 'text-red-600': chatStore.inputTooLong }, {'isMobileComputed': userStore.isMobile}]"
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
import { useUserStore } from "@/Stores/UserStore";

let videoPlayer = useVideoPlayerStore()
let chatStore = useChatStore()
let userStore = useUserStore()

let props = defineProps({
    user: Object,
})

</script>
