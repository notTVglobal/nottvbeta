<template>
<!--  Total watching is the number from MistServer... to account for people who are not logged in and watching. -->
  <div
      class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-white bg-opacity-80 bg-black last:mr-0">
    <font-awesome-icon icon="fa-solid fa-eye" class="pr-1"/>
    <span v-if="channelStore.totalWatching" v-text="channelStore.totalWatching"/>
    <span v-else>TBD</span>
  </div>

</template>

<script setup>
import { ref } from "vue"
import { useChannelStore } from "@/Stores/ChannelStore"

const channelStore = useChannelStore()

let props = defineProps({
  user: Object,
  totalWatching: ref()
})

</script>
