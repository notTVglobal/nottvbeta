<template>

  <div v-if="appSettingStore.showOttButtons">
    <ButtonsFullPage v-if="appSettingStore.fullPage" />
    <ButtonsTopRight v-if="!appSettingStore.fullPage" />
  </div>

  <NowPlayingInfo :user="user"/>
  <Playlist :user="user"/>
  <Channels :user="user"/>
  <Chat :user="user"/>
  <Filters :user="user"/>

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
import Chat from "@/Components/Global/Ott/Elements/Chat"
import Filters from "@/Components/Global/Ott/Elements/Filters"

const appSettingStore = useAppSettingStore()
const videoPlayerStore = useVideoPlayerStore()
const userStore = useUserStore()
console.log('OttContainer')

defineProps({
  user: Object,
})

</script>
