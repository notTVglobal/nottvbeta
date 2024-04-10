<template>

  <div class=""
       :class="[appSettingStore.fullPage ? 'upgradeFullPageContainer' : 'ottTopRightDisplay', 'bg-gray-600 hide-scrollbar']">

    <h1 class="text-xs font-semibold uppercase mt-2 mb-3 w-full bg-yellow-600 text-black p-2">Upgrade</h1>
    <div class="pb-24 px-8 w-full overflow-y-scroll scrollbar-hide"
         :class="[{'h-[calc(100vh-22rem)]':!userStore.isMobile},{'h-[calc(100vh-20rem)]':userStore.isMobile}]">
      Upgrade to a Premium Subscription to gain access to more features, movies and channels!
      <div class="relative top-0 left-0 right-0 w-fit mx-auto mt-2">
        <button class="rounded-lg py-1 px-2 bg-blue-800 hover:bg-blue-600 text-white"
                @click="upgrade">
          Upgrade now!
        </button>
      </div>
    </div>

    <div v-if="appSettingStore.fullPage" class="closeButtonContainer">
      <button v-touch="()=>appSettingStore.closeOtt()"
              v-if="appSettingStore.ott === 2" class="upgradeCloseButton">
        CLOSE UPGRADE
      </button>
    </div>

  </div>

</template>

<script setup>
import { Inertia } from "@inertiajs/inertia"
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
const appSettingStore = useAppSettingStore()
import { useUserStore } from "@/Stores/UserStore"

const videoPlayerStore = useVideoPlayerStore()
const userStore = useUserStore()

function upgrade() {
  Inertia.get('/contribute/subscription')

  appSettingStore.ott = 0
}

</script>
