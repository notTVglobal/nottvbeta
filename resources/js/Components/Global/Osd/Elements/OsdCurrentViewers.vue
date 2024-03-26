<template>
  <!--  Current Viewers are the number of people logged in watching. These people have access to the chat. -->
  <div
      class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-white bg-opacity-80 bg-black last:mr-0 ml-1">
    <font-awesome-icon icon="fa-solid fa-user" class="pr-1"/>
    <span v-text="channelStore.viewerCount"/>
  </div>

</template>

<script setup>
import { ref } from "vue"
import { useChannelStore } from "@/Stores/ChannelStore"

const channelStore = useChannelStore()

let props = defineProps({
  user: Object,
  currentViewers: ref()
})

</script>
