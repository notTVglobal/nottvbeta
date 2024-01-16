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

      <upgrade
          v-if="(appSettingStore.ott === 2 || videoPlayerStore.ottChannels) && !userStore.isSubscriber && !userStore.isVip && !userStore.isAdmin"/>
      <div
          v-if="(appSettingStore.ott === 2 || videoPlayerStore.ottChannels) && (userStore.isSubscriber || userStore.isVip || userStore.isAdmin)">

        <div ref="scrollRef"
             v-if="appSettingStore.ott === 2 || videoPlayerStore.ottChannels"
             class="channelsMenu ottTopRightDisplay bg-green-900 overflow-y-auto scrollbar-custom">

          <h1 v-if="appSettingStore.ott === 2"
              class="text-xs font-semibold uppercase w-full bg-green-900 text-white px-2 mt-2 mb-2 justify-center">
            CHANNELS
          </h1>

          <div v-if="videoPlayerStore.ottChannels" class="flex flex-col p-5 mt-2">
            <div class="text-3xl text-center font-semibold uppercase mb-3 w-full bg-green-900 text-white p-2">
              CHANNELS
            </div>
          </div>

          <div
              :class="[{'w-full':appSettingStore.ott === 2},{'px-5 space-y-1 overflow-y-scroll hide-scrollbar':videoPlayerStore.ottChannels}]">
            <Channels/>
          </div>


          <div class="fixed w-full bottom-4 text-center">
            <ScrollDownIndicator/>
          </div>

        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { computed, provide, ref } from "vue"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
const appSettingStore = useAppSettingStore()
import { useStreamStore } from "@/Stores/StreamStore"
import { useChatStore } from "@/Stores/ChatStore"
import { useUserStore } from "@/Stores/UserStore"
import Channels from "@/Components/Global/Ott/Elements/Channels"
import Upgrade from "@/Components/Global/Ott/Elements/Upgrade"
import ScrollDownIndicator from "@/Components/Global/UserHints/ScrollDownIndicator"

const videoPlayerStore = useVideoPlayerStore()
const streamStore = useStreamStore()
const chat = useChatStore()
const userStore = useUserStore()

const scrollRef = ref(null)
provide('scrollRef', scrollRef)

let props = defineProps({
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

</script>

<style scoped>


</style>
