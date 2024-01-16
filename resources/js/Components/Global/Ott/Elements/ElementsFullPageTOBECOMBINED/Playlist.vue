<template>
  <Transition
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      enter-active-class="transition duration-3000"
      leave-active-class="transition duration-2000"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
  >

    <!--        <div v-if="videoPlayerStore.showPlaylist" class="playlistForStreamPageStandard w-96 max-w-[12rem] bottom-0 bg-opacity-80 bg-orange-600 text-sm text-white pb-12-->
    <!--                chat-mask overflow-y-auto scroll-smooth hover:scroll-auto break-words">-->
    <div v-if="videoPlayerStore.ottPlaylist" class="playlistFullPageContainer hide-scrollbar">

      <!--        Previous version (full screen background for Ott) -->
      <!--        <div v-if="videoPlayerStore.showChannels" class="channelsForStreamPageStandard max-w-screen h-screen left-0 bottom-0 sm:bottom-8 bg-opacity-80 bg-green-600 text-sm text-white pb-12-->
      <!--            chat-mask overflow-y-auto scroll-smooth hover:scroll-auto break-words">-->

      <div>


        <div class="top-0 px-5 space-y-2">
          <div>
            Coming soon.
          </div>
        </div>

      </div>

      <button v-touch="()=>videoPlayerStore.togglePlaylist()"
              v-if="videoPlayerStore.ottPlaylist" class="playlistCloseButton">
        CLOSE PLAYLIST
      </button>
    </div>

  </Transition>
</template>

<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
const appSettingStore = useAppSettingStore()

const videoPlayerStore = useVideoPlayerStore()

defineProps({
  user: Object,
})


</script>
