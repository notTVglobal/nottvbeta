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
      <transition name="fade">
        <div v-if="chatStore.errorMessage" class="absolute w-96 px-6 right-0 bottom-24 z-999 py-1 error-message text-yellow-500 bg-black bg-opacity-80">
          {{ chatStore.errorMessage }}
        </div>
      </transition>
      <div v-if="divClass" :class="divClass">

        <OttChatMessages/>

<!--        <OttChatInput-->
<!--            :user="user"-->
<!--            class="fixed bottom-5 left-1/2 transform -translate-x-1/2 lg:left-auto lg:transform-none w-full"-->
<!--            :class="{ 'text-gray-100': !chatStore.inputTooLong, 'text-red-600': chatStore.inputTooLong }"-->
<!--        />-->

        <OttChatInput
            :user="user"
            class="absolute w-full lg:w-96 px-6 right-0 bottom-5"
            :class="{ 'text-gray-100': !chatStore.inputTooLong, 'text-red-600': chatStore.inputTooLong }"
        />


          <button v-if="appSettingStore.fullPage" @click="closeChat"
                :class="chatCloseClass">
          CLOSE CHAT
        </button>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useVideoPlayerStore } from '@/Stores/VideoPlayerStore'
import { useChatStore } from '@/Stores/ChatStore'
import { useUserStore } from '@/Stores/UserStore'
import OttChatMessages from '@/Components/Global/Chat/OttChatMessages'
import OttChatInput from '@/Components/Global/Chat/OttChatInput'
import { computed, onMounted } from 'vue'

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()
const chatStore = useChatStore()
const userStore = useUserStore()

defineProps({
  user: Object,
})

function closeChat() {
  if (userStore.isMobile) {
    appSettingStore.toggleOttChat()
    appSettingStore.osd = true
    appSettingStore.turnPipChatModeOff()
  } else {
    appSettingStore.toggleOttChat()
    appSettingStore.osd = true
    videoPlayerStore.makeVideoFullPage()
  }
  appSettingStore.closeOtt()
}

const pipChatModeChangeTopPosition = computed(() => {
  return appSettingStore.pipChatMode ? 'chatFullPageContainerChangeTopPositionForPipChatMode' : ''
})

const pipChatModeChangeStyle = computed(() => {
  return appSettingStore.pipChatMode ? 'top-16' : 'bg-gray-950'
})

const divClass = computed(() => {
  if (appSettingStore.ott === 4) {
    if (appSettingStore.fullPage && !appSettingStore.pipChatMode) {
      return `chatFullPageContainer hide-scrollbar ${pipChatModeChangeTopPosition.value}`
    } else if (appSettingStore.fullPage && appSettingStore.pipChatMode) {
      return `pipChatFullPageContainer hide-scrollbar ${pipChatModeChangeTopPosition.value}`
    } else {
      return `ottTopRightDisplay hide-scrollbar ${pipChatModeChangeStyle.value}`
    }
  }
  return null // Return null if `ott` is not 4
})

const chatCloseClass = computed(() => {
    return userStore.isMobile ? 'chatCloseButtonIsMobile' : 'chatCloseButton'
})

onMounted(() => {
  chatStore.errorMessage = ''
})

</script>

<style>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
  opacity: 0;
}
</style>