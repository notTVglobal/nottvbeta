<template>
  <Transition
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      enter-active-class="transition duration-3000"
      leave-active-class="transition duration-2000"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
  >
    <div>
      <!--    <div v-if="appSettingStore.ott !== 0" class="fixed top-44 lg:top-78 right-0 w-full h-full lg:w-96 mt-4 overflow-y-none z-40 bg-blue-700"-->
      <!--    :class="{'lg:mt-3':userStore.isMobile, 'lg:mt-2':!userStore.isMobile}">-->
      <!--    <div class="h-full w-full overflow-y-scroll scrollbar-hide">-->

      <!--        <div v-if="appSettingStore.ott === 4" class="w-full h-full px-2 overflow-y-scroll scrollbar-hide">-->
      <!--            <div class="w-full overflow-y-scroll scrollbar-hide"-->
      <!--                 :class="[{'h-[calc(100vh-22rem)]':!userStore.isMobile},{'h-[calc(100vh-20rem)]':userStore.isMobile}]">-->


      <!--                     class="fixed bottom-0 right-0 w-full lg:w-96 pb-24 px-2 overflow-y-scroll scrollbar-hide bg-gray-900"-->

      <div v-if="appSettingStore.ott === 4"
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

      <div v-if="videoPlayerStore.ottChat && appSettingStore.fullPage"
           :class="pipChatModeChangeTopPosition"
           class="chatFullPageContainer hide-scrollbar">
        <full-page-chat :user="user"/>
        <button v-touch="()=>closeChat()"
                v-if="videoPlayerStore.ottChat" class="chatCloseButton">
          CLOSE CHAT
        </button>
      </div>
      <!--        </div>-->

      <!--    </div>-->
      <!--    </div>-->
    </div>
  </Transition>Z
</template>

<script setup>
import { computed } from "vue"
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useStreamStore } from "@/Stores/StreamStore"
import { useChatStore } from "@/Stores/ChatStore"
import { useUserStore } from "@/Stores/UserStore"
import VideoOTTChatMessages from "@/Components/Global/Chat/Elements/VideoOTTChatMessages"
import VideoOTTChatInput from "@/Components/Global/Chat/Elements/VideoOTTChatInput"
import FullPageChat from "@/Components/Global/Chat/Elements/FullPageChat"

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()
const streamStore = useStreamStore()
const chatStore = useChatStore()
const userStore = useUserStore()

let props = defineProps({
  user: Object,
})

let playVideo = (source) => {
  videoPlayerStore.loadNewSourceFromMist(source)
}

function closeChat() {
  if (userStore.isMobile) {
    videoPlayerStore.toggleChat()
    videoPlayerStore.osd = true
    chatStore.turnPipChatModeOff()
  } else {
    videoPlayerStore.toggleChat()
    videoPlayerStore.osd = true
    videoPlayerStore.makeVideoFullPage()
  }
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

const pipChatModeChangeTopPosition = computed(() => {
  return appSettingStore.pipChatMode ? 'chatFullPageContainerChangeTopPositionForPipChatMode' : '';
});

</script>
