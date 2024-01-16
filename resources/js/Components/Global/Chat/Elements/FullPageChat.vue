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
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
const appSettingStore = useAppSettingStore()
import { useChatStore } from "@/Stores/ChatStore"
import { useUserStore } from "@/Stores/UserStore"
import InputMessage from "@/Components/Global/Chat/Elements/FullPageChatInput"
import ChatMessages from "@/Components/Global/Chat/Elements/FullPageChatMessages"

const videoPlayer = useVideoPlayerStore()
const chatStore = useChatStore()
const userStore = useUserStore()

let props = defineProps({
    user: Object,
})

</script>
