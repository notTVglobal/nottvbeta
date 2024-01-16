<template>
  <div class="videoOttTopRightButtonsContainer">
    <div class="flex flex-row h-7 justify-around bg-red-500 w-full text-xs uppercase font-semibold">

      <button class="h-full w-full border-r-2 border-r-gray-900"
              :class="infoClass"
              @click="appSettingStore.toggleOttInfo()">
        INFO
      </button>
      <button v-if="userStore.isVip || userStore.isAdmin"
              class="h-full w-full border-r-2 border-r-gray-900"
              :class="channelsClass"
              @click="appSettingStore.toggleOttChannels()">
        CHANNELS
      </button>
      <button class="h-full w-full border-r-2 border-r-gray-900"
              :class="playlistClass"
              @click="appSettingStore.toggleOttPlaylist()">
        PLAYLIST
      </button>
      <button class="h-full w-full border-r-2 border-r-gray-900"
              :class="chatClass"
              @click="appSettingStore.toggleOttChat()">
        CHAT
      </button>
      <button v-if="userStore.isVip || userStore.isAdmin"
              class="h-full w-full"
              :class="filtersClass"
              @click="appSettingStore.toggleOttFilters()">
        FILTERS
      </button>

    </div>
  </div>
</template>
<script setup>
import { computed } from "vue";

console.log('ButtonsTopRight')
import { useAppSettingStore } from '@/Stores/AppSettingStore'
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useUserStore } from "@/Stores/UserStore"

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()
const userStore = useUserStore()

const infoClass = computed(() => ({
  // all users
  'bg-purple-700': appSettingStore.ott === 1,
  'bg-purple-900 hover:bg-purple-700': appSettingStore.ott !== 1,
}))

const channelsClass = computed(() => ({
  // user is !vip
  'bg-gray-700': appSettingStore.ott === 2 && userNeedsToUpgrade(),
  'bg-gray-900 hover:bg-gray-700': appSettingStore.ott !== 2 && userNeedsToUpgrade(),
  // user is vip
  'bg-green-700': appSettingStore.ott === 2 && userIsVip(),
  'bg-green-900 hover:bg-green-700': appSettingStore.ott !== 2 && userIsVip(),
}))

const playlistClass = computed(() => ({
  // user is !subscriber
  'bg-gray-700': appSettingStore.ott === 3 && userNeedsToUpgrade(),
  'bg-gray-900 hover:bg-gray-700': appSettingStore.ott !== 3 && userNeedsToUpgrade(),
  // user is subscriber
  'bg-orange-700': appSettingStore.ott === 3 && userIsSubscriber(),
  'bg-orange-900 hover:bg-orange-700': appSettingStore.ott !== 3 && userIsSubscriber(),
}))

const chatClass = computed(() => ({
  // all users
  'bg-indigo-700': appSettingStore.ott === 4,
  'bg-indigo-900 hover:bg-indigo-700': appSettingStore.ott !== 4,
}))

const filtersClass = computed(() => ({
  // user is !vip
  'bg-gray-700': appSettingStore.ott === 5 && userNeedsToUpgrade(),
  'bg-gray-900 hover:bg-gray-700': appSettingStore.ott !== 5 && userNeedsToUpgrade(),
  // user is vip
  'bg-yellow-700': appSettingStore.ott === 5 && userIsVip(),
  'bg-yellow-900 hover:bg-yellow-700': appSettingStore.ott !== 5 && userIsVip(),
}))

function userNeedsToUpgrade() {
  return !(userStore.isSubscriber || userStore.isVip || userStore.isAdmin);
}

function userIsSubscriber() {
  return (userStore.isSubscriber || userStore.isVip || userStore.isAdmin);
}

function userIsVip() {
  return (userStore.isVip || userStore.isAdmin);
}

</script>