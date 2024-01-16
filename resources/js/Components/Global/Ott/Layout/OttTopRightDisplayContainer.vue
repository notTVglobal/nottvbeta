<template>
  <ButtonsOttTopRight :key="userStore.key"/>
  <Info :user="user"/>
  <Playlist :user="user"/>
  <Channels :user="user"/>
  <Chat :user="user"/>
  <Filters :user="user"/>

</template>
<script setup>
import { useUserStore } from "@/Stores/UserStore"
import ButtonsOttTopRight from "@/Components/Global/Ott/Elements/ButtonsOttTopRight"
import Info from "@/Components/Global/Ott/Elements/Info"
import Filters from "@/Components/Global/Ott/Elements/Filters"
import Chat from "@/Components/Global/Ott/Elements/Chat"
import Playlist from "@/Components/Global/Ott/Elements/Playlist"
import Channels from "@/Components/Global/Ott/Elements/Channels"

const userStore = useUserStore()

defineProps({
  user: Object,
})

</script>
