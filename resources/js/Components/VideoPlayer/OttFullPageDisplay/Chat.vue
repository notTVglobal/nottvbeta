<template>
    <Transition
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        enter-active-class="transition duration-3000"
        leave-active-class="transition duration-2000"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >

            <div v-if="videoPlayerStore.ottChat" class="chatFullPageContainer">
                <div class="chatFullPageInner">
                    <full-page-chat :user="user"/>

                    <button v-touch="()=>closeChat()"
                            v-if="videoPlayerStore.ottChat" class="chatCloseButton">
                        CLOSE CHAT
                    </button>
                </div>
            </div>

    </Transition>
</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import FullPageChat from "@/Components/VideoPlayer/Chat/FullPageChat"

let videoPlayerStore = useVideoPlayerStore();

defineProps({
    user: Object,
})

function closeChat() {
    videoPlayerStore.toggleChat()
    videoPlayerStore.osd = true
    videoPlayerStore.makeVideoFullPage()
}

</script>
