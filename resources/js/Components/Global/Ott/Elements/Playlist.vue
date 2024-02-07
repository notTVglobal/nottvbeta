<template>
  <Transition
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      enter-active-class="transition duration-3000"
      leave-active-class="transition duration-2000"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
  >
    <div v-if="shouldDisplayOtt"
         :class="[appSettingStore.fullPage ? 'playlistFullPageContainer' : 'ottTopRightDisplay', 'bg-orange-800 hide-scrollbar']">

      <div class="h-full w-full overflow-y-hidden scrollbar-hide">

        <div v-if="showUpgrade && !appSettingStore.fullPage">
          <upgrade />
        </div>

        <div v-else class="now-playing w-full h-full bg-orange-800 p-2 overflow-y-scroll scrollbar-hide mb-64">

<!--          <div v-if="!appSettingStore.fullPage"-->
<!--              class="text-xs font-semibold uppercase w-full bg-orange-900 text-white p-2 mb-3">PLAYLIST</div>-->

<!--          <div v-else class="flex flex-col p-5 mt-2 mb-3">-->
<!--            <div class="text-3xl text-center font-semibold uppercase w-full bg-orange-800 text-white p-2">-->
<!--              PLAYLIST-->
<!--            </div>-->
<!--          </div>-->

          <div v-if="appSettingStore.fullPage" class="flex flex-col p-5 mt-2 mb-3 ">
            <div class="text-3xl text-center font-semibold uppercase w-full bg-orange-700 text-white p-2">PLAYLIST</div>
          </div>

          <div v-else class="text-xs font-semibold uppercase mb-3 w-full bg-orange-700 text-white p-2">PLAYLIST</div>

          <div class="top-0 px-5 space-y-2">
            <div>
              Coming soon.
            </div>
          </div>
        </div>

      </div>

      <div v-if="appSettingStore.fullPage" class="closeButtonContainer">
        <button v-touch="()=>appSettingStore.closeOtt()"
                v-if="appSettingStore.ott === 3" class="playlistCloseButton">
          CLOSE PLAYLIST
        </button>
      </div>

    </div>
  </Transition>
</template>

<script setup>
import { computed } from 'vue'
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useUserStore } from "@/Stores/UserStore"
// import { useStreamStore } from "@/Stores/StreamStore"
// import { useChatStore } from "@/Stores/ChatStore"
import Upgrade from "@/Components/Global/Ott/Elements/Upgrade"

const appSettingStore = useAppSettingStore()
const userStore = useUserStore()
// const videoPlayerStore = useVideoPlayerStore()
// const streamStore = useStreamStore()
// const chat = useChatStore()

let props = defineProps({
  user: Object,
})

// let playVideo = (source) => {
//   videoPlayerStore.loadNewSourceFromMist(source)
// }

// const ottDisplayShow = computed(() => ({
//   'hidden': !videoPlayerStore.ott
// }))

// const ottChannels = computed(() => ({
//   channelsOttMobile: userStore.isMobile,
//   channelsOttDesktop: !userStore.isMobile
// }))

const shouldDisplayOtt = computed(() => {
  return appSettingStore.ott === 3
})

const showUpgrade = computed(() => {
  return !userStore.isSubscriber &&
      !userStore.isVip &&
      !userStore.isAdmin;
});

</script>

<style scoped>


</style>
