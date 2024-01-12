<template>
    <Transition
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        enter-active-class="transition duration-3000"
        leave-active-class="transition duration-2000"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >

            <div v-if="videoPlayerStore.ottChat"
                 :class="pipChatModeChangeTopPosition"
                 class="chatFullPageContainer hide-scrollbar">
                    <full-page-chat :user="user"/>
                    <button v-touch="()=>closeChat()"
                            v-if="videoPlayerStore.ottChat" class="chatCloseButton">
                        CLOSE CHAT
                    </button>
            </div>

    </Transition>
</template>

<script setup>
import { computed } from "vue";
import { useAppSettingStore } from '@/Stores/AppSettingStore';
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import FullPageChat from "@/Components/VideoPlayer/Chat/FullPageChat"

const appSettingStore = useAppSettingStore();
let videoPlayerStore = useVideoPlayerStore();

defineProps({
    user: Object,
})

function closeChat() {
    videoPlayerStore.toggleChat()
    videoPlayerStore.osd = true
    videoPlayerStore.makeVideoFullPage()
}

const pipChatModeChangeTopPosition = computed(() => {
    return appSettingStore.pipChatMode ? 'chatFullPageContainerChangeTopPositionForPipChatMode' : '';
});

</script>
