<template>
<!--    <Transition-->
<!--        enter-from-class="opacity-0"-->
<!--        enter-to-class="opacity-100"-->
<!--        enter-active-class="transition duration-300"-->
<!--        leave-active-class="transition duration-200"-->
<!--        leave-from-class="opacity-100"-->
<!--        leave-to-class="opacity-0"-->
<!--    >-->
<!--&lt;!&ndash; this transition doesn't seem to be working&ndash;&gt;-->
<!--        <div v-if="show">-->

<!-- Video FullPage Controls -->

        <div class="videoControls">

            <VideoControlsButtons />

        </div>

<!--    </Transition>-->
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
