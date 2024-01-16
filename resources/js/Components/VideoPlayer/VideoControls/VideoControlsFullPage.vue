<template>
    <Transition
        enter-from-class="opacity-0 scale-300"
        enter-to-class="opacity-100 scale-100"
        enter-active-class="transition ease-in-out duration-500 transform"
        leave-active-class="transition ease-in-out duration-500 transform"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-300"
    >
<!-- Video FullPage Controls -->

        <div class="videoControls">

            <VideoControlsButtons />

        </div>

    </Transition>
</template>

<script setup>
import {useVideoPlayerStore} from "@/Stores/VideoPlayerStore.js"
import {useChatStore} from "@/Stores/ChatStore"
import {useUserStore} from "@/Stores/UserStore"
import VideoControlsButtons from "@/Components/VideoPlayer/VideoControls/VideoControlsButtons.vue";

let videoPlayerStore = useVideoPlayerStore()
let chatStore = useChatStore()
let userStore = useUserStore()

defineProps({
    show: Boolean
});


</script>
