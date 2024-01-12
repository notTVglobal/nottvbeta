<template>
<!--    <div v-if="videoPlayerStore.ott !== 0" class="fixed top-44 lg:top-78 right-0 w-full h-full lg:w-96 mt-4 overflow-y-none z-40 bg-blue-700"-->
<!--    :class="{'lg:mt-3':userStore.isMobile, 'lg:mt-2':!userStore.isMobile}">-->
<!--    <div class="h-full w-full overflow-y-scroll scrollbar-hide">-->

<!--        <div v-if="videoPlayerStore.ott === 4" class="w-full h-full px-2 overflow-y-scroll scrollbar-hide">-->
<!--            <div class="w-full overflow-y-scroll scrollbar-hide"-->
<!--                 :class="[{'h-[calc(100vh-22rem)]':!userStore.isMobile},{'h-[calc(100vh-20rem)]':userStore.isMobile}]">-->



    <!--                     class="fixed bottom-0 right-0 w-full lg:w-96 pb-24 px-2 overflow-y-scroll scrollbar-hide bg-gray-900"-->

        <div v-if="videoPlayerStore.ott === 4"
             :class="pipChatModeChangeStyle"
             class="ottTopRightDisplay hide-scrollbar">
<!--                <div v-if=""-->
<!--                     class="fixed top-44 lg:top-78 right-0 h-full w-full lg:w-96 mt-4 pb-12 px-2 overflow-y-scroll scrollbar-hide bg-gray-800"-->
<!--                     :class="ottDisplay">-->

                <div class="overflow-y-scroll w-full hide-scrollbar">

                            <VideoOTTChatMessages/>

                            <VideoOTTChatInput
                                :user="props.user"
                                class="fixed bottom-5 left-1/2 transform -translate-x-1/2 lg:left-auto lg:transform-none"
                                :class="{ 'text-gray-100': !chatStore.inputTooLong, 'text-red-600': chatStore.inputTooLong }"
                            />

                </div>
        </div>
<!--        </div>-->

<!--    </div>-->
<!--    </div>-->
</template>

<script setup>
import { computed } from "vue";
import { useAppSettingStore } from '@/Stores/AppSettingStore';
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore.js"
import { useStreamStore } from "@/Stores/StreamStore"
import { useChatStore } from "@/Stores/ChatStore.js"
import { useUserStore } from "@/Stores/UserStore"
import VideoOTTChatMessages from "@/Components/VideoPlayer/Chat/VideoOTTChatMessages.vue";
import VideoOTTChatInput from "@/Components/VideoPlayer/Chat/VideoOTTChatInput.vue";

const appSettingStore = useAppSettingStore();
let videoPlayerStore = useVideoPlayerStore()
let streamStore = useStreamStore()
let chatStore = useChatStore()
let userStore = useUserStore()

let props = defineProps ({
    user: Object,
})

let playVideo = (source) => {
    videoPlayerStore.loadNewSourceFromMist(source)
}

const ottDisplayShow = computed(() => ({
    'hidden': !videoPlayerStore.ott
}))

const ottDisplay = computed(() => ({
    ottDisplayMobile: userStore.isMobile,
    ottDisplayDesktop: !userStore.isMobile
}))

const pipChatModeChangeStyle = computed(() => {
    return appSettingStore.pipChatMode ? 'top-16' : 'bg-gray-800';
});

</script>
