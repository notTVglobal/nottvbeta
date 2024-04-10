<template>
  <div v-if="showOttButtons">
    <component :is="buttonComponent" />
  </div>

  <NowPlayingInfo v-if="appSettingStore.ott === 1 && hasAccessTo('NowPlayingInfo')" :user="user"/>
  <Playlist v-if="appSettingStore.ott === 3 && hasAccessTo('Playlist')" :user="user"/>
  <Channels v-if="appSettingStore.ott === 2 && hasAccessTo('Channels')" :user="user"/>
  <ChatContainer v-if="appSettingStore.ott === 4 && hasAccessTo('ChatContainer')" :user="user" />
  <Filters v-if="appSettingStore.ott === 5 && hasAccessTo('Filters')" :user="user"/>
  <Upgrade v-if="showUpgrade" :user="user"/>

</template>
<script setup>
import { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"
import { useAppSettingStore } from "@/Stores/AppSettingStore"
import { useUserStore } from "@/Stores/UserStore"
import ButtonsFullPage from "@/Components/Global/Ott/Elements/ButtonsFullPage"
import ButtonsTopRight from "@/Components/Global/Ott/Elements/ButtonsTopRight"
import NowPlayingInfo from "@/Components/Global/Ott/Elements/NowPlayingInfo"
import Playlist from "@/Components/Global/Ott/Elements/Playlist"
import Channels from "@/Components/Global/Ott/Elements/Channels"
import Filters from "@/Components/Global/Ott/Elements/Filters"
import ChatContainer from '@/Components/Global/Ott/Elements/Chat'
import Upgrade from '@/Components/Global/Ott/Elements/Upgrade.vue'
import { computed } from 'vue'

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()
const userStore = useUserStore()

defineProps({
  user: Object,
})

const showOttButtons = computed(() => appSettingStore.showOttButtons)

const buttonComponent = computed(() => {
  return appSettingStore.fullPage ? ButtonsFullPage : ButtonsTopRight
})

const accessLevels = {
  NowPlayingInfo: () => true,
  Playlist: () => userStore.isSubscriber ||userStore.isVip || userStore.isAdmin,
  Channels: () => userStore.isSubscriber || userStore.isVip || userStore.isAdmin,
  ChatContainer: () => true,
  Filters: () => userStore.isVip || userStore.isAdmin,
}

const hasAccessTo = (componentName) => {
  return accessLevels[componentName]?.() ?? false
}

const showUpgrade = computed(() => {
  // Determine the current component based on ott value
  const currentComponentName = {
    1: 'NowPlayingInfo',
    2: 'Channels',
    3: 'Playlist',
    4: 'ChatContainer',
    5: 'Filters',
  }[appSettingStore.ott]

  // Check if user does not have access to the current component
  return currentComponentName && !hasAccessTo(currentComponentName)
})

</script>
